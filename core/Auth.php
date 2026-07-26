<?php
class Auth {

    private const SESSION_LIFETIME = 7200; // 2 hours of inactivity

    public static function check(): bool {
        if (empty($_SESSION['user_id'])) return false;

        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > self::SESSION_LIFETIME) {
            self::logout();
            return false;
        }

        $_SESSION['last_activity'] = time();
        return true;
    }

    public static function user(): ?array {
        if (!self::check()) return null;
        return [
            'id'     => $_SESSION['user_id'],
            'name'   => $_SESSION['user_name']  ?? '',
            'email'  => $_SESSION['user_email'] ?? '',
            'role'   => $_SESSION['user_role']  ?? 'customer',
            'avatar' => $_SESSION['user_avatar']?? null,
        ];
    }

    public static function id(): ?int {
        return self::check() ? (int)$_SESSION['user_id'] : null;
    }

    public static function role(): string {
        return $_SESSION['user_role'] ?? 'guest';
    }

    public static function login(array $user): void {
        session_regenerate_id(true);
        $_SESSION['user_id']     = $user['id'];
        $_SESSION['user_name']   = $user['full_name'] ?? $user['name'] ?? '';
        $_SESSION['user_email']  = $user['email'];
        $_SESSION['user_role']   = $user['role'] ?? 'customer';
        $_SESSION['user_avatar'] = $user['avatar'] ?? null;
    }

    public static function logout(): void {
        session_unset();
        session_destroy();
    }

    // Redirect URL after login based on role
    public static function redirectAfterLogin(): string {
        $role = $_SESSION['user_role'] ?? 'customer';
        return match($role) {
            'admin', 'supervisor' => url('admin/dashboard'),
            default               => url('account'),
        };
    }

    public static function isAdmin(): bool {
        return self::check() && $_SESSION['user_role'] === 'admin';
    }

    public static function isSupervisor(): bool {
        return self::check() && in_array($_SESSION['user_role'], ['admin', 'supervisor']);
    }

    public static function isCustomer(): bool {
        return self::check() && $_SESSION['user_role'] === 'customer';
    }

    public static function can(string $permission): bool {
        $role = self::role();
        $permissions = [
            'admin' => ['*'],
            'supervisor' => [
                'view_orders','update_order_status','view_products','view_stock',
                'manage_reviews','view_customers','view_reports'
            ],
            'customer' => ['view_account','place_order','write_review'],
        ];
        $allowed = $permissions[$role] ?? [];
        return in_array('*', $allowed) || in_array($permission, $allowed);
    }

    public static function mergeGuestCart(Db $db): int {
        $sessionId = session_id();
        $userId    = self::id();
        if (!$userId || !$sessionId) return 0;

        $sid        = $db->connect()->real_escape_string($sessionId);
        $guestItems = $db->select("SELECT * FROM cart WHERE session_id='$sid' AND user_id IS NULL") ?: [];

        $merged = 0;
        foreach ($guestItems as $item) {
            $pid = $item['product_id'];
            $vid = $item['variation_id'] ? "= {$item['variation_id']}" : 'IS NULL';
            $existing = $db->selectOne("SELECT id, quantity FROM cart WHERE user_id=$userId AND product_id=$pid AND variation_id $vid");
            if ($existing) {
                $db->update('cart', ['quantity' => $existing['quantity'] + $item['quantity']], "id={$existing['id']}");
                $db->delete('cart', "id={$item['id']}");
            } else {
                $db->update('cart', ['user_id' => $userId, 'session_id' => null], "id={$item['id']}");
            }
            $merged++;
        }
        return $merged;
    }
}
