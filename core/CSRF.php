<?php
// ============================================================
// CSRF PROTECTION
// ============================================================
class CSRF {

    private static string $key = '_csrf_token';

    public static function token(): string {
        if (empty($_SESSION[self::$key])) {
            $_SESSION[self::$key] = bin2hex(random_bytes(32));
        }
        return $_SESSION[self::$key];
    }

    public static function verify(string $token): bool {
        return hash_equals(self::token(), $token);
    }

    public static function field(): string {
        return '<input type="hidden" name="_csrf" value="' . self::token() . '">';
    }

    public static function check(): void {
        $token = $_POST['_csrf'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        if (!self::verify($token)) {
            http_response_code(403);
            die(json_encode(['success' => false, 'message' => 'CSRF token mismatch.']));
        }
    }
}
