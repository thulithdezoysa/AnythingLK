<?php
require_once ADMIN . '/AdminController.php';
require_once APP   . '/models/StockModel.php';

// ============================================================
// AdminDashboardController
// ============================================================
class AdminDashboardController extends AdminController {

    public function index(array $p = []): void {
        $pageTitle = 'Dashboard';

        // Stats
        $stats = [
            'total_orders'    => (int)($this->db->selectOne("SELECT COUNT(*) AS c FROM orders")['c'] ?? 0),
            'total_revenue'   => (float)($this->db->selectOne("SELECT SUM(total_amount) AS s FROM orders WHERE status NOT IN ('cancelled','refunded')")['s'] ?? 0),
            'total_products'  => (int)($this->db->selectOne("SELECT COUNT(*) AS c FROM products WHERE status='active'")['c'] ?? 0),
            'total_customers' => (int)($this->db->selectOne("SELECT COUNT(*) AS c FROM users WHERE role='customer'")['c'] ?? 0),
            'pending_orders'  => (int)($this->db->selectOne("SELECT COUNT(*) AS c FROM orders WHERE status='pending'")['c'] ?? 0),
            'low_stock'       => (int)($this->db->selectOne("SELECT COUNT(*) AS c FROM product_stock WHERE quantity <= low_stock_threshold AND quantity > 0")['c'] ?? 0),
        ];

        // Recent orders
        $recentOrders = $this->db->select(
            "SELECT o.*, u.full_name FROM orders o
             LEFT JOIN users u ON u.id=o.user_id
             ORDER BY o.created_at DESC LIMIT 10"
        ) ?: [];

        // Monthly revenue (last 6 months)
        $monthlyRevenue = $this->db->select(
            "SELECT DATE_FORMAT(created_at,'%Y-%m') AS month,
                    SUM(total_amount) AS revenue,
                    COUNT(*) AS orders
             FROM orders WHERE status NOT IN ('cancelled','refunded')
               AND created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
             GROUP BY DATE_FORMAT(created_at,'%Y-%m') ORDER BY DATE_FORMAT(created_at,'%Y-%m')"
        ) ?: [];

        // Top products
        $topProducts = $this->db->select(
            "SELECT p.name, p.thumbnail, SUM(oi.quantity) AS sold, SUM(oi.total_price) AS revenue
             FROM order_items oi JOIN products p ON p.id=oi.product_id
             GROUP BY oi.product_id ORDER BY sold DESC LIMIT 5"
        ) ?: [];

        // Low stock alerts
        $lowStockItems = (new StockModel())->getLowStock();

        $this->view('dashboard/index', compact('pageTitle','stats','recentOrders','monthlyRevenue','topProducts','lowStockItems'));
    }
}
