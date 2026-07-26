<?php
require_once ROOT . '/core/Controller.php';

class ProductModel extends Db {

    private string $lang;

    public function __construct() {
        $this->lang = Lang::current();
    }

    // Localised field helper
    private function nameField(string $base = 'name'): string {
        return $this->lang !== 'en'
            ? "COALESCE(NULLIF(p.{$base}_{$this->lang},''), p.{$base})"
            : "p.{$base}";
    }

    // ---- LISTING ----
    public function getListing(array $filters = [], int $page = 1, int $perPage = 16): array {
        $where      = ["p.status = 'active'"];
        $order      = 'p.created_at DESC';
        $nameF      = $this->nameField('name');
        $shortDescF = $this->nameField('short_desc');

        if (!empty($filters['category'])) {
            $cat = $this->connect()->real_escape_string($filters['category']);
            $where[] = "(c.slug = '$cat' OR pc.slug = '$cat')";
        }
        if (!empty($filters['brand'])) {
            $b = $this->connect()->real_escape_string($filters['brand']);
            $where[] = "br.slug = '$b'";
        }
        if (!empty($filters['q'])) {
            $db  = $this->connect();
            $q   = $db->real_escape_string($filters['q']);
            $len = mb_strlen(trim($filters['q']));

            if ($len >= 4) {
                // FULLTEXT for relevance + LIKE fallback so short/partial tokens never miss
                $where[] = "(MATCH(p.name, p.short_desc, p.description, p.tags)
                             AGAINST('$q' IN BOOLEAN MODE)
                             OR {$nameF} LIKE '%$q%' OR p.tags LIKE '%$q%')";
                $order = "({$nameF} LIKE '$q%') DESC,
                          MATCH(p.name, p.short_desc, p.tags) AGAINST('$q' IN BOOLEAN MODE) DESC,
                          p.views DESC";
            } else {
                // Short query: FULLTEXT won't index it, so LIKE only
                $where[] = "({$nameF} LIKE '%$q%' OR p.tags LIKE '%$q%'
                             OR {$shortDescF} LIKE '%$q%')";
                $order = "({$nameF} LIKE '$q%') DESC, p.views DESC";
            }
        }
        if (!empty($filters['min_price'])) {
            $where[] = "COALESCE(p.sale_price, p.price) >= {$filters['min_price']}";
        }
        if (!empty($filters['max_price'])) {
            $where[] = "COALESCE(p.sale_price, p.price) <= {$filters['max_price']}";
        }
        if (!empty($filters['featured'])) {
            $where[] = "p.is_featured = 1";
        }
        if (!empty($filters['new'])) {
            $where[] = "p.is_new = 1";
        }
        switch ($filters['sort'] ?? '') {
            case 'price_asc':  $order = 'COALESCE(p.sale_price,p.price) ASC';  break;
            case 'price_desc': $order = 'COALESCE(p.sale_price,p.price) DESC'; break;
            case 'popular':    $order = 'p.views DESC, p.rating_avg DESC';      break;
            case 'rating':     $order = 'p.rating_avg DESC';                    break;
            case 'newest':     $order = 'p.created_at DESC';                    break;
        }
        $whereStr  = implode(' AND ', $where);
        $offset    = ($page - 1) * $perPage;

        $sql = "SELECT p.id, p.slug, p.price, p.sale_price, p.thumbnail,
                       p.rating_avg, p.rating_count, p.is_new, p.is_featured,
                       {$nameF} AS name,
                       {$shortDescF} AS short_desc,
                       c.name AS category_name, c.slug AS category_slug,
                       br.name AS brand_name,
                       COALESCE(ps.quantity,0) AS stock_qty
                FROM products p
                LEFT JOIN categories c  ON c.id = p.category_id
                LEFT JOIN categories pc ON pc.id = c.parent_id
                LEFT JOIN brands br     ON br.id = p.brand_id
                LEFT JOIN product_stock ps ON ps.product_id = p.id
                    AND ps.variation_id IS NULL AND ps.warehouse='Main'
                WHERE {$whereStr}
                GROUP BY p.id
                ORDER BY {$order}
                LIMIT {$perPage} OFFSET {$offset}";

        $products = $this->select($sql);

        // Total count
        $countSql = "SELECT COUNT(DISTINCT p.id) AS cnt
                     FROM products p
                     LEFT JOIN categories c  ON c.id = p.category_id
                     LEFT JOIN categories pc ON pc.id = c.parent_id
                     LEFT JOIN brands br     ON br.id = p.brand_id
                     WHERE {$whereStr}";
        $total = (int)($this->selectOne($countSql)['cnt'] ?? 0);

        return ['products' => $products ?: [], 'total' => $total];
    }

    // ---- LIVE SEARCH (AJAX instant-search, LIKE-based for any query length) ----
    public function liveSearch(string $q, int $limit = 6): array
    {
        $db  = $this->connect();
        $esc = $db->real_escape_string($q);
        $n   = $this->nameField('name');
        $sd  = $this->nameField('short_desc');

        return $this->select(
            "SELECT DISTINCT
                 p.id, p.slug, p.price, p.sale_price, p.thumbnail,
                 {$n}  AS name,
                 {$sd} AS short_desc,
                 c.name  AS category_name,
                 br.name AS brand_name,
                 (CASE
                     WHEN {$n} = '$esc'           THEN 100
                     WHEN {$n} LIKE '$esc %'       THEN  90
                     WHEN {$n} LIKE '$esc%'        THEN  80
                     WHEN {$n} LIKE '% $esc %'     THEN  70
                     WHEN {$n} LIKE '%$esc%'       THEN  60
                     WHEN p.tags LIKE '%$esc%'     THEN  40
                     WHEN c.name  LIKE '%$esc%'
                       OR br.name LIKE '%$esc%'    THEN  20
                     WHEN p.short_desc LIKE '%$esc%' THEN 10
                     ELSE 0
                 END) AS _score
             FROM products p
             LEFT JOIN categories c  ON c.id = p.category_id
             LEFT JOIN brands br     ON br.id = p.brand_id
             WHERE p.status = 'active'
               AND (
                    {$n}          LIKE '%$esc%'
                 OR p.tags        LIKE '%$esc%'
                 OR p.short_desc  LIKE '%$esc%'
                 OR c.name        LIKE '%$esc%'
                 OR br.name       LIKE '%$esc%'
               )
             ORDER BY _score DESC, p.views DESC, p.rating_avg DESC
             LIMIT $limit"
        ) ?: [];
    }

    // ---- SINGLE PRODUCT ----
    public function getBySlug(string $slug): ?array {
        $slug = $this->connect()->real_escape_string($slug);
        $n    = $this->nameField('name');
        $sd   = $this->nameField('short_desc');
        $desc = $this->nameField('description');

        $p = $this->selectOne(
            "SELECT p.*, {$n} AS name, {$sd} AS short_desc, {$desc} AS description,
                    c.name AS category_name, c.slug AS category_slug,
                    br.name AS brand_name, br.slug AS brand_slug,
                    v.company_name AS vendor_name
             FROM products p
             LEFT JOIN categories c ON c.id = p.category_id
             LEFT JOIN brands br    ON br.id = p.brand_id
             LEFT JOIN vendors v    ON v.id  = p.vendor_id
             WHERE p.slug = '$slug' AND p.status = 'active'"
        );
        if (!$p) return null;

        $id = $p['id'];
        $p['images']     = $this->select("SELECT * FROM product_images WHERE product_id=$id ORDER BY sort_order");
        $p['variations'] = $this->select("SELECT * FROM product_variations WHERE product_id=$id AND status=1 ORDER BY sort_order");
        $p['attributes'] = $this->select(
            "SELECT pa.name AS attr_name, pa.type, pav.value, pav.color_hex
             FROM product_attribute_values pav
             JOIN product_attributes pa ON pa.id = pav.attribute_id
             WHERE pav.product_id = $id"
        );
        $p['stock'] = $this->selectOne("SELECT quantity FROM product_stock WHERE product_id=$id AND variation_id IS NULL AND warehouse='Main'");
        $p['avg_rating'] = $this->getAvgRating($id);
        return $p;
    }

    // ---- STOCK ----
    public function getStock(int $productId, ?int $variationId = null): int {
        $varCond = $variationId ? "= $variationId" : "IS NULL";
        $row = $this->selectOne(
            "SELECT quantity FROM product_stock
             WHERE product_id=$productId AND variation_id $varCond AND warehouse='Main'"
        );
        return (int)($row['quantity'] ?? 0);
    }

    public function decrementStock(int $productId, int $qty, ?int $variationId = null): void {
        $varCond = $variationId ? "= $variationId" : "IS NULL";
        $this->query(
            "UPDATE product_stock SET quantity = GREATEST(0, quantity - $qty)
             WHERE product_id=$productId AND variation_id $varCond AND warehouse='Main'"
        );
    }

    // ---- REVIEWS ----
    public function getReviews(int $productId, int $limit = 10): array {
        return $this->select(
            "SELECT r.*, u.full_name, u.avatar
             FROM product_reviews r
             JOIN users u ON u.id = r.user_id
             WHERE r.product_id=$productId AND r.status='approved'
             ORDER BY r.created_at DESC LIMIT $limit"
        ) ?: [];
    }

    public function getAvgRating(int $productId): array {
        return $this->selectOne(
            "SELECT AVG(rating) AS avg, COUNT(*) AS total
             FROM product_reviews WHERE product_id=$productId AND status='approved'"
        ) ?? ['avg' => 0, 'total' => 0];
    }

    // ---- RELATED PRODUCTS ----
    public function getRelated(int $productId, int $categoryId, int $limit = 8): array {
        $fields = "p.id, p.slug, p.price, p.sale_price, p.thumbnail,
                   p.rating_avg, p.rating_count, p.is_new, p.is_featured,
                   p.name, p.short_desc,
                   COALESCE(ps.quantity, 0) AS stock_qty";
        $join   = "LEFT JOIN product_stock ps
                       ON ps.product_id = p.id AND ps.variation_id IS NULL AND ps.warehouse='Main'";

        // Primary: same category
        $rows = $this->select(
            "SELECT $fields FROM products p $join
             WHERE p.category_id=$categoryId AND p.id != $productId AND p.status='active'
             ORDER BY p.rating_avg DESC, p.views DESC
             LIMIT $limit"
        ) ?: [];

        // Fallback: parent category or site-wide popular
        if (count($rows) < 4) {
            $parentRow = $this->selectOne("SELECT parent_id FROM categories WHERE id=$categoryId");
            $parentId  = $parentRow ? (int)$parentRow['parent_id'] : 0;
            $exclude   = array_merge([$productId], array_column($rows, 'id'));
            $excList   = implode(',', $exclude);
            $need      = $limit - count($rows);
            $extra = $parentId
                ? ($this->select(
                    "SELECT $fields FROM products p $join
                     LEFT JOIN categories c ON c.id = p.category_id
                     WHERE (p.category_id=$parentId OR c.parent_id=$parentId)
                       AND p.id NOT IN ($excList) AND p.status='active'
                     ORDER BY p.views DESC LIMIT $need") ?: [])
                : ($this->select(
                    "SELECT $fields FROM products p $join
                     WHERE p.id NOT IN ($excList) AND p.status='active'
                     ORDER BY p.views DESC, p.rating_avg DESC LIMIT $need") ?: []);
            $rows = array_merge($rows, $extra);
        }

        return $rows;
    }

    // ---- FREQUENTLY BOUGHT TOGETHER ----
    public function getFrequentlyBought(int $productId, int $limit = 4): array {
        return $this->select(
            "SELECT p.id, p.slug, p.price, p.sale_price, p.thumbnail, p.name, pr.score
             FROM product_recommendations pr
             JOIN products p ON p.id = pr.related_id
             WHERE pr.product_id=$productId AND pr.type='frequently_bought'
               AND p.status='active'
             ORDER BY pr.score DESC LIMIT $limit"
        ) ?: [];
    }

    // ---- YOU MAY ALSO LIKE ----
    public function getYouMayLike(int $productId, int $brandId, int $categoryId, int $limit = 8): array {
        $fields = "p.id, p.slug, p.price, p.sale_price, p.thumbnail,
                   p.rating_avg, p.rating_count, p.is_new, p.is_featured,
                   p.name, p.short_desc,
                   COALESCE(ps.quantity, 0) AS stock_qty";
        $join   = "LEFT JOIN product_stock ps
                       ON ps.product_id = p.id AND ps.variation_id IS NULL AND ps.warehouse='Main'";

        if ($brandId) {
            $rows = $this->select(
                "SELECT $fields FROM products p $join
                 WHERE p.brand_id=$brandId AND p.id != $productId AND p.status='active'
                 ORDER BY p.rating_avg DESC, p.views DESC LIMIT $limit"
            ) ?: [];
            if (count($rows) >= 4) return $rows;
        }
        return $this->select(
            "SELECT $fields FROM products p $join
             WHERE p.category_id=$categoryId AND p.id != $productId AND p.status='active'
             ORDER BY p.views DESC, p.rating_avg DESC LIMIT $limit"
        ) ?: [];
    }

    // ---- FEATURED / NEW / BEST ----
    public function getFeatured(int $limit = 8): array {
        return $this->select(
            "SELECT p.id, p.slug, p.price, p.sale_price, p.thumbnail,
                    p.rating_avg, p.name
             FROM products p WHERE p.is_featured=1 AND p.status='active'
             ORDER BY p.views DESC LIMIT $limit"
        ) ?: [];
    }

    public function getNew(int $limit = 8): array {
        return $this->select(
            "SELECT p.id, p.slug, p.price, p.sale_price, p.thumbnail,
                    p.rating_avg, p.name
             FROM products p WHERE p.is_new=1 AND p.status='active'
             ORDER BY p.created_at DESC LIMIT $limit"
        ) ?: [];
    }

    public function getBestSellers(int $limit = 8): array {
        return $this->select(
            "SELECT p.id, p.slug, p.price, p.sale_price, p.thumbnail,
                    p.rating_avg, p.name,
                    SUM(oi.quantity) AS sold_qty
             FROM order_items oi
             JOIN products p ON p.id = oi.product_id
             WHERE p.status='active'
             GROUP BY p.id ORDER BY sold_qty DESC LIMIT $limit"
        ) ?: [];
    }

    // ---- CATEGORIES ----
    public function getCategories(bool $withChildren = false): array {
        $rows = $this->select(
            "SELECT c.*, COUNT(p.id) AS product_count
             FROM categories c
             LEFT JOIN products p ON p.category_id = c.id AND p.status='active'
             WHERE c.parent_id IS NULL AND c.status=1
             GROUP BY c.id ORDER BY c.sort_order"
        ) ?: [];

        if ($withChildren) {
            foreach ($rows as &$row) {
                $row['children'] = $this->select(
                    "SELECT * FROM categories WHERE parent_id={$row['id']} AND status=1
                     ORDER BY sort_order"
                ) ?: [];
            }
        }
        return $rows;
    }

    public function getCategoryBySlug(string $slug): ?array {
        $found = array_filter(Helper::categories(), fn($c) => $c['slug'] === $slug);
        return $found ? reset($found) : null;
    }

    // Returns ordered array [root, ..., direct_cat] by walking parent_id chain
    public function getCategoryAncestors(int $catId): array {
        $all   = Helper::categories();
        $byId  = [];
        foreach ($all as $c) { $byId[(int)$c['id']] = $c; }

        $chain = [];
        $cur   = $catId;
        $guard = 0;
        while ($cur && isset($byId[$cur]) && $guard++ < 10) {
            array_unshift($chain, $byId[$cur]);
            $cur = (int)($byId[$cur]['parent_id'] ?? 0);
        }
        return $chain;
    }

    // ---- BRANDS WITH FILTER-AWARE COUNTS ----
    public function getBrandsWithCounts(array $filters = []): array {
        $where = ["p.status='active'"];
        $db    = $this->connect();
        if (!empty($filters['category'])) {
            $cat = $db->real_escape_string($filters['category']);
            $where[] = "(c.slug='$cat' OR pc.slug='$cat')";
        }
        if (!empty($filters['min_price'])) {
            $where[] = "COALESCE(p.sale_price,p.price)>=" . (float)$filters['min_price'];
        }
        if (!empty($filters['max_price'])) {
            $where[] = "COALESCE(p.sale_price,p.price)<=" . (float)$filters['max_price'];
        }
        if (!empty($filters['q'])) {
            $q = $db->real_escape_string($filters['q']);
            $where[] = "MATCH(p.name,p.short_desc,p.description,p.tags) AGAINST('$q' IN BOOLEAN MODE)";
        }
        $w = implode(' AND ', $where);
        return $this->select(
            "SELECT br.id, br.name, br.slug, COUNT(DISTINCT p.id) AS cnt
             FROM brands br
             INNER JOIN products p  ON p.brand_id = br.id
             LEFT  JOIN categories c  ON c.id = p.category_id
             LEFT  JOIN categories pc ON pc.id = c.parent_id
             WHERE {$w}
             GROUP BY br.id HAVING cnt > 0
             ORDER BY br.name"
        ) ?: [];
    }

    // ---- CATEGORIES WITH FILTER-AWARE COUNTS ----
    public function getCategoriesWithCounts(array $filters = []): array {
        $where = ["p.status='active'"];
        $db    = $this->connect();
        if (!empty($filters['brand'])) {
            $b = $db->real_escape_string($filters['brand']);
            $where[] = "br.slug='$b'";
        }
        if (!empty($filters['min_price'])) {
            $where[] = "COALESCE(p.sale_price,p.price)>=" . (float)$filters['min_price'];
        }
        if (!empty($filters['max_price'])) {
            $where[] = "COALESCE(p.sale_price,p.price)<=" . (float)$filters['max_price'];
        }
        if (!empty($filters['q'])) {
            $q = $db->real_escape_string($filters['q']);
            $where[] = "MATCH(p.name,p.short_desc,p.description,p.tags) AGAINST('$q' IN BOOLEAN MODE)";
        }
        $w = implode(' AND ', $where);
        return $this->select(
            "SELECT c.id, c.name, c.slug, c.icon, COUNT(DISTINCT p.id) AS product_count
             FROM categories c
             INNER JOIN products p  ON p.category_id = c.id
             LEFT  JOIN brands br   ON br.id = p.brand_id
             WHERE c.status=1 AND {$w}
             GROUP BY c.id HAVING product_count > 0
             ORDER BY c.sort_order, c.name"
        ) ?: [];
    }

    // ---- CATEGORY TREE WITH COUNTS (for collapsible sidebar) ----
    public function getCategoryTreeWithCounts(array $filters = []): array {
        $db    = $this->connect();
        $where = ["p.status='active'"];
        if (!empty($filters['brand'])) {
            $b = $db->real_escape_string($filters['brand']);
            $where[] = "br.slug='$b'";
        }
        if (!empty($filters['min_price'])) {
            $where[] = "COALESCE(p.sale_price,p.price)>=" . (float)$filters['min_price'];
        }
        if (!empty($filters['max_price'])) {
            $where[] = "COALESCE(p.sale_price,p.price)<=" . (float)$filters['max_price'];
        }
        if (!empty($filters['q'])) {
            $q = $db->real_escape_string($filters['q']);
            $where[] = "MATCH(p.name,p.short_desc,p.description,p.tags) AGAINST('$q' IN BOOLEAN MODE)";
        }
        $w = implode(' AND ', $where);

        $rows = $this->select(
            "SELECT c.id, c.name, c.slug, c.icon, c.parent_id, c.depth, c.sort_order,
                    COALESCE(cnt.direct_count, 0) AS direct_count
             FROM categories c
             LEFT JOIN (
                 SELECT p.category_id, COUNT(DISTINCT p.id) AS direct_count
                 FROM products p
                 LEFT JOIN brands br ON br.id = p.brand_id
                 WHERE $w
                 GROUP BY p.category_id
             ) cnt ON cnt.category_id = c.id
             WHERE c.status=1
             ORDER BY c.depth, c.parent_id, c.sort_order, c.name"
        ) ?: [];

        if (empty($rows)) return [];

        $tree = Helper::getCategoryTree($rows);
        self::addTotalCounts($tree);
        self::pruneZeroCounts($tree);
        return $tree;
    }

    private static function addTotalCounts(array &$nodes): int {
        $sum = 0;
        foreach ($nodes as &$n) {
            $childSum = empty($n['_children']) ? 0 : self::addTotalCounts($n['_children']);
            $n['total_count'] = (int)$n['direct_count'] + $childSum;
            $sum += $n['total_count'];
        }
        return $sum;
    }

    private static function pruneZeroCounts(array &$nodes): void {
        foreach ($nodes as $k => &$n) {
            if (!empty($n['_children'])) self::pruneZeroCounts($n['_children']);
            if ($n['total_count'] === 0) unset($nodes[$k]);
        }
        $nodes = array_values($nodes);
    }

    // ---- INCREMENT VIEWS ----
    public function incrementViews(int $productId): void {
        $this->query("UPDATE products SET views=views+1 WHERE id=$productId");
    }
}
