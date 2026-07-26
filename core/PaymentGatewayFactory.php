<?php
/**
 * PaymentGatewayFactory
 * Maps payment method codes → gateway drivers and loads API settings.
 */
class PaymentGatewayFactory {

    // code → driver name mapping
    private static array $map = [
        'cod'        => 'internal',
        'bank'       => 'manual',
        'card'       => 'payhere',
        'visa'       => 'payhere',
        'mastercard' => 'payhere',
        'payhere'    => 'payhere',
        'payzy'      => 'payzy',
        'koko'       => 'koko',
        'mintpay'    => 'mintpay',
    ];

    public static function driver(string $code): string {
        return self::$map[strtolower($code)] ?? 'unknown';
    }

    public static function settings(string $code): array {
        $db  = new Db();
        $esc = $db->connect()->real_escape_string(strtolower($code));
        $row = $db->selectOne("SELECT * FROM payment_gateway_settings WHERE method_code='$esc'") ?: [];
        if (!empty($row['extra_config'])) {
            $row['extra_config'] = json_decode($row['extra_config'], true) ?? [];
        }
        return $row;
    }

    public static function isSandbox(string $code): bool {
        return (bool)(self::settings($code)['is_sandbox'] ?? true);
    }

    public static function isConfigured(string $code): bool {
        $s = self::settings($code);
        $driver = self::driver($code);
        if (in_array($driver, ['internal', 'manual'])) return true;
        return !empty($s['api_key']);
    }

    /**
     * Process a payment. Returns ['success', 'redirect'|'message', 'reference'].
     * Extend each case with real SDK calls.
     */
    public static function process(string $code, array $order, float $amount): array {
        $driver   = self::driver($code);
        $settings = self::settings($code);

        switch ($driver) {
            case 'internal':
                return ['success' => true, 'message' => 'COD order confirmed.', 'reference' => 'COD-' . $order['id']];

            case 'manual':
                return ['success' => true, 'message' => 'Bank transfer details sent.', 'reference' => 'BANK-' . $order['id']];

            case 'payhere':
                // TODO: integrate PayHere SDK
                // $payhere = new PayHereGateway($settings['api_key'], $settings['api_secret'], $settings['is_sandbox']);
                // return $payhere->initiate($order, $amount);
                return ['success' => true, 'message' => 'Redirecting to PayHere…', 'redirect' => '#payhere'];

            case 'koko':
                // TODO: integrate KOKO API
                return ['success' => true, 'message' => 'Redirecting to KOKO…', 'redirect' => '#koko'];

            case 'mintpay':
                return ['success' => true, 'message' => 'Redirecting to MintPay…', 'redirect' => '#mintpay'];

            case 'payzy':
                return ['success' => true, 'message' => 'Redirecting to Payzy…', 'redirect' => '#payzy'];

            default:
                return ['success' => false, 'message' => 'Unknown payment driver: ' . $driver];
        }
    }
}
