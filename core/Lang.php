<?php
// ============================================================
// MULTI-LANGUAGE SYSTEM
// Supports: en (English), si (Sinhala), ta (Tamil)
// ============================================================
class Lang {

    private static array  $translations = [];
    private static string $current      = 'en';
    private static array  $supported    = ['en', 'si', 'ta'];

    public static function init(): void {
        // Detect language: URL param > session > cookie > default
        if (isset($_GET['lang']) && in_array($_GET['lang'], self::$supported)) {
            self::$current = $_GET['lang'];
            $_SESSION['lang'] = self::$current;
            setcookie('lang', self::$current, time() + (86400 * 30), '/');
        } elseif (isset($_SESSION['lang']) && in_array($_SESSION['lang'], self::$supported)) {
            self::$current = $_SESSION['lang'];
        } elseif (isset($_COOKIE['lang']) && in_array($_COOKIE['lang'], self::$supported)) {
            self::$current = $_COOKIE['lang'];
        }

        // Load language file
        $file = ROOT . '/lang/' . self::$current . '.php';
        if (file_exists($file)) {
            self::$translations = require $file;
        }
    }

    public static function get(string $key, array $replace = []): string {
        $value = self::$translations[$key] ?? $key;

        foreach ($replace as $k => $v) {
            $value = str_replace(':' . $k, $v, $value);
        }

        return $value;
    }

    public static function all(): array {
        return self::$translations;
    }

    public static function current(): string {
        return self::$current;
    }

    public static function supported(): array {
        return self::$supported;
    }
}

// Global shorthand function
function __(string $key, array $replace = []): string {
    return Lang::get($key, $replace);
}
