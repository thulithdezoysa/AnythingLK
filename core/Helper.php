<?php
class Helper {

    public static function baseUrl(): string {
        static $base = null;
        if ($base !== null) return $base;

        $configFile = ROOT . '/config.ini';
        if (file_exists($configFile)) {
            $config = parse_ini_file($configFile);
            // If app_url is explicitly set and not empty, use it
            if (!empty($config['app_url'])) {
                $base = rtrim($config['app_url'], '/');
                return $base;
            }
        }

        // Auto-detect: scheme + host only (no path for root deployment)
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host   = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $base   = $scheme . '://' . $host;
        return $base;
    }

    public static function asset(string $path): string {
        return self::baseUrl() . '/assets/' . ltrim($path, '/');
    }

    public static function url(string $path = ''): string {
        $path = ltrim($path, '/');
        return $path ? self::baseUrl() . '/' . $path : self::baseUrl();
    }

    public static function slug(string $text): string {
        $text = mb_strtolower(trim($text));
        $text = preg_replace('/[\s]+/', '-', $text);
        $text = preg_replace('/[^a-z0-9\-]/', '', $text);
        $text = preg_replace('/-+/', '-', $text);
        return trim($text, '-');
    }

    public static function price(float $amount, string $currency = 'LKR'): string {
        return $currency . ' ' . number_format($amount, 2);
    }

    public static function sanitize(string $input): string {
        return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
    }

    public static function validEmail(string $email): bool {
        return (bool)filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public static function hashPassword(string $password): string {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }

    public static function verifyPassword(string $password, string $hash): bool {
        return password_verify($password, $hash);
    }

    public static function toWebP(string $sourcePath, string $destPath, int $quality = 85): bool {
        if (!file_exists($sourcePath)) return false;
        $info = @getimagesize($sourcePath);
        if (!$info) return false;
        switch ($info['mime']) {
            case 'image/jpeg': $img = @imagecreatefromjpeg($sourcePath); break;
            case 'image/png':
                $img = @imagecreatefrompng($sourcePath);
                if ($img) { imagealphablending($img, false); imagesavealpha($img, true); }
                break;
            case 'image/gif':  $img = @imagecreatefromgif($sourcePath); break;
            case 'image/webp': return copy($sourcePath, $destPath);
            default: return false;
        }
        if (!$img) return false;
        $r = imagewebp($img, $destPath, $quality);
        imagedestroy($img);
        // GD may return true but write 0 bytes if webp support is broken
        if ($r && (!file_exists($destPath) || filesize($destPath) === 0)) {
            @unlink($destPath);
            return false;
        }
        return $r;
    }

    public static function uploadImage(array $file, string $dir): ?string {
        if (empty($file['tmp_name']) || ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) return null;
        $allowed = ['image/jpeg','image/png','image/gif','image/webp'];
        $mime    = mime_content_type($file['tmp_name']);
        if (!in_array($mime, $allowed)) return null;
        $uploadDir = ROOT . '/uploads/' . trim($dir, '/') . '/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
        $filename = uniqid('img_', true) . '.webp';
        $dest     = $uploadDir . $filename;
        if (self::toWebP($file['tmp_name'], $dest)) return $filename;
        // Fallback: move as-is
        $ext = strtolower(pathinfo($file['name'] ?? '', PATHINFO_EXTENSION));
        $filename = uniqid('img_', true) . '.' . $ext;
        if (move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) return $filename;
        return null;
    }

    public static function paginate(int $total, int $perPage, int $currentPage): array {
        $totalPages = (int)ceil($total / max(1, $perPage));
        return [
            'total'        => $total,
            'per_page'     => $perPage,
            'current_page' => $currentPage,
            'total_pages'  => $totalPages,
            'offset'       => max(0, ($currentPage - 1) * $perPage),
            'has_prev'     => $currentPage > 1,
            'has_next'     => $currentPage < $totalPages,
        ];
    }

    public static function timeAgo(string $datetime): string {
        $time = time() - strtotime($datetime);
        if ($time < 60)     return 'just now';
        if ($time < 3600)   return floor($time/60) . 'm ago';
        if ($time < 86400)  return floor($time/3600) . 'h ago';
        if ($time < 604800) return floor($time/86400) . 'd ago';
        return date('M j, Y', strtotime($datetime));
    }

    public static function orderNumber(): string {
        return 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
    }

    public static function truncate(string $text, int $len = 100): string {
        return mb_strlen($text) <= $len ? $text : mb_substr($text, 0, $len) . '...';
    }

    public static function stars(float $rating, int $max = 5): string {
        $html = '';
        for ($i = 1; $i <= $max; $i++) {
            if ($rating >= $i)           $html .= '<i class="fa fa-star" style="color:#ffb83c;"></i>';
            elseif ($rating >= $i - 0.5) $html .= '<i class="fa fa-star-half-o" style="color:#ffb83c;"></i>';
            else                          $html .= '<i class="fa fa-star-o" style="color:#ddd;"></i>';
        }
        return $html;
    }

    // Get nested categories as flat tree
    public static function getCategoryTree(array $all, ?int $parentId = null, int $depth = 0): array {
        $result = [];
        foreach ($all as $cat) {
            $pid = $cat['parent_id'] ? (int)$cat['parent_id'] : null;
            if ($pid === $parentId) {
                $cat['_depth']    = $depth;
                $cat['_children'] = self::getCategoryTree($all, (int)$cat['id'], $depth + 1);
                $result[] = $cat;
            }
        }
        return $result;
    }

    // Flatten category tree for dropdowns
    public static function flattenCategoryTree(array $tree, string $prefix = ''): array {
        $flat = [];
        foreach ($tree as $cat) {
            $cat['_label'] = $prefix . $cat['name'];
            $flat[] = $cat;
            if (!empty($cat['_children'])) {
                $flat = array_merge($flat, self::flattenCategoryTree($cat['_children'], $prefix . '— '));
            }
        }
        return $flat;
    }

    // All active categories — cached for the lifetime of the request
    public static function categories(): array {
        static $rows = null;
        if ($rows === null) {
            try {
                $db = new Db();
                $rows = $db->select(
                    "SELECT * FROM categories WHERE status=1 ORDER BY depth, parent_id, sort_order"
                ) ?: [];
            } catch (\Throwable $e) {
                $rows = [];
            }
        }
        return $rows;
    }

    // Root-level active categories (no parent)
    public static function rootCategories(): array {
        return array_values(array_filter(self::categories(), fn($c) => !$c['parent_id']));
    }

    // Direct children of a given parent id
    public static function childCategories(int $parentId): array {
        return array_values(array_filter(self::categories(), fn($c) => (int)$c['parent_id'] === $parentId));
    }

    // Get site setting
    public static function setting(string $key, string $default = ''): string {
        static $settings = null;
        if ($settings === null) {
            try {
                $db = new Db();
                $rows = $db->select("SELECT `key`, `value` FROM settings");
                $settings = array_column($rows ?: [], 'value', 'key');
            } catch (\Throwable $e) {
                $settings = [];
            }
        }
        return $settings[$key] ?? $default;
    }
}

function e(string $str): string         { return htmlspecialchars($str, ENT_QUOTES, 'UTF-8'); }
function asset(string $path): string    { return Helper::asset($path); }
function url(string $path = ''): string { return Helper::url($path); }
function price(float $amt): string      { return Helper::price($amt); }
function stars(float $r): string        { return Helper::stars($r); }
function setting(string $k, string $d=''): string { return Helper::setting($k, $d); }
