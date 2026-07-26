<?php
// ============================================================
// BASE CONTROLLER
// ============================================================
require_once ROOT . '/db_connect.php';
require_once CORE . '/Helper.php';

class Controller {

    protected Db $db;
    protected array $data = [];

    public function __construct() {
        $this->db = new Db();
    }

    // --------------------------------------------------------
    // Render a view inside the layout
    // --------------------------------------------------------
    protected function view(string $viewPath, array $data = [], string $layout = 'default'): void {
        // Merge shared data
        $data['lang']         = Lang::all();
        $data['csrfToken']    = CSRF::token();
        $data['currentLang']  = Lang::current();
        // Don't overwrite a DB user row the controller already injected
        if (!array_key_exists('user', $data)) {
            $data['user'] = Auth::user();
        }
        $data['cartCount']    = $this->getCartCount();
        $data['navCategories']        = Helper::categories();
        $data['navRootCats']          = Helper::rootCategories();
        $data['footerPaymentMethods'] = $this->db->select(
            "SELECT code, display_name FROM payment_methods WHERE is_active=1 ORDER BY sort_order"
        ) ?: [];

        // Make data available as variables
        extract($data);

        $viewFile = APP . '/views/' . $viewPath . '.php';

        if (!file_exists($viewFile)) {
            http_response_code(500);
            die("View not found: $viewPath");
        }

        // Capture view content
        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        // Wrap in layout
        $layoutFile = APP . '/views/layouts/' . $layout . '.php';
        if (file_exists($layoutFile)) {
            require $layoutFile;
        } else {
            echo $content;
        }
    }

    // --------------------------------------------------------
    // JSON response (for AJAX)
    // --------------------------------------------------------
    protected function json(array $data, int $code = 200): void {
        // Discard any accidental output (notices, warnings, whitespace) before JSON
        while (ob_get_level()) ob_end_clean();
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    // --------------------------------------------------------
    // Redirect
    // --------------------------------------------------------
    protected function redirect(string $path): void {
        $base = Helper::baseUrl();
        header("Location: $base/$path");
        exit;
    }

    // --------------------------------------------------------
    // Require login (redirect if not)
    // --------------------------------------------------------
    protected function requireLogin(): void {
        if (!Auth::check()) {
            $this->redirect('login');
        }
    }

    // --------------------------------------------------------
    // Cart count helper
    // --------------------------------------------------------
    private function getCartCount(): int {
        if (Auth::check()) {
            $userId = Auth::user()['id'];
            $row = $this->db->selectOne("SELECT SUM(quantity) as cnt FROM cart WHERE user_id = $userId");
        } else {
            $sessionId = session_id();
            $row = $this->db->selectOne("SELECT SUM(quantity) as cnt FROM cart WHERE session_id = '$sessionId' AND user_id IS NULL");
        }
        return (int)($row['cnt'] ?? 0);
    }
}
