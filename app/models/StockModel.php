<?php
class StockModel extends Db {

    // ---- STOCK IN ----
    public function stockIn(int $productId, int $qty, string $warehouse = 'Main',
                            ?int $variationId = null, string $notes = '', ?int $userId = null,
                            string $refType = 'manual', ?int $refId = null): bool {
        $before = $this->getQty($productId, $variationId, $warehouse);
        $after  = $before + $qty;

        // Upsert stock
        $existing = $this->getStockRow($productId, $variationId, $warehouse);
        if ($existing) {
            $this->update('product_stock', ['quantity' => $after], "id={$existing['id']}");
        } else {
            $this->insert('product_stock', [
                'product_id'   => $productId,
                'variation_id' => $variationId,
                'warehouse'    => $warehouse,
                'quantity'     => $after,
            ]);
        }

        $this->logMovement($productId, $variationId, $warehouse, 'IN', $qty, $before, $after, $refType, $refId, $notes, $userId);
        return true;
    }

    // ---- STOCK ADJUSTMENT ----
    public function adjust(int $productId, int $newQty, string $warehouse = 'Main',
                           ?int $variationId = null, string $notes = '', ?int $userId = null): bool {
        $before = $this->getQty($productId, $variationId, $warehouse);

        $existing = $this->getStockRow($productId, $variationId, $warehouse);
        if ($existing) {
            $this->update('product_stock', ['quantity' => $newQty], "id={$existing['id']}");
        } else {
            $this->insert('product_stock', [
                'product_id'   => $productId,
                'variation_id' => $variationId,
                'warehouse'    => $warehouse,
                'quantity'     => $newQty,
            ]);
        }

        $this->logMovement($productId, $variationId, $warehouse, 'ADJUSTMENT', $newQty - $before, $before, $newQty, 'manual', null, $notes, $userId);
        return true;
    }

    // ---- RECEIVE PURCHASE ORDER ----
    public function receivePO(int $poId, ?int $userId = null): array {
        $po = $this->selectOne("SELECT * FROM purchase_orders WHERE id=$poId");
        if (!$po || $po['status'] === 'received') {
            return ['success' => false, 'message' => 'Invalid or already received PO.'];
        }

        $items = $this->select("SELECT * FROM purchase_order_items WHERE po_id=$poId");
        foreach ($items as $item) {
            $this->stockIn(
                $item['product_id'],
                $item['quantity'],
                'Main',
                $item['variation_id'] ?: null,
                "PO #{$po['po_number']}",
                $userId,
                'purchase_order',
                $poId
            );
            $this->update('purchase_order_items', ['received_qty' => $item['quantity']], "id={$item['id']}");
        }

        $this->update('purchase_orders', [
            'status'        => 'received',
            'received_date' => date('Y-m-d'),
        ], "id=$poId");

        return ['success' => true, 'message' => 'Stock updated from Purchase Order.'];
    }

    // ---- ALERTS: LOW STOCK ----
    public function getLowStock(): array {
        return $this->select(
            "SELECT ps.*, p.name AS product_name, p.slug, p.thumbnail,
                    pv.name AS variation_name
             FROM product_stock ps
             JOIN products p ON p.id = ps.product_id
             LEFT JOIN product_variations pv ON pv.id = ps.variation_id
             WHERE ps.quantity <= ps.low_stock_threshold
               AND ps.quantity > 0
               AND p.status = 'active' AND p.track_stock = 1
             ORDER BY ps.quantity ASC"
        ) ?: [];
    }

    public function getOutOfStock(): array {
        return $this->select(
            "SELECT ps.*, p.name AS product_name, p.slug
             FROM product_stock ps
             JOIN products p ON p.id = ps.product_id
             WHERE ps.quantity = 0 AND p.status = 'active' AND p.track_stock = 1"
        ) ?: [];
    }

    // ---- MOVEMENT HISTORY ----
    public function getMovements(int $productId, int $limit = 20): array {
        return $this->select(
            "SELECT sm.*, p.name AS product_name, u.full_name AS created_by_name
             FROM stock_movements sm
             JOIN products p ON p.id = sm.product_id
             LEFT JOIN users u ON u.id = sm.created_by
             WHERE sm.product_id = $productId
             ORDER BY sm.created_at DESC LIMIT $limit"
        ) ?: [];
    }

    public function getAllMovements(int $page = 1, int $per = 30, ?int $productId = null): array {
        $offset    = ($page - 1) * $per;
        $whereProd = $productId ? "AND sm.product_id = $productId" : '';
        return $this->select(
            "SELECT sm.*, p.name AS product_name, u.full_name AS created_by_name
             FROM stock_movements sm
             JOIN products p ON p.id = sm.product_id
             LEFT JOIN users u ON u.id = sm.created_by
             WHERE 1=1 $whereProd
             ORDER BY sm.created_at DESC
             LIMIT $per OFFSET $offset"
        ) ?: [];
    }

    // ---- PRIVATE HELPERS ----
    private function getQty(int $productId, ?int $variationId, string $warehouse): int {
        $row = $this->getStockRow($productId, $variationId, $warehouse);
        return (int)($row['quantity'] ?? 0);
    }

    private function getStockRow(int $productId, ?int $variationId, string $warehouse): ?array {
        $varCond = $variationId ? "= $variationId" : "IS NULL";
        $wh      = $this->connect()->real_escape_string($warehouse);
        return $this->selectOne(
            "SELECT * FROM product_stock
             WHERE product_id=$productId AND variation_id $varCond AND warehouse='$wh'"
        );
    }

    private function logMovement(int $productId, ?int $variationId, string $warehouse,
                                  string $type, int $qty, int $before, int $after,
                                  string $refType = 'manual', ?int $refId = null,
                                  string $notes = '', ?int $userId = null): void {
        $this->insert('stock_movements', [
            'product_id'     => $productId,
            'variation_id'   => $variationId,
            'warehouse'      => $warehouse,
            'type'           => $type,
            'quantity'       => $qty,
            'quantity_before'=> $before,
            'quantity_after' => $after,
            'reference_type' => $refType,
            'reference_id'   => $refId,
            'notes'          => $notes,
            'created_by'     => $userId,
            'created_at'     => date('Y-m-d H:i:s'),
        ]);
    }
}
