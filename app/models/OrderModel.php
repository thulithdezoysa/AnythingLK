<?php
class OrderModel extends Db {

    // ---- PLACE ORDER ----
    public function create(array $data, array $cartItems, float $subtotal, float $discount, float $shipping): ?int {
        $orderNumber = Helper::orderNumber();
        $total       = max(0, $subtotal - $discount + $shipping);

        $orderId = $this->insert('orders', [
            'order_number'    => $orderNumber,
            'user_id'         => $data['user_id'] ?? null,
            'guest_email'     => $data['email'] ?? null,
            'status'          => 'pending',
            'payment_status'  => 'unpaid',
            'payment_method'  => $data['payment_method'] ?? 'cod',
            'subtotal'        => $subtotal,
            'discount_amount' => $discount,
            'shipping_amount' => $shipping,
            'total_amount'    => $total,
            'coupon_code'     => $_SESSION['coupon']['code'] ?? null,
            'shipping_name'   => $data['full_name'],
            'shipping_phone'  => $data['phone'],
            'shipping_address'=> $data['address'],
            'shipping_city'   => $data['city'],
            'shipping_state'  => $data['state'] ?? '',
            'shipping_postal' => $data['postal'] ?? '',
            'shipping_country'=> $data['country'] ?? 'Sri Lanka',
            'shipping_method' => $data['shipping_method'] ?? 'Standard',
            'notes'           => $data['notes'] ?? '',
            'ip_address'      => $_SERVER['REMOTE_ADDR'] ?? '',
            'created_at'      => date('Y-m-d H:i:s'),
        ]);

        if (!$orderId) return null;

        $pm = new ProductModel();

        foreach ($cartItems as $item) {
            $this->insert('order_items', [
                'order_id'      => $orderId,
                'product_id'    => $item['product_id'],
                'variation_id'  => $item['variation_id'] ?? null,
                'vendor_id'     => null,
                'product_name'  => $item['name'],
                'variation_name'=> $item['variation_name'] ?? '',
                'quantity'      => $item['quantity'],
                'unit_price'    => $item['effective_price'],
                'total_price'   => $item['line_total'],
            ]);

            // Decrement stock
            $pm->decrementStock($item['product_id'], $item['quantity'], $item['variation_id'] ?? null);

            // Log stock movement
            $before = $pm->getStock($item['product_id'], $item['variation_id'] ?? null) + $item['quantity'];
            $after  = $before - $item['quantity'];
            $this->insert('stock_movements', [
                'product_id'     => $item['product_id'],
                'variation_id'   => $item['variation_id'] ?? null,
                'type'           => 'OUT',
                'quantity'       => $item['quantity'],
                'quantity_before'=> $before,
                'quantity_after' => $after,
                'reference_type' => 'order',
                'reference_id'   => $orderId,
                'created_at'     => date('Y-m-d H:i:s'),
            ]);
        }

        // Log coupon usage
        if (!empty($_SESSION['coupon']['id']) && !empty($data['user_id'])) {
            $this->insert('coupon_usage', [
                'coupon_id' => $_SESSION['coupon']['id'],
                'user_id'   => $data['user_id'],
                'order_id'  => $orderId,
            ]);
            $this->query("UPDATE coupons SET usage_count=usage_count+1 WHERE id={$_SESSION['coupon']['id']}");
        }

        // Status history entry
        $this->insert('order_status_history', [
            'order_id'   => $orderId,
            'status'     => 'pending',
            'notes'      => 'Order placed',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        unset($_SESSION['coupon']);
        return $orderId;
    }

    // ---- GET BY ID ----
    public function getById(int $id): ?array {
        $order = $this->selectOne(
            "SELECT o.*, u.full_name AS user_full_name, u.email AS user_email, u.phone AS user_phone
             FROM orders o
             LEFT JOIN users u ON u.id = o.user_id
             WHERE o.id = $id"
        );
        if (!$order) return null;
        $order['items']   = $this->select("SELECT oi.*, p.thumbnail FROM order_items oi LEFT JOIN products p ON p.id=oi.product_id WHERE oi.order_id=$id");
        $order['history'] = $this->select("SELECT * FROM order_status_history WHERE order_id=$id ORDER BY created_at");
        return $order;
    }

    // ---- GET BY USER ----
    public function getByUser(int $userId, int $page = 1, int $per = 10): array {
        $offset = ($page - 1) * $per;
        return $this->select(
            "SELECT o.*, COUNT(oi.id) AS item_count
             FROM orders o
             LEFT JOIN order_items oi ON oi.order_id = o.id
             WHERE o.user_id = $userId
             GROUP BY o.id
             ORDER BY o.created_at DESC
             LIMIT $per OFFSET $offset"
        ) ?: [];
    }

    // ---- UPDATE STATUS ----
    public function updateStatus(int $orderId, string $status, string $notes = '', ?int $adminId = null): void {
        $this->update('orders', ['status' => $status, 'updated_at' => date('Y-m-d H:i:s')], "id=$orderId");
        $this->insert('order_status_history', [
            'order_id'   => $orderId,
            'status'     => $status,
            'notes'      => $notes,
            'changed_by' => $adminId,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
