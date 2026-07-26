<?php
class RateLimit {

    private static function dir(): string {
        $dir = ROOT . '/storage/rate_limits';
        if (!is_dir($dir)) mkdir($dir, 0755, true);
        return $dir;
    }

    private static function ip(): string {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        // Take first IP if comma-separated
        return trim(explode(',', $ip)[0]);
    }

    /**
     * Check whether this action is within the rate limit.
     * Returns true (allowed) or false (rate limited).
     *
     * @param string $action       Identifier, e.g. 'login', 'register'
     * @param int    $maxAttempts  Max requests allowed in the window
     * @param int    $windowSeconds  Window size in seconds
     */
    public static function check(string $action, int $maxAttempts = 5, int $windowSeconds = 300): bool {
        $key  = preg_replace('/[^a-z0-9_]/i', '_', $action) . '_' . md5(self::ip());
        $file = self::dir() . '/' . $key . '.json';

        $fp = @fopen($file, 'c+');
        if (!$fp) return true; // Fail open if filesystem is unavailable

        flock($fp, LOCK_EX);

        $raw  = stream_get_contents($fp);
        $data = $raw ? (json_decode($raw, true) ?? []) : [];

        $now    = time();
        $cutoff = $now - $windowSeconds;

        // Remove expired entries
        $data = array_values(array_filter($data, fn($t) => $t > $cutoff));

        $allowed = count($data) < $maxAttempts;

        // Always record the attempt (even if blocked, to keep the window accurate)
        $data[] = $now;

        ftruncate($fp, 0);
        rewind($fp);
        fwrite($fp, json_encode($data));
        flock($fp, LOCK_UN);
        fclose($fp);

        return $allowed;
    }

    /**
     * Clear rate limit tracking for this action + IP (e.g. after successful login).
     */
    public static function reset(string $action): void {
        $key  = preg_replace('/[^a-z0-9_]/i', '_', $action) . '_' . md5(self::ip());
        $file = self::dir() . '/' . $key . '.json';
        if (file_exists($file)) @unlink($file);
    }
}
