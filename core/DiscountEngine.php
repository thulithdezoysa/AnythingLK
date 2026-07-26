<?php
// ============================================================
// DISCOUNT ENGINE
// Priority: coupon > global > product (item-level) > bogo
// KOKO / Payzy / MintPay are excluded from ALL discounts
// ============================================================
class DiscountEngine
{
    /** Payment methods that cannot receive any discount */
    public const EXCLUDED_METHODS = ['koko', 'payzy', 'mintpay'];

    // ── Public API ────────────────────────────────────────────

    /**
     * Returns true if the payment method is excluded from all discounts.
     */
    public static function isExcluded(string $method): bool
    {
        return in_array(strtolower(trim($method)), self::EXCLUDED_METHODS, true);
    }

    /**
     * Full discount calculation for cart/checkout.
     * Returns ['amount'=>float, 'label'=>string, 'type'=>string, 'coupon_code'=>string|null]
     */
    public static function calculate(
        float   $subtotal,
        array   $items,
        ?string $method = null,
        ?string $couponCode = null
    ): array {
        // Excluded payment methods → zero discount
        if ($method && self::isExcluded($method)) {
            return ['amount'=>0,'label'=>'','type'=>'none','coupon_code'=>null];
        }

        // 1. Coupon (highest priority)
        if ($couponCode) {
            $r = self::evalCoupon($couponCode, $subtotal, $method);
            if ($r['success']) {
                return ['amount'=>$r['discount'],'label'=>'Coupon '.$couponCode,'type'=>'coupon','coupon_code'=>$couponCode,'coupon_id'=>$r['id']];
            }
        }

        // 2. Global discount (best active one by priority)
        $global = self::evalGlobal($subtotal, $method);
        if ($global['amount'] > 0) {
            return ['amount'=>$global['amount'],'label'=>$global['name'],'type'=>'global','coupon_code'=>null];
        }

        return ['amount'=>0,'label'=>'','type'=>'none','coupon_code'=>null];
    }

    /**
     * BOGO discount: extra savings applied on top of order-level discount.
     * Returns additional savings amount.
     */
    public static function bogoDiscount(array $items, ?string $method = null): float
    {
        if ($method && self::isExcluded($method)) return 0;
        $db      = new Db();
        $now     = date('Y-m-d H:i:s');
        $offers  = $db->select(
            "SELECT * FROM bogo_offers WHERE status=1
             AND (start_date IS NULL OR start_date <= '$now')
             AND (end_date   IS NULL OR end_date   >= '$now')"
        ) ?: [];
        if (empty($offers)) return 0;

        $savings = 0;
        foreach ($offers as $offer) {
            if (self::methodExcluded($method, $offer['excluded_methods'])) continue;
            $savings += self::applyBogo($offer, $items);
        }
        return $savings;
    }

    /**
     * Product-level discount for a single product_id.
     * Returns discount amount for the item total.
     */
    public static function productDiscount(int $productId, float $itemTotal, ?string $method = null): float
    {
        if ($method && self::isExcluded($method)) return 0;
        $db  = new Db();
        $now = date('Y-m-d H:i:s');
        $d   = $db->selectOne(
            "SELECT * FROM product_discounts
             WHERE product_id=$productId AND status=1
               AND (start_date IS NULL OR start_date <= '$now')
               AND (end_date   IS NULL OR end_date   >= '$now')
             ORDER BY id DESC LIMIT 1"
        );
        if (!$d) return 0;
        if (self::methodExcluded($method, $d['excluded_methods'])) return 0;
        $amt = $d['type'] === 'percentage'
            ? $itemTotal * ($d['value'] / 100)
            : (float)$d['value'];
        return min($amt, $itemTotal);
    }

    /**
     * Validate and apply a coupon code. Used by cart/AJAX.
     * Returns ['success'=>bool, 'message'=>string, 'discount'=>float, 'id'=>int]
     */
    public static function applyCoupon(string $code, float $subtotal, ?string $method = null): array
    {
        if ($method && self::isExcluded($method)) {
            return ['success'=>false,'message'=>'Discounts are not available with '.ucfirst($method).'.'];
        }
        $r = self::evalCoupon($code, $subtotal, $method);
        if (!$r['success']) return ['success'=>false,'message'=>$r['message'],'discount'=>0];
        return [
            'success'  => true,
            'message'  => 'Coupon applied! You saved '.price($r['discount']),
            'discount' => $r['discount'],
            'code'     => strtoupper($code),
            'id'       => $r['id'],
        ];
    }

