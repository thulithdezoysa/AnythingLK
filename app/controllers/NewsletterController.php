<?php
require_once CORE . '/Controller.php';

class NewsletterController extends Controller {

    public function subscribe(array $p = []): void {
        CSRF::check();
        $email = Helper::sanitize($_POST['email'] ?? '');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->json(['success' => false, 'message' => 'Please enter a valid email address.']);
        }
        $emailEsc = $this->db->connect()->real_escape_string($email);
        $existing = $this->db->selectOne("SELECT id, status FROM newsletter_subscribers WHERE email='$emailEsc'");
        if ($existing) {
            if ($existing['status'] === 'active') {
                $this->json(['success' => false, 'message' => 'You are already subscribed!']);
            }
            $this->db->update('newsletter_subscribers', ['status' => 'active', 'subscribed_at' => date('Y-m-d H:i:s')], "email='$emailEsc'");
        } else {
            $this->db->insert('newsletter_subscribers', [
                'email'         => $email,
                'status'        => 'active',
                'subscribed_at' => date('Y-m-d H:i:s'),
                'ip_address'    => $_SERVER['REMOTE_ADDR'] ?? '',
            ]);
        }
        $this->json(['success' => true, 'message' => "Subscribed! Expect great deals. 🎉"]);
    }
}
