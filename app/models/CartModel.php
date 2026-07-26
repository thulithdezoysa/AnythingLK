<?php
class CartModel extends Db {

    private ?int $userId;
    private string $sessionId;

    public function __construct() {
        $this->userId    = Auth::id();
        $this->sessionId = $this->connect()->real_escape_string(session_id());
    }

    private function condition(): string {
        if ($this->userId) {
            return "user_id = {$this->userId}";
        }
        return "session_id = '{$this->sessionId}' AND user_id IS NULL";
    }

    // ---- GET CART ITEMS ----
    public function getItems(): array {
        $cond = $this->condition();
        $rows = $this->select(
            "SELECT c.id, c.quantity, c.product_id, c.variation_id,
                    p.name, p.slug, p.thumbnail, p.track_stock,
                    COALESCE(pv.price, p.price) AS unit_price,
                    COALESCE(pv.sale_price, p.sale_price) AS sale_unit_price,
                    pv.name AS variation_name,
                    COALESCE(ps.quantity, 0) AS stock_qty
             FROM cart c
             JOIN products p ON p.id = c.product_id
             LEFT JOIN product_variations pv ON pv.id = c.variation_id
             LEFT JOIN product_stock ps ON ps.product_id = c.product_id
                AND ps.variation_id <=> c.variation_id
                AND ps.warehouse = 'Main'
             WHERE $cond AND p.status = 'active'
             ORDER BY c.added_at DESC"
        ) ?: [];

        foreach ($rows as &$row) {
            $row['effective_price'] = (float)($row['sale_unit_price'] ?: $row['unit_price']);
            $row['line_total']      = $row['effective_price'] * $row['quantity'];
        }
        return $rows;
    }

    // ---- ADD ITEM ----
    public function add(int $productId, int $qty = 1, ?int $variationId = null): array {
        $cond = $this->condition();
        $varCond = $variationId ? "= $variationId" : "IS NULL";

        $existing = $this->selectOne(
            "SELECT id, quantity FROM cart
             WHERE $cond AND product_id = $productId AND variation_id $varCond"
        );

        if ($existing) {
            $newQty = $existing['quantity'] + $qty;
            $this->update('cart', ['quantity' => $newQty], "id = {$existing['id']}");
        } else {
            $data = [
                'product_id'   => $productId,
                'variation_id' => $variationId,
                'quantity'     => $qty,
                'added_at'     => date('Y-m-d H:i:s'),
            ];
            if ($this->userId) {
                $data['user_id'] = $this->userId;
            } else {
                $data['session_id'] = session_id();
            }
            $this->insert('cart', $data);
        }

        return ['count' => $this->count(), 'subtotal' => $this->subtotal()];
    }

    // ---- UPDATE QUANTITY ----
    public function updateQty(int $cartId, int $qty): bool {
        if ($qty <= 0) {
            return $this->removeById($cartId);
        }
        $cond = $this->condition();
        $this->query("UPDATE cart SET quantity=$qty WHERE id=$cartId AND $cond");
        return true;
    }

    // ---- REMOVE ----
    public function removeById(int $cartId): bool {
        $cond = $this->condition();
        $this->query("DELETE FROM cart WHERE id=$cartId AND $cond");
        return true;
    }


    // Get qty of a specific product already in cart
    public function getQtyInCart(int $productId, ?int $variationId = null): int {
        $cond    = $this->condition();
        $varCond = $variationId ? "= $variationId" : "IS NULL";
        $row     = $this->selectOne(
            "SELECT SUM(quantity) AS qty FROM cart
             WHERE $cond AND product_id=$productId AND variation_id $varCond"
        );
        return (int)($row['qty'] ?? 0);
    }

    // ---- CLEAR ----
    public function clear(): void {
        $cond = $this->condition();
        $this->query("DELETE FROM cart WHERE $cond");
    }

