<?php
require_once CORE . '/Controller.php';

// ============================================================
// LangController
// ============================================================
class LangController extends Controller {
    public function switch(array $params): void {
        $code = $params['code'] ?? 'en';
        $supported = ['en','si','ta'];
        if (!in_array($code, $supported)) $code = 'en';
        $_SESSION['lang'] = $code;
        setcookie('lang', $code, time() + (86400 * 30), '/');
        $ref = $_SERVER['HTTP_REFERER'] ?? url('');
        header("Location: $ref");
        exit;
    }
}
