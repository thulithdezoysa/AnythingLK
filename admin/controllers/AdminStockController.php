<?php
require_once ADMIN . '/AdminController.php';
require_once APP   . '/models/StockModel.php';

class AdminStockController extends AdminController {

    private StockModel $sm;

    public function __construct() {
        parent::__construct();
        $this->sm = new StockModel();
    }

    public function index(array $p = []): void {
        $pageTitle = 'Stock Management';
        $db        = $this->db->connect();
        $search    = Helper::sanitize($_GET['search'] ?? '');
        $wh        = $db->real_escape_string($_GET['warehouse'] ?? 'Main');
        $cond      = "AND ps.warehouse = '$wh'";
        if ($search) {
            $sq    = $db->real_escape_string($search);
            $cond .= " AND (p.name LIKE '%$sq%' OR p.sku LIKE '%$sq%')";
        }
        $pid = (int)($_GET['product'] ?? 0);
        if ($pid) $cond .= " AND p.id = $pid";

        $perPage = in_array((int)($_GET['per_page'] ?? 25), [10,25,50,100]) ? (int)($_GET['per_page'] ?? 25) : 25;
        $page    = max(1, (int)($_GET['page'] ?? 1));
        $offset  = ($page - 1) * $perPage;

        $total = (int)($this->db->selectOne(
            "SELECT COUNT(*) AS cnt
             FROM product_stock ps
             JOIN products p ON p.id = ps.product_id
             WHERE 1=1 $cond"
        )['cnt'] ?? 0);

        $stock = $this->db->select(
            "SELECT ps.*, p.name AS product_name, p.slug, p.sku, p.thumbnail, p.id AS pid,
                    pv.name AS variation_name, c.name AS category_name
             FROM product_stock ps
             JOIN products p ON p.id = ps.product_id
             LEFT JOIN product_variations pv ON pv.id = ps.variation_id
             LEFT JOIN categories c ON c.id = p.category_id
             WHERE 1=1 $cond
             ORDER BY ps.quantity ASC, p.name ASC
             LIMIT $perPage OFFSET $offset"
        ) ?: [];

        $pagData    = Helper::paginate($total, $perPage, $page);
        $products   = $this->db->select("SELECT id, name FROM products WHERE status='active' ORDER BY name") ?: [];
        $warehouses = $this->db->select("SELECT DISTINCT warehouse FROM product_stock ORDER BY warehouse") ?: [];

        $this->view('stock/index', compact('pageTitle','stock','search','products','warehouses','wh','perPage','pagData','total'));
    }

    public function lowStock(array $p = []): void {
        $pageTitle    = 'Low Stock Alerts';
        $lowStockItems = $this->sm->getLowStock();
        $outOfStock    = $this->sm->getOutOfStock();
        $this->view('stock/low_stock', compact('pageTitle','lowStockItems','outOfStock'));
    }

    public function movements(array $p = []): void {
        $pageTitle = 'Stock Movement History';
        $page      = max(1, (int)($_GET['page'] ?? 1));
        $productId = (int)($_GET['product_id'] ?? 0);
        $movements = $this->sm->getAllMovements($page, 30, $productId ?: null);
        $filterProduct = null;
        if ($productId) {
            $filterProduct = $this->db->selectOne("SELECT id, name FROM products WHERE id = $productId");
        }
        $this->view('stock/movements', compact('pageTitle','movements','page','filterProduct'));
    }

    public function adjust(array $p = []): void {
        CSRF::check();
        $productId   = (int)($_POST['product_id'] ?? 0);
        $newQty      = (int)($_POST['quantity'] ?? 0);
        $notes       = Helper::sanitize($_POST['notes'] ?? '');
        $warehouse   = Helper::sanitize($_POST['warehouse'] ?? 'Main');
        $variationId = (int)($_POST['variation_id'] ?? 0) ?: null;

        if (!$productId) { $this->json(['success'=>false,'message'=>'Invalid product.']); }

        $this->sm->adjust($productId, $newQty, $warehouse, $variationId, $notes, Auth::id());
        $this->db->logActivity(Auth::id(), 'STOCK_ADJUST', 'product_stock', $productId,
                               null, ['qty'=>$newQty,'warehouse'=>$warehouse,'notes'=>$notes]);

        $this->json(['success'=>true,'message'=>"Stock adjusted to $newQty."]);
    }

    public function emailAlert(array $p = []): void {
        CSRF::check();
        $items = $this->sm->getLowStock();
        if (empty($items)) {
            $this->json(['success'=>false,'message'=>'No low-stock items to alert about.']);
            return;
        }
        $sent = MailService::sendLowStockAlert($items);
        $this->json([
            'success' => $sent,
            'message' => $sent ? 'Low-stock alert email sent to admin.' : 'Failed to send email. Check mail configuration.',
        ]);
    }

    public function stockIn(array $p = []): void {
        CSRF::check();
        $productId   = (int)($_POST['product_id'] ?? 0);
        $qty         = (int)($_POST['quantity'] ?? 0);
        $notes       = Helper::sanitize($_POST['notes'] ?? '');
        $warehouse   = Helper::sanitize($_POST['warehouse'] ?? 'Main');
        $variationId = (int)($_POST['variation_id'] ?? 0) ?: null;

        if (!$productId || $qty <= 0) { $this->json(['success'=>false,'message'=>'Invalid data.']); }

        $this->sm->stockIn($productId, $qty, $warehouse, $variationId, $notes, Auth::id());
        $this->db->logActivity(Auth::id(), 'STOCK_IN', 'product_stock', $productId,
                               null, ['qty'=>$qty,'warehouse'=>$warehouse,'notes'=>$notes]);

        $this->json(['success'=>true,'message'=>"$qty units added to stock."]);
    }
}