    // ── Private helpers ───────────────────────────────────────

    private static function evalCoupon(string $code, float $subtotal, ?string $method): array
    {
        $db   = new Db();
        $esc  = $db->connect()->real_escape_string(strtoupper(trim($code)));
        $now  = date('Y-m-d');
        $c    = $db->selectOne(
            "SELECT * FROM coupons WHERE code='$esc' AND status=1
             AND (start_date IS NULL OR start_date <= '$now')
             AND (end_date   IS NULL OR end_date   >= '$now')
             AND (usage_limit IS NULL OR usage_count < usage_limit)"
        );
        if (!$c) return ['success'=>false,'message'=>'Invalid or expired coupon.'];
        if (self::methodExcluded($method, $c['excluded_methods']))
            return ['success'=>false,'message'=>'This coupon cannot be used with the selected payment method.'];
        if ($subtotal < (float)$c['min_order'])
            return ['success'=>false,'message'=>'Minimum order of '.price($c['min_order']).' required.'];

        $discount = $c['type'] === 'percentage'
            ? $subtotal * ((float)$c['value'] / 100)
            : (float)$c['value'];
        if ($c['max_discount'] && $discount > (float)$c['max_discount'])
            $discount = (float)$c['max_discount'];

        return ['success'=>true,'discount'=>round($discount,2),'id'=>(int)$c['id'],'message'=>''];
    }

    private static function evalGlobal(float $subtotal, ?string $method): array
    {
        $db  = new Db();
        $now = date('Y-m-d H:i:s');
        $rows = $db->select(
            "SELECT * FROM global_discounts WHERE status=1
             AND (start_date IS NULL OR start_date <= '$now')
             AND (end_date   IS NULL OR end_date   >= '$now')
             AND min_order <= $subtotal
             ORDER BY priority DESC, value DESC"
        ) ?: [];
        foreach ($rows as $g) {
            if (self::methodExcluded($method, $g['excluded_methods'])) continue;
            $amt = $g['type'] === 'percentage'
                ? $subtotal * ((float)$g['value'] / 100)
                : (float)$g['value'];
            if ($g['max_discount'] && $amt > (float)$g['max_discount']) $amt = (float)$g['max_discount'];
            if ($amt > 0) return ['amount'=>round($amt,2),'name'=>$g['name']];
        }
        return ['amount'=>0,'name'=>''];
    }

    private static function applyBogo(array $offer, array $items): float
    {
        $buyPid  = $offer['buy_product_id'] ? (int)$offer['buy_product_id'] : null;
        $getPid  = $offer['get_product_id'] ? (int)$offer['get_product_id'] : null;
        $buyQty  = (int)$offer['buy_qty'];
        $getQty  = (int)$offer['get_qty'];
        $discPct = (float)$offer['get_discount_pct'];

        // Find qualifying buy-items
        $buyCount = 0;
        foreach ($items as $item) {
            if ($buyPid === null || (int)$item['product_id'] === $buyPid) {
                $buyCount += (int)$item['quantity'];
            }
        }
        $sets = (int)floor($buyCount / $buyQty);
        if ($sets < 1) return 0;

        // Find get-items and apply discount
        $savings   = 0;
        $freeNeeded = $sets * $getQty;
        foreach ($items as $item) {
            $targetPid = $getPid ?? $buyPid;
            if ($targetPid !== null && (int)$item['product_id'] !== $targetPid) continue;
            $applyQty = min($freeNeeded, (int)$item['quantity']);
            $savings += ($applyQty * (float)$item['effective_price']) * ($discPct / 100);
            $freeNeeded -= $applyQty;
            if ($freeNeeded <= 0) break;
        }
        return round($savings, 2);
    }

    private static function methodExcluded(?string $method, string $excludedList): bool
    {
        if (!$method) return false;
        $excluded = array_map('trim', explode(',', strtolower($excludedList)));
        return in_array(strtolower($method), $excluded, true);
    }
}
