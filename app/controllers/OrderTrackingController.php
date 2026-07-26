<?php
require_once CORE . '/Controller.php';

class OrderTrackingController extends Controller
{
    private const OTP_TTL       = 600;   // OTP valid for 10 minutes
    private const RESEND_CD     = 60;    // seconds before resend is allowed
    private const MAX_ATTEMPTS  = 5;     // wrong OTPs before lockout
    private const LOCKOUT_TTL   = 900;   // 15-minute lockout after too many attempts
    private const VERIFIED_TTL  = 1800;  // verified session lasts 30 minutes

    // ── GET /order-tracking ──────────────────────────────────────────
    public function index(array $params = []): void
    {
        $step    = 'form';
        $order   = null;
        $error   = $_SESSION['ot_flash_error']   ?? '';
        $success = $_SESSION['ot_flash_success'] ?? '';
        unset($_SESSION['ot_flash_error'], $_SESSION['ot_flash_success']);

        if (!empty($_SESSION['ot_verified']) && !empty($_SESSION['ot_verified_at'])) {
            if ((time() - (int)$_SESSION['ot_verified_at']) < self::VERIFIED_TTL) {
                $step  = 'details';
                $order = $this->fetchOrder($_SESSION['ot_verified']);
                if (!$order) {
                    $this->clearSession();
                    $error = 'Order data could not be loaded. Please verify again.';
                    $step  = 'form';
                }
            } else {
                $this->clearSession();
                $error = 'Your verification session expired. Please verify again.';
            }
        } elseif (($_SESSION['ot_step'] ?? '') === 'verify') {
            if (!empty($_SESSION['ot_otp_expires']) && time() > (int)$_SESSION['ot_otp_expires']) {
                $this->clearOtp();
                $_SESSION['ot_step'] = 'verify'; // keep at otp screen so user can resend
                $error = 'Your OTP has expired. Please request a new one.';
            }
            $step = 'verify';
        }

        $maskedEmail = !empty($_SESSION['ot_raw_email']) ? $this->maskEmail($_SESSION['ot_raw_email']) : '';
        $orderNum    = $_SESSION['ot_order_num']   ?? '';
        $resendAt    = (int)($_SESSION['ot_otp_sent_at']  ?? 0);
        $resendIn    = max(0, self::RESEND_CD - (time() - $resendAt));
        $attempts    = (int)($_SESSION['ot_attempts']     ?? 0);
        $lockedUntil = (int)($_SESSION['ot_locked_until'] ?? 0);
        $otpExpires  = (int)($_SESSION['ot_otp_expires']  ?? 0);

        $this->view('home/order_tracking', compact(
            'step', 'order', 'error', 'success',
            'maskedEmail', 'orderNum', 'resendIn',
            'attempts', 'lockedUntil', 'otpExpires'
        ));
    }