    // ---- COUNT ----
    public function count(): int {
        $cond = $this->condition();
        $row  = $this->selectOne("SELECT SUM(quantity) AS cnt FROM cart WHERE $cond");
        return (int)($row['cnt'] ?? 0);
    }

    // ---- SUBTOTAL ----
    public function subtotal(): float {
        $items = $this->getItems();
        return array_sum(array_column($items, 'line_total'));
    }

    // ---- APPLY COUPON ----
    public function applyCoupon(string $code, float $subtotal): array {
        $result = DiscountEngine::applyCoupon($code, $subtotal);
        if (!$result['success']) return $result;

        // Check per-user usage limit
        $couponId = $result['id'];
        $coupon   = $this->selectOne("SELECT per_user_limit FROM coupons WHERE id=$couponId");
        if ($this->userId && !empty($coupon['per_user_limit'])) {
            $used = $this->selectOne(
                "SELECT COUNT(*) AS cnt FROM coupon_usage WHERE coupon_id=$couponId AND user_id={$this->userId}"
            );
            if (($used['cnt'] ?? 0) >= $coupon['per_user_limit']) {
                return ['success'=>false,'message'=>'You have already used this coupon.'];
            }
        }

        $_SESSION['coupon'] = [
            'id'       => $couponId,
            'code'     => $result['code'],
            'discount' => $result['discount'],
        ];
        return $result;
    }

    // ---- TOTALS SUMMARY ----
    public function totals(?int $shippingMethodId = null, ?string $paymentMethod = null): array {
        $items    = $this->getItems();
        $subtotal = array_sum(array_column($items, 'line_total'));

        // Use session coupon OR auto-apply global discount
        $coupon   = $_SESSION['coupon'] ?? null;
        $discData = DiscountEngine::calculate(
            $subtotal,
            $items,
            $paymentMethod,
            $coupon ? $coupon['code'] : null
        );
        $discount = $discData['amount'];

        // If coupon in session but payment method now excludes it, clear it
        if ($coupon && DiscountEngine::isExcluded($paymentMethod ?? '')) {
            $discount = 0;
            $coupon   = null;
        }

        // BOGO on top
        $bogoSavings = DiscountEngine::bogoDiscount($items, $paymentMethod);

        $shipping = 0;
        if ($shippingMethodId) {
            $sm       = $this->selectOne("SELECT * FROM shipping_methods WHERE id=$shippingMethodId AND status=1");
            $shipping = $sm ? ($sm['is_free'] || ($sm['free_above'] > 0 && $subtotal >= (float)$sm['free_above']) ? 0 : (float)$sm['price']) : 0;
        }

        $total = max(0, $subtotal - $discount - $bogoSavings + $shipping);

        return compact('items','subtotal','discount','bogoSavings','shipping','total','coupon','discData');
    }

    /**
     * Returns the intersection of allowed payment method codes for all cart items.
     * Products with no restrictions don't constrain the result.
     * Empty return = no common method found (show warning).
     */
    public function allowedPaymentMethodCodes(): array {
        $items = $this->getItems();
        if (empty($items)) return [];

        $productIds = array_unique(array_map('intval', array_column($items, 'product_id')));
        $idList     = implode(',', $productIds);

        $rows = $this->select(
            "SELECT product_id, method_code
             FROM product_payment_methods
             WHERE product_id IN ($idList)"
        ) ?: [];

        if (empty($rows)) return []; // no product-level restrictions → all methods allowed

        // Group restrictions by product
        $byProduct = [];
        foreach ($rows as $r) {
            $byProduct[(int)$r['product_id']][] = $r['method_code'];
        }

        // Get all active codes as the starting set
        $all = array_column(
            $this->select("SELECT code FROM payment_methods WHERE is_active=1") ?: [],
            'code'
        );

        $allowed = $all;
        foreach ($byProduct as $codes) {
            $allowed = array_values(array_intersect($allowed, $codes));
        }

        return $allowed;
    }
}
