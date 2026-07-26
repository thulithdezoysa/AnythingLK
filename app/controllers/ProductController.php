<?php
// ============================================================
// ProductController
// ============================================================
require_once CORE . '/Controller.php';
require_once APP  . '/models/ProductModel.php';
require_once APP  . '/models/TrackingModel.php';

class ProductController extends Controller {

    private ProductModel $pm;

    public function __construct() {
        parent::__construct();
        $this->pm = new ProductModel();
    }

    private function buildFilters(): array {
        return [
            'q'         => trim($_GET['q']        ?? ''),
            'sort'      => $_GET['sort']           ?? 'newest',
            'min_price' => $_GET['min_price']      ?? '',
            'max_price' => $_GET['max_price']      ?? '',
            'brand'     => $_GET['brand']          ?? '',
        ];
    }

    public function listing(array $params = []): void {
        $filters = $this->buildFilters();
        $page    = max(1, (int)($_GET['page'] ?? 1));
        $result  = $this->pm->getListing($filters, $page, 16);
        $pagData = Helper::paginate($result['total'], 16, $page);
        $catTree = $this->pm->getCategoryTreeWithCounts($filters);
        $brands  = $this->pm->getBrandsWithCounts($filters);

        $this->view('product/listing', compact('result', 'pagData', 'filters', 'catTree', 'brands'));
    }

    public function category(array $params): void {
        $cat = $this->pm->getCategoryBySlug($params['slug']);
        if (!$cat) { http_response_code(404); $this->view('errors/404'); return; }

        $filters      = array_merge($this->buildFilters(), ['category' => $params['slug']]);
        $page         = max(1, (int)($_GET['page'] ?? 1));
        $result       = $this->pm->getListing($filters, $page, 16);
        $pagData      = Helper::paginate($result['total'], 16, $page);
        $catTree      = $this->pm->getCategoryTreeWithCounts($filters);
        $brands       = $this->pm->getBrandsWithCounts($filters);
        $categoryPath = $this->pm->getCategoryAncestors((int)$cat['id']);

        $this->view('product/listing', compact('result', 'pagData', 'filters', 'cat', 'catTree', 'brands', 'categoryPath'));
    }

    public function brand(array $params): void {
        $slug  = $this->db->connect()->real_escape_string($params['slug']);
        $brand = $this->db->selectOne("SELECT * FROM brands WHERE slug='$slug'");
        if (!$brand) { http_response_code(404); $this->view('errors/404'); return; }

        $filters = array_merge($this->buildFilters(), ['brand' => $params['slug']]);
        $page    = max(1, (int)($_GET['page'] ?? 1));
        $result  = $this->pm->getListing($filters, $page, 16);
        $pagData = Helper::paginate($result['total'], 16, $page);
        $catTree = $this->pm->getCategoryTreeWithCounts($filters);
        $brands  = $this->pm->getBrandsWithCounts($filters);

        $this->view('product/listing', compact('result', 'pagData', 'filters', 'brand', 'catTree', 'brands'));
    }

    public function quickView(array $params): void {
        $product = $this->pm->getBySlug($params['slug']);
        if (!$product) { $this->json(['success' => false], 404); return; }

        $stockQty = (int)($product['stock']['quantity'] ?? 0);
        $effPrice = (float)($product['sale_price'] ?: $product['price']);
        $hasDisc  = !empty($product['sale_price']) && (float)$product['sale_price'] < (float)$product['price'];
        $discPct  = $hasDisc ? round(((float)$product['price'] - (float)$product['sale_price']) / (float)$product['price'] * 100) : 0;

        $this->json([
            'success'      => true,
            'id'           => (int)$product['id'],
            'name'         => $product['name'],
            'slug'         => $product['slug'],
            'thumbnail'    => $product['thumbnail'] ?? '',
            'images'       => array_slice($product['images'] ?? [], 0, 5),
            'price'        => (float)$product['price'],
            'sale_price'   => (float)$product['sale_price'],
            'eff_price'    => $effPrice,
            'has_disc'     => $hasDisc,
            'disc_pct'     => $discPct,
            'short_desc'   => $product['short_desc'] ?? '',
            'track_stock'  => (bool)$product['track_stock'],
            'stock_qty'    => $stockQty,
            'variations'   => $product['variations'] ?? [],
            'avg'          => round((float)($product['avg_rating']['avg'] ?? 0), 1),
            'rating_total' => (int)($product['avg_rating']['total'] ?? 0),
            'category'     => $product['category_name'] ?? '',
            'brand'        => $product['brand_name'] ?? '',
            'sku'          => $product['sku'] ?? '',
        ]);
    }

    public function detail(array $params): void {
        $product = $this->pm->getBySlug($params['slug']);
        if (!$product) { http_response_code(404); $this->view('errors/404'); return; }

        $this->pm->incrementViews($product['id']);

        $pid          = (int)$product['id'];
        $catId        = (int)$product['category_id'];
        $brandId      = (int)($product['brand_id'] ?? 0);
        $categoryPath = $catId ? $this->pm->getCategoryAncestors($catId) : [];

        $related    = $this->pm->getRelated($pid, $catId, 8);
        $reviews    = $this->pm->getReviews($pid);
        $fbt        = $this->pm->getFrequentlyBought($pid);
        $youMayLike = $this->pm->getYouMayLike($pid, $brandId, $catId, 8);

        // Payment methods: product-specific first, fallback to all active
        $productPm = $this->db->select(
            "SELECT pm.* FROM payment_methods pm
             JOIN product_payment_methods ppm ON ppm.method_code = pm.code
             WHERE ppm.product_id = $pid AND pm.is_active = 1
             ORDER BY pm.sort_order"
        ) ?: [];
        $paymentMethods = !empty($productPm)
            ? $productPm
            : ($this->db->select("SELECT * FROM payment_methods WHERE is_active=1 ORDER BY sort_order") ?: []);

        // Active product-level discount
        $now = date('Y-m-d H:i:s');
        $productDiscount = $this->db->selectOne(
            "SELECT * FROM product_discounts
             WHERE product_id=$pid AND status=1
               AND (start_date IS NULL OR start_date <= '$now')
               AND (end_date   IS NULL OR end_date   >= '$now')
             ORDER BY id DESC LIMIT 1"
        );

        // Active global discounts
        $globalDiscounts = $this->db->select(
            "SELECT * FROM global_discounts WHERE status=1
               AND (start_date IS NULL OR start_date <= '$now')
               AND (end_date   IS NULL OR end_date   >= '$now')
             ORDER BY priority DESC LIMIT 3"
        ) ?: [];

        // Track view
        (new TrackingModel())->track(Auth::id(), session_id(), $pid, 'view');

        $this->view('product/detail', compact(
            'product','related','reviews','fbt','youMayLike',
            'paymentMethods','productDiscount','globalDiscounts','categoryPath'
        ));
    }
}
