<?php
require_once CORE . '/Controller.php';
require_once APP  . '/models/ProductModel.php';

class SearchController extends Controller {

    public function index(array $params = []): void {
        $q    = Helper::sanitize($_GET['q'] ?? '');
        $page = max(1, (int)($_GET['page'] ?? 1));

        // AJAX live-search (returns JSON)
        if (!empty($_GET['ajax'])) {
            $model    = new ProductModel();
            $products = $model->liveSearch($q, 6);

            $db  = $this->db->connect();
            $esc = $db->real_escape_string($q);

            // Brands: prefix matches first, then any contains-match
            $brands = $this->db->select(
                "SELECT id, name, slug FROM brands
                 WHERE name LIKE '%$esc%'
                   AND EXISTS(SELECT 1 FROM products WHERE brand_id = brands.id AND status = 'active')
                 ORDER BY (name LIKE '$esc%') DESC, name
                 LIMIT 3"
            ) ?: [];

            // Categories: case-insensitive partial match on cached list
            $cats = array_slice(array_values(array_map(
                fn($c) => ['id' => $c['id'], 'name' => $c['name'], 'slug' => $c['slug']],
                array_filter(Helper::categories(), fn($c) => stripos($c['name'], $q) !== false)
            )), 0, 3);

            $this->json([
                'products' => array_map(fn($p) => [
                    'id'            => $p['id'],
                    'name'          => $p['name'],
                    'slug'          => $p['slug'],
                    'price'         => (float)($p['sale_price'] ?: $p['price']),
                    'thumbnail'     => $p['thumbnail'] ?? null,
                    'brand_name'    => $p['brand_name'] ?? null,
                    'category_name' => $p['category_name'] ?? null,
                ], $products),
                'brands' => $brands,
                'cats'   => $cats,
            ]);
        }

        // Full search results page
        $result  = (new ProductModel())->getListing(['q' => $q], $page, 16);
        $pagData = Helper::paginate($result['total'], 16, $page);
        $filters = ['q' => $q, 'sort' => $_GET['sort'] ?? 'newest'];

        $this->view('product/listing', compact('result', 'pagData', 'filters', 'q'));
    }
}
