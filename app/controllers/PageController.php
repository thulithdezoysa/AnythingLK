<?php
require_once CORE . '/Controller.php';

class PageController extends Controller {
    public function about(array $p=[]): void {
        $productCount  = (int)($this->db->selectOne("SELECT COUNT(*) AS c FROM products WHERE status='active'")['c'] ?? 0);
        $customerCount = (int)($this->db->selectOne("SELECT COUNT(*) AS c FROM users WHERE role='customer'")['c'] ?? 0);
        $vendorCount   = (int)($this->db->selectOne("SELECT COUNT(*) AS c FROM vendors WHERE status='active'")['c'] ?? 0);
        $this->view('home/about', compact('productCount','customerCount','vendorCount'));
    }
    public function contact(array $p=[]): void { $this->view('home/contact'); }

    public function sendContact(array $p=[]): void {
        CSRF::check();

        if (!RateLimit::check('contact', 5, 300)) {
            $this->json(['success'=>false,'message'=>'Too many messages sent. Please wait a few minutes.']);
        }

        $name    = Helper::sanitize($_POST['name']    ?? '');
        $email   = Helper::sanitize($_POST['email']   ?? '');
        $subject = Helper::sanitize($_POST['subject'] ?? '');
        $message = Helper::sanitize($_POST['message'] ?? '');
        if (!$name || !$email || !$message) {
            $this->json(['success'=>false,'message'=>'Please fill in all required fields.']);
        }
        $this->db->insert('contact_messages', [
            'name'=>$name,'email'=>$email,'subject'=>$subject,'message'=>$message,
        ]);
        ob_start();
        try { MailService::sendContactAck($email, $name, $subject); } catch (\Throwable $e) {}
        ob_end_clean();
        $this->json(['success'=>true,'message'=>"Message sent! We'll reply within 24 hours."]);
    }

    // Public pages
    public function orderTracking(array $p=[]): void {
        $order = null;
        $error = '';
        if (!empty($_GET['order'])) {
            $on = $this->db->escape(Helper::sanitize($_GET['order']));
            $order = $this->db->selectOne(
                "SELECT o.*, COUNT(oi.id) AS item_count
                 FROM orders o
                 LEFT JOIN order_items oi ON oi.order_id = o.id
                 WHERE o.order_number=$on
                 GROUP BY o.id"
            );
            if (!$order) $error = 'No order found with that order number. Please check and try again.';
            else {
                $order['items']   = $this->db->select("SELECT * FROM order_items WHERE order_id={$order['id']}") ?: [];
                $order['history'] = $this->db->select("SELECT * FROM order_status_history WHERE order_id={$order['id']} ORDER BY created_at") ?: [];
            }
        }
        $this->view('home/order_tracking', compact('order','error'));
    }

    public function shippingPolicy(array $p=[]): void {
        $shippingMethods = $this->db->select(
            "SELECT name, description, price, min_days, max_days, is_free, free_above
             FROM shipping_methods WHERE status=1 ORDER BY price ASC"
        ) ?: [];
        $this->view('home/shipping_policy', compact('shippingMethods'));
    }
    public function returnPolicy(array $p=[]): void   { $this->view('home/return_policy');   }
    public function faq(array $p=[]): void            { $this->view('home/faq');             }
}