    // ── POST /order-tracking/send-otp ────────────────────────────────
    public function sendOtp(array $params = []): void
    {
        CSRF::check();

        // IP-level rate limit: 3 OTP requests per 10 minutes
        if (!RateLimit::check('track_otp_send', 3, 600)) {
            $_SESSION['ot_flash_error'] = 'Too many OTP requests. Please wait 10 minutes and try again.';
            $this->redirect('order-tracking');
        }

        $orderNum = strtoupper(trim(Helper::sanitize($_POST['order_number'] ?? '')));
        $email    = strtolower(trim($_POST['email'] ?? ''));

        if (!$orderNum || !$email) {
            $_SESSION['ot_flash_error'] = 'Please enter both your order number and email address.';
            $this->redirect('order-tracking');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['ot_flash_error'] = 'Please enter a valid email address.';
            $this->redirect('order-tracking');
        }

        // Look up order and resolve its email (guest or registered user)
        $on    = $this->db->escape($orderNum);
        $order = $this->db->selectOne(
            "SELECT o.*, u.email AS user_email, u.full_name AS user_name
             FROM orders o
             LEFT JOIN users u ON u.id = o.user_id
             WHERE o.order_number = $on"
        );

        if (!$order) {
            $_SESSION['ot_flash_error'] = 'No order found with that order number. Please check and try again.';
            $this->redirect('order-tracking');
        }

        $orderEmail = strtolower(trim($order['guest_email'] ?: ($order['user_email'] ?? '')));

        if (!$orderEmail) {
            $_SESSION['ot_flash_error'] = 'Unable to verify this order — no email on record. Please contact support.';
            $this->redirect('order-tracking');
        }

        // Constant-time email comparison to prevent timing attacks
        if (!hash_equals($orderEmail, $email)) {
            $_SESSION['ot_flash_error'] = 'The email address does not match our records for this order.';
            $this->redirect('order-tracking');
        }

        // Generate cryptographically secure 6-digit OTP
        $otp = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $_SESSION['ot_step']        = 'verify';
        $_SESSION['ot_order_num']   = $orderNum;
        $_SESSION['ot_raw_email']   = $email;
        $_SESSION['ot_otp']         = $otp;
        $_SESSION['ot_otp_expires'] = time() + self::OTP_TTL;
        $_SESSION['ot_otp_sent_at'] = time();
        $_SESSION['ot_attempts']    = 0;
        unset($_SESSION['ot_locked_until']);

        $recipientName = trim($order['shipping_name'] ?: ($order['user_name'] ?? 'Customer'));

        $sent = false;
        ob_start();
        try {
            $sent = MailService::sendTrackingOtp($email, $recipientName, $orderNum, $otp);
        } catch (\Throwable $e) {
            error_log('Tracking OTP mail error: ' . $e->getMessage());
        }
        ob_end_clean();

        if (!$sent) {
            $_SESSION['ot_flash_error'] = 'We could not send the verification email. Please try again or contact support.';
            $this->clearOtp();
            $this->redirect('order-tracking');
        }

        $_SESSION['ot_flash_success'] = 'A 6-digit verification code has been sent to ' . $this->maskEmail($email) . '. Check your inbox (and spam folder).';
        $this->redirect('order-tracking');
    }

    // ── POST /order-tracking/verify-otp ─────────────────────────────
    public function verifyOtp(array $params = []): void
    {
        CSRF::check();

        if (($_SESSION['ot_step'] ?? '') !== 'verify') {
            $this->redirect('order-tracking');
        }

        // Lockout check
        $lockedUntil = (int)($_SESSION['ot_locked_until'] ?? 0);
        if ($lockedUntil > 0 && time() < $lockedUntil) {
            $mins = (int)ceil(($lockedUntil - time()) / 60);
            $_SESSION['ot_flash_error'] = "Too many failed attempts. Please try again in {$mins} minute(s).";
            $this->redirect('order-tracking');
        }

        // OTP expiry check
        $expires = (int)($_SESSION['ot_otp_expires'] ?? 0);
        if (!$expires || time() > $expires) {
            $this->clearOtp();
            $_SESSION['ot_flash_error'] = 'Your OTP has expired. Please request a new one using the Resend button.';
            $this->redirect('order-tracking');
        }

        $entered = preg_replace('/\D/', '', trim($_POST['otp'] ?? ''));
        $stored  = $_SESSION['ot_otp'] ?? '';

        $_SESSION['ot_attempts'] = (int)($_SESSION['ot_attempts'] ?? 0) + 1;

        if (!$entered || !hash_equals($stored, $entered)) {
            if ((int)$_SESSION['ot_attempts'] >= self::MAX_ATTEMPTS) {
                $_SESSION['ot_locked_until'] = time() + self::LOCKOUT_TTL;
                $this->clearOtp();
                $_SESSION['ot_flash_error'] = 'Too many failed attempts. Please wait 15 minutes before trying again.';
            } else {
                $remaining = self::MAX_ATTEMPTS - (int)$_SESSION['ot_attempts'];
                $_SESSION['ot_flash_error'] = "Invalid verification code. You have {$remaining} attempt(s) remaining.";
            }
            $this->redirect('order-tracking');
        }

        // — Verified —
        $orderNum = $_SESSION['ot_order_num'];
        session_regenerate_id(true); // new session ID prevents session fixation

        $_SESSION['ot_verified']    = $orderNum;
        $_SESSION['ot_verified_at'] = time();
        // Clean up OTP data — no longer needed
        foreach (['ot_step','ot_otp','ot_otp_expires','ot_otp_sent_at','ot_attempts','ot_locked_until'] as $k) {
            unset($_SESSION[$k]);
        }

        $_SESSION['ot_flash_success'] = 'Identity verified successfully.';
        $this->redirect('order-tracking');
    }

