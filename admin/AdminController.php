<?php
require_once ROOT . '/core/Controller.php';

class AdminController extends Controller {

    public function __construct() {
        parent::__construct();
        // Double-check auth
        if (!Auth::check() || !Auth::isSupervisor()) {
            header('Location: ' . Helper::url('login'));
            exit;
        }
    }

    protected function view(string $viewPath, array $data = [], string $layout = 'admin'): void {
        $data['lang']        = Lang::all();
        $data['csrfToken']   = CSRF::token();
        $data['currentLang'] = Lang::current();
        $data['user']        = Auth::user();
        $data['cartCount']   = 0;
        $data['adminUser']   = Auth::user();
        $data['isSuperAdmin']= Auth::isAdmin();
        $data['isSupervisor']= Auth::isSupervisor();
        $data['siteName']    = setting('site_name', 'Anything.lk');

        // Sidebar counters
        try {
            $data['pendingOrders']  = (int)($this->db->selectOne("SELECT COUNT(*) AS c FROM orders WHERE status='pending'")['c'] ?? 0);
            $data['lowStockCount']  = (int)($this->db->selectOne("SELECT COUNT(*) AS c FROM product_stock WHERE quantity <= low_stock_threshold AND quantity > 0")['c'] ?? 0);
            $data['pendingReviews'] = (int)($this->db->selectOne("SELECT COUNT(*) AS c FROM product_reviews WHERE status='pending'")['c'] ?? 0);
            $data['unreadMessages'] = (int)($this->db->selectOne("SELECT COUNT(*) AS c FROM contact_messages WHERE is_read=0")['c'] ?? 0);
        } catch (\Throwable $e) {
            $data['pendingOrders'] = $data['lowStockCount'] = $data['pendingReviews'] = $data['unreadMessages'] = 0;
        }

        extract($data);

        $viewFile = ADMIN . '/views/' . $viewPath . '.php';
        if (!file_exists($viewFile)) {
            $viewFile = APP . '/views/' . $viewPath . '.php';
        }
        if (!file_exists($viewFile)) {
            die("Admin view not found: $viewPath");
        }

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        $layoutFile = ADMIN . '/views/layouts/' . $layout . '.php';
        if (file_exists($layoutFile)) {
            require $layoutFile;
        } else {
            echo $content;
        }
    }
}
