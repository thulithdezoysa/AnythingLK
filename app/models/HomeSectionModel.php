<?php
class HomeSectionModel extends Db {

    // ── Hero Sections ──────────────────────────────────
    public function getHeroSections(bool $activeOnly = true): array {
        $cond = $activeOnly ? 'WHERE status=1' : '';
        return $this->select("SELECT * FROM home_hero_sections $cond ORDER BY sort_order, id") ?: [];
    }

    public function getHeroSection(int $id): ?array {
        return $this->selectOne("SELECT * FROM home_hero_sections WHERE id=$id") ?: null;
    }

    public function saveHeroSection(array $d, int $id = 0): int {
        $fields = [
            'title'       => $d['title'],
            'subtitle'    => $d['subtitle'] ?? '',
            'badge_text'  => $d['badge_text'] ?? '',
            'category_id' => !empty($d['category_id']) ? (int)$d['category_id'] : null,
            'link'        => $d['link'] ?? '',
            'status'      => (int)($d['status'] ?? 1),
            'sort_order'  => (int)($d['sort_order'] ?? 0),
        ];
        if ($id) { $this->update('home_hero_sections', $fields, "id=$id"); return $id; }
        return $this->insert('home_hero_sections', $fields);
    }

    public function getProductsByCategory(int $catId, int $limit = 12): array {
        $ids = $this->getCategoryDescendants($catId);
        if (empty($ids)) return [];
        $inList = implode(',', $ids);
        return $this->select("
            SELECT p.id, p.slug, p.price, p.sale_price, p.thumbnail,
                   p.rating_avg, p.rating_count, p.is_new, p.is_featured,
                   p.name, p.short_desc,
                   COALESCE(ps.quantity,0) AS stock_qty
            FROM products p
            LEFT JOIN product_stock ps ON ps.product_id = p.id
                AND ps.variation_id IS NULL AND ps.warehouse='Main'
            WHERE p.category_id IN ($inList) AND p.status='active'
            GROUP BY p.id
            ORDER BY p.is_featured DESC, p.is_new DESC, p.created_at DESC
            LIMIT $limit
        ") ?: [];
    }

    private function getCategoryDescendants(int $rootId): array {
        $all  = Helper::categories();
        $ids  = [$rootId];
        $changed = true;
        while ($changed) {
            $changed = false;
            foreach ($all as $c) {
                $pid = (int)($c['parent_id'] ?? 0);
                if (in_array($pid, $ids) && !in_array((int)$c['id'], $ids)) {
                    $ids[]   = (int)$c['id'];
                    $changed = true;
                }
            }
        }
        return $ids;
    }

    public function deleteHeroSection(int $id): void {
        $row = $this->selectOne("SELECT image FROM home_hero_sections WHERE id=$id");
        if (!empty($row['image'])) @unlink(ROOT.'/uploads/home/'.$row['image']);
        $this->delete('home_hero_sections', "id=$id");
    }

    public function toggleHeroSection(int $id): void {
        $this->query("UPDATE home_hero_sections SET status = 1-status WHERE id=$id");
    }

    // ── Collections ───────────────────────────────────
    public function getCollections(bool $activeOnly = true): array {
        $cond = $activeOnly ? 'WHERE status=1' : '';
        return $this->select("SELECT * FROM home_collections $cond ORDER BY sort_order, id") ?: [];
    }

    public function getCollection(int $id): ?array {
        return $this->selectOne("SELECT * FROM home_collections WHERE id=$id") ?: null;
    }

    public function saveCollection(array $d, int $id = 0): int {
        $fields = [
            'name'       => $d['name'],
            'tagline'    => $d['tagline'] ?? '',
            'link'       => $d['link'] ?? '',
            'color'      => $d['color'] ?? '#2dc653',
            'status'     => (int)($d['status'] ?? 1),
            'sort_order' => (int)($d['sort_order'] ?? 0),
        ];
        if (!empty($d['image'])) $fields['image'] = $d['image'];
        if ($id) { $this->update('home_collections', $fields, "id=$id"); return $id; }
        return $this->insert('home_collections', $fields);
    }

    public function deleteCollection(int $id): void {
        $row = $this->selectOne("SELECT image FROM home_collections WHERE id=$id");
        if (!empty($row['image'])) @unlink(ROOT.'/uploads/home/'.$row['image']);
        $this->delete('home_collections', "id=$id");
    }

    public function toggleCollection(int $id): void {
        $this->query("UPDATE home_collections SET status = 1-status WHERE id=$id");
    }

    // ── Reviews ───────────────────────────────────────
    public function getReviews(bool $activeOnly = true): array {
        $cond = $activeOnly ? 'WHERE status=1' : '';
        return $this->select("SELECT * FROM home_reviews $cond ORDER BY sort_order, id") ?: [];
    }

    public function getReview(int $id): ?array {
        return $this->selectOne("SELECT * FROM home_reviews WHERE id=$id") ?: null;
    }

    public function saveReview(array $d, int $id = 0): int {
        $source = in_array($d['source']??'', ['google','facebook','trustpilot','other'])
            ? $d['source'] : 'google';
        $fields = [
            'name'       => $d['name'],
            'rating'     => min(5, max(1, (int)($d['rating'] ?? 5))),
            'review'     => $d['review'],
            'source'     => $source,
            'status'     => (int)($d['status'] ?? 1),
            'sort_order' => (int)($d['sort_order'] ?? 0),
        ];
        if ($id) { $this->update('home_reviews', $fields, "id=$id"); return $id; }
        return $this->insert('home_reviews', $fields);
    }

    public function deleteReview(int $id): void {
        $this->delete('home_reviews', "id=$id");
    }

    public function toggleReview(int $id): void {
        $this->query("UPDATE home_reviews SET status = 1-status WHERE id=$id");
    }

    // ── Sort order (drag-drop) ────────────────────────
    public function updateOrder(string $table, array $ids): void {
        $allowed = ['home_hero_sections','home_collections','home_reviews'];
        if (!in_array($table, $allowed)) return;
        foreach ($ids as $ord => $id) {
            $id  = (int)$id;
            $ord = (int)$ord;
            $this->query("UPDATE `$table` SET sort_order=$ord WHERE id=$id");
        }
    }
}