    // ── POST /order-tracking/resend-otp ─────────────────────────────
    public function resendOtp(array $params = []): void
    {
        CSRF::check();

        if (($_SESSION['ot_step'] ?? '') !== 'verify') {
            $this->redirect('order-tracking');
        }

        // Resend cooldown
        $sentAt = (int)($_SESSION['ot_otp_sent_at'] ?? 0);
        $wait   = self::RESEND_CD - (time() - $sentAt);
        if ($wait > 0) {
            $_SESSION['ot_flash_error'] = "Please wait {$wait} second(s) before requesting another code.";
            $this->redirect('order-tracking');
        }

        // IP-level rate limit for resends
        if (!RateLimit::check('track_otp_resend', 5, 600)) {
            $_SESSION['ot_flash_error'] = 'Too many resend requests. Please wait a while.';
            $this->redirect('order-tracking');
        }

        $email    = $_SESSION['ot_raw_email']  ?? '';
        $orderNum = $_SESSION['ot_order_num']  ?? '';
        if (!$email || !$orderNum) {
            $this->clearSession();
            $this->redirect('order-tracking');
        }

        $on    = $this->db->escape($orderNum);
        $order = $this->db->selectOne("SELECT shipping_name FROM orders WHERE order_number = $on");
        $name  = trim($order['shipping_name'] ?? 'Customer');

        $otp = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $_SESSION['ot_otp']         = $otp;
        $_SESSION['ot_otp_expires'] = time() + self::OTP_TTL;
        $_SESSION['ot_otp_sent_at'] = time();
        $_SESSION['ot_attempts']    = 0;
        unset($_SESSION['ot_locked_until']);

        $sent = false;
        ob_start();
        try {
            $sent = MailService::sendTrackingOtp($email, $name, $orderNum, $otp);
        } catch (\Throwable $e) {
            error_log('Tracking OTP resend error: ' . $e->getMessage());
        }
        ob_end_clean();

        if ($sent) {
            $_SESSION['ot_flash_success'] = 'A new verification code has been sent to ' . $this->maskEmail($email) . '.';
        } else {
            $_SESSION['ot_flash_error'] = 'Failed to resend the code. Please try again.';
        }

        $this->redirect('order-tracking');
    }

    // ── GET /order-tracking/reset ────────────────────────────────────
    public function reset(array $params = []): void
    {
        $this->clearSession();
        $this->redirect('order-tracking');
    }

    // ── Helpers ──────────────────────────────────────────────────────
    private function fetchOrder(string $orderNum): ?array
    {
        $on    = $this->db->escape($orderNum);
        $order = $this->db->selectOne(
            "SELECT o.*, COUNT(oi.id) AS item_count
             FROM orders o
             LEFT JOIN order_items oi ON oi.order_id = o.id
             WHERE o.order_number = $on
             GROUP BY o.id"
        );
        if (!$order) return null;
        $order['items']   = $this->db->select("SELECT * FROM order_items WHERE order_id = {$order['id']}") ?: [];
        $order['history'] = $this->db->select("SELECT * FROM order_status_history WHERE order_id = {$order['id']} ORDER BY created_at ASC") ?: [];
        return $order;
    }

    private function maskEmail(string $email): string
    {
        if (!str_contains($email, '@')) return $email;
        [$local, $domain] = explode('@', $email, 2);
        $domainParts = explode('.', $domain, 2);
        $maskedLocal  = substr($local, 0, 1) . str_repeat('*', max(2, strlen($local) - 1));
        $maskedDomain = substr($domainParts[0], 0, 1) . str_repeat('*', max(2, strlen($domainParts[0]) - 1));
        $tld = isset($domainParts[1]) ? '.' . $domainParts[1] : '';
        return $maskedLocal . '@' . $maskedDomain . $tld;
    }

    private function clearOtp(): void
    {
        unset($_SESSION['ot_otp'], $_SESSION['ot_otp_expires']);
    }

    private function clearSession(): void
    {
        foreach ([
            'ot_step','ot_order_num','ot_raw_email',
            'ot_otp','ot_otp_expires','ot_otp_sent_at',
            'ot_attempts','ot_locked_until',
            'ot_verified','ot_verified_at',
            'ot_flash_error','ot_flash_success',
        ] as $k) {
            unset($_SESSION[$k]);
        }
    }
}
