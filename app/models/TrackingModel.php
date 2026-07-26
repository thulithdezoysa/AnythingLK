<?php
// ============================================================
// TrackingModel
// ============================================================
class TrackingModel extends Db {

    public function track(?int $userId, string $sessionId, int $productId, string $event, array $meta = []): void {
        $sid    = $this->connect()->real_escape_string($sessionId);
        $metaJ  = $meta ? $this->connect()->real_escape_string(json_encode($meta)) : 'NULL';
        $uidVal = $userId ? $userId : 'NULL';

        $this->query(
            "INSERT INTO user_behavior (user_id, session_id, product_id, event, metadata, created_at)
             VALUES ($uidVal, '$sid', $productId, '$event',
                     " . ($meta ? "'{$metaJ}'" : 'NULL') . ",
                     NOW())"
        );
    }

    public function getPersonalised(?int $userId, string $sessionId, int $limit = 8): array {
        // Products from same categories as viewed/purchased items
        $cond = $userId ? "b.user_id = $userId" : "b.session_id = '$sessionId'";

        return $this->select(
            "SELECT p.id, p.slug, p.name, p.price, p.sale_price, p.thumbnail, p.rating_avg,
                    COUNT(b.id) AS score
             FROM user_behavior b
             JOIN products p ON p.category_id IN (
                 SELECT DISTINCT p2.category_id FROM user_behavior b2
                 JOIN products p2 ON p2.id = b2.product_id
                 WHERE $cond
             )
             WHERE p.id NOT IN (
                 SELECT product_id FROM user_behavior WHERE $cond AND event='purchase'
             )
             AND p.status = 'active'
             GROUP BY p.id ORDER BY score DESC, p.rating_avg DESC
             LIMIT $limit"
        ) ?: [];
    }
}
