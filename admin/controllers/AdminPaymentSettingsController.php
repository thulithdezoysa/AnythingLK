<?php
require_once CORE . '/PaymentGatewayFactory.php';

class AdminPaymentSettingsController extends AdminController {

    public function index(array $p = []): void {
        $pageTitle = 'Payment Gateway Settings';
        $methods   = $this->db->select("SELECT * FROM payment_methods ORDER BY sort_order") ?: [];
        $settings  = [];
        foreach ($methods as $m) {
            $code = $m['code'];
            $s = $this->db->selectOne(
                "SELECT * FROM payment_gateway_settings WHERE method_code='"
                . $this->db->connect()->real_escape_string($code) . "'"
            ) ?: ['method_code'=>$code,'api_key'=>'','api_secret'=>'','webhook_url'=>'','is_sandbox'=>1,'is_active'=>0,'extra_config'=>''];
            $settings[$code] = $s;
        }
        $this->view('payment_settings/index', compact('pageTitle','methods','settings'));
    }

    public function save(array $p = []): void {
        CSRF::check();
        $code = $this->db->connect()->real_escape_string(trim($_POST['method_code'] ?? ''));
        if (!$code) { $this->json(['success'=>false,'message'=>'Invalid method.']); return; }

        $fields = [
            'method_code'  => $code,
            'display_name' => Helper::sanitize($_POST['display_name'] ?? $code),
            'api_key'      => trim($_POST['api_key'] ?? ''),
            'api_secret'   => trim($_POST['api_secret'] ?? ''),
            'webhook_url'  => trim($_POST['webhook_url'] ?? ''),
            'extra_config' => trim($_POST['extra_config'] ?? '') ?: null,
            'is_sandbox'   => (int)!empty($_POST['is_sandbox']),
            'is_active'    => (int)!empty($_POST['is_active']),
        ];

        $existing = $this->db->selectOne("SELECT id FROM payment_gateway_settings WHERE method_code='$code'");
        if ($existing) {
            $this->db->update('payment_gateway_settings', $fields, "method_code='$code'");
        } else {
            $this->db->insert('payment_gateway_settings', $fields);
        }
        $this->json(['success'=>true,'message'=>'Gateway settings saved.']);
    }

    public function toggle(array $p = []): void {
        CSRF::check();
        $code = $this->db->connect()->real_escape_string(trim($_POST['method_code'] ?? ''));
        if (!$code) { $this->json(['success'=>false]); return; }
        $this->db->query(
            "INSERT INTO payment_gateway_settings (method_code,is_active) VALUES ('$code',1)
             ON DUPLICATE KEY UPDATE is_active = 1 - is_active"
        );
        $row = $this->db->selectOne("SELECT is_active FROM payment_gateway_settings WHERE method_code='$code'");
        $this->json(['success'=>true,'active'=>(int)($row['is_active'] ?? 0)]);
    }
}
