<?php
require_once ADMIN . '/AdminController.php';

class AdminProductController extends AdminController {

    public function index(array $p = []): void {
        $pageTitle = 'Products';
        $search    = Helper::sanitize($_GET['search'] ?? '');
        $cond      = "p.status != 'deleted'";
        if ($search) {
            $sq    = $this->db->connect()->real_escape_string($search);
            $cond .= " AND (p.name LIKE '%$sq%' OR p.sku LIKE '%$sq%')";
        }
        $catFilter = (int)($_GET['category'] ?? 0);
        if ($catFilter) $cond .= " AND p.category_id = $catFilter";

        $perPage = in_array((int)($_GET['per_page'] ?? 25), [10,25,50,100]) ? (int)($_GET['per_page'] ?? 25) : 25;
        $page    = max(1, (int)($_GET['page'] ?? 1));
        $offset  = ($page - 1) * $perPage;

        $total   = (int)($this->db->selectOne(
            "SELECT COUNT(DISTINCT p.id) AS cnt FROM products p WHERE $cond"
        )['cnt'] ?? 0);

        $products = $this->db->select(
            "SELECT p.*, c.name AS cat_name, b.name AS brand_name,
                    COALESCE(ps.quantity,0) AS stock_qty
             FROM products p
             LEFT JOIN categories c ON c.id = p.category_id
             LEFT JOIN brands b     ON b.id = p.brand_id
             LEFT JOIN product_stock ps ON ps.product_id=p.id AND ps.variation_id IS NULL AND ps.warehouse='Main'
             WHERE $cond
             ORDER BY p.created_at DESC
             LIMIT $perPage OFFSET $offset"
        ) ?: [];

        $pagData    = Helper::paginate($total, $perPage, $page);
        $categories = $this->buildCategoryTree();
        $this->view('products/index', compact('pageTitle','products','search','categories','catFilter','pagData','total','perPage'));
    }

    public function create(array $p = []): void {
        $this->ensurePmTable();
        $pageTitle  = 'Add Product';
        $categories = $this->buildCategoryTree();
        $brands     = $this->db->select("SELECT * FROM brands WHERE status=1 ORDER BY name") ?: [];
        $vendors    = $this->db->select("SELECT * FROM vendors WHERE status='active' ORDER BY company_name") ?: [];
        $attributes = $this->db->select("SELECT * FROM product_attributes") ?: [];
        $allPm      = $this->db->select("SELECT * FROM payment_methods WHERE is_active=1 ORDER BY sort_order") ?: [];
        $activePm   = [];
        $this->view('products/form', compact('pageTitle','categories','brands','vendors','attributes','allPm','activePm'));
    }

    public function store(array $p = []): void {
        CSRF::check();
        $slug = Helper::slug($_POST['name'] ?? '');
        // Ensure unique slug
        $exists = $this->db->selectOne("SELECT id FROM products WHERE slug='$slug'");
        if ($exists) $slug .= '-' . time();

        $thumbnail = null;
        if (!empty($_FILES['thumbnail']['name'])) {
            if (($_FILES['thumbnail']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
                $errMsg = $_FILES['thumbnail']['error'] === UPLOAD_ERR_INI_SIZE
                    ? 'Thumbnail too large. Max allowed: 10MB.'
                    : 'Thumbnail upload failed (error code: ' . $_FILES['thumbnail']['error'] . ').';
                $this->json(['success' => false, 'message' => $errMsg]);
                return;
            }
            $thumbnail = Helper::uploadImage($_FILES['thumbnail'], 'products');
            if (!$thumbnail) {
                $this->json(['success' => false, 'message' => 'Thumbnail upload failed. Use JPG, PNG, GIF, or WebP under 10MB.']);
                return;
            }
        }

        $pid = $this->db->insert('products', [
            'category_id'   => (int)($_POST['category_id'] ?? 0),
            'brand_id'      => (int)($_POST['brand_id'] ?? 0) ?: null,
            'vendor_id'     => (int)($_POST['vendor_id'] ?? 0) ?: null,
            'name'          => Helper::sanitize($_POST['name'] ?? ''),
            'name_si'       => Helper::sanitize($_POST['name_si'] ?? ''),
            'name_ta'       => Helper::sanitize($_POST['name_ta'] ?? ''),
            'slug'          => $slug,
            'sku'           => Helper::sanitize($_POST['sku'] ?? '') ?: 'SKU-'.time(),
            'short_desc'    => Helper::sanitize($_POST['short_desc'] ?? ''),
            'short_desc_si' => Helper::sanitize($_POST['short_desc_si'] ?? ''),
            'short_desc_ta' => Helper::sanitize($_POST['short_desc_ta'] ?? ''),
            'description'   => $_POST['description'] ?? '',
            'description_si'=> $_POST['description_si'] ?? '',
            'description_ta'=> $_POST['description_ta'] ?? '',
            'price'         => (float)($_POST['price'] ?? 0),
            'sale_price'    => (float)($_POST['sale_price'] ?? 0) ?: null,
            'cost_price'    => (float)($_POST['cost_price'] ?? 0),
            'tax_rate'      => (float)($_POST['tax_rate'] ?? 0),
            'weight'        => (float)($_POST['weight'] ?? 0),
            'thumbnail'     => $thumbnail,
            'tags'          => Helper::sanitize($_POST['tags'] ?? ''),
            'status'        => $_POST['status'] ?? 'active',
            'is_featured'   => !empty($_POST['is_featured']) ? 1 : 0,
            'is_new'        => !empty($_POST['is_new']) ? 1 : 0,
            'track_stock'   => !empty($_POST['track_stock']) ? 1 : 0,
            'meta_title'    => Helper::sanitize($_POST['meta_title'] ?? ''),
            'meta_desc'     => Helper::sanitize($_POST['meta_desc'] ?? ''),
            'created_at'    => date('Y-m-d H:i:s'),
        ]);

        // Initial stock
        $initQty = (int)($_POST['initial_stock'] ?? 0);
        if ($initQty > 0) {
            $this->db->insert('product_stock', [
                'product_id' => $pid, 'warehouse' => 'Main', 'quantity' => $initQty,
            ]);
            $this->db->insert('stock_movements', [
                'product_id'=>$pid,'type'=>'IN','quantity'=>$initQty,
                'quantity_before'=>0,'quantity_after'=>$initQty,
                'reference_type'=>'initial','created_by'=>Auth::id(),'created_at'=>date('Y-m-d H:i:s'),
            ]);
        }

        // Variations
        if (!empty($_POST['variations'])) {
            foreach ($_POST['variations'] as $var) {
                if (empty($var['name'])) continue;
                $varid = $this->db->insert('product_variations', [
                    'product_id' => $pid,
                    'name'       => Helper::sanitize($var['name']),
                    'sku'        => Helper::sanitize($var['sku'] ?? ''),
                    'price'      => (float)($var['price'] ?? 0) ?: null,
                    'sale_price' => (float)($var['sale_price'] ?? 0) ?: null,
                    'sort_order' => (int)($var['sort_order'] ?? 0),
                ]);
                if (!empty($var['stock']) && $varid) {
                    $this->db->insert('product_stock', [
                        'product_id'=>$pid,'variation_id'=>$varid,'warehouse'=>'Main','quantity'=>(int)$var['stock'],
                    ]);
                }
            }
        }

        // Additional images
        if (!empty($_FILES['images']['name'][0])) {
            $count = count($_FILES['images']['name']);
            for ($i = 0; $i < $count; $i++) {
                $singleFile = [
                    'name'     => $_FILES['images']['name'][$i],
                    'type'     => $_FILES['images']['type'][$i],
                    'tmp_name' => $_FILES['images']['tmp_name'][$i],
                    'error'    => $_FILES['images']['error'][$i],
                    'size'     => $_FILES['images']['size'][$i],
                ];
                $img = Helper::uploadImage($singleFile, 'products');
                if ($img) {
                    $this->db->insert('product_images', ['product_id'=>$pid,'image'=>$img,'sort_order'=>$i]);
                }
            }
        }

        // Payment methods
        $this->saveProductPm($pid, $_POST['payment_methods'] ?? []);

        $this->db->logActivity(Auth::id(), 'CREATE', 'products', $pid, null, ['name'=>$_POST['name']??'']);
        $this->json(['success'=>true,'message'=>'Product created.','redirect'=>url('admin/products')]);
    }

    public function edit(array $params): void {
        $this->ensurePmTable();
        $pageTitle  = 'Edit Product';
        $id         = (int)($params['id'] ?? 0);
        $product    = $this->db->selectOne("SELECT * FROM products WHERE id=$id");
        if (!$product) $this->redirect('admin/products');

        $product['images']     = $this->db->select("SELECT * FROM product_images WHERE product_id=$id ORDER BY sort_order") ?: [];
        $product['variations'] = $this->db->select("SELECT pv.*, COALESCE(ps.quantity,0) AS stock FROM product_variations pv LEFT JOIN product_stock ps ON ps.variation_id=pv.id AND ps.warehouse='Main' WHERE pv.product_id=$id ORDER BY pv.sort_order") ?: [];
        $product['stock']      = (int)($this->db->selectOne("SELECT quantity FROM product_stock WHERE product_id=$id AND variation_id IS NULL AND warehouse='Main'")['quantity'] ?? 0);

        $categories    = $this->buildCategoryTree();
        $brands        = $this->db->select("SELECT * FROM brands WHERE status=1 ORDER BY name") ?: [];
        $vendors       = $this->db->select("SELECT * FROM vendors WHERE status='active' ORDER BY company_name") ?: [];
        $attributes    = $this->db->select("SELECT * FROM product_attributes") ?: [];
        $allPm         = $this->db->select("SELECT * FROM payment_methods WHERE is_active=1 ORDER BY sort_order") ?: [];
        $activePm      = array_column(
            $this->db->select("SELECT method_code FROM product_payment_methods WHERE product_id=$id") ?: [],
            'method_code'
        );

        $this->view('products/form', compact('pageTitle','product','categories','brands','vendors','attributes','allPm','activePm'));
    }

    public function update(array $params): void {
        CSRF::check();
        $id = (int)($params['id'] ?? 0);

        $thumbnail = null;
        if (!empty($_FILES['thumbnail']['name'])) {
            if (($_FILES['thumbnail']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
                $errMsg = $_FILES['thumbnail']['error'] === UPLOAD_ERR_INI_SIZE
                    ? 'Thumbnail too large. Max allowed: 10MB.'
                    : 'Thumbnail upload failed (error code: ' . $_FILES['thumbnail']['error'] . ').';
                $this->json(['success' => false, 'message' => $errMsg]);
                return;
            }
            $thumbnail = Helper::uploadImage($_FILES['thumbnail'], 'products');
            if (!$thumbnail) {
                $this->json(['success' => false, 'message' => 'Thumbnail upload failed. Use JPG, PNG, GIF, or WebP under 10MB.']);
                return;
            }
        }

        // Handle slug update
        $rawSlug = Helper::sanitize($_POST['slug'] ?? '');
        $newSlug = $rawSlug ? Helper::slug($rawSlug) : Helper::slug($_POST['name'] ?? '');
        if ($newSlug) {
            $esc = $this->db->connect()->real_escape_string($newSlug);
            $slugConflict = $this->db->selectOne("SELECT id FROM products WHERE slug='$esc' AND id != $id");
            if ($slugConflict) $newSlug .= '-' . $id;
        }

        $upd = [
            'category_id' => (int)($_POST['category_id'] ?? 0),
            'brand_id'    => (int)($_POST['brand_id'] ?? 0) ?: null,
            'vendor_id'   => (int)($_POST['vendor_id'] ?? 0) ?: null,
            'name'        => Helper::sanitize($_POST['name'] ?? ''),
            'name_si'     => Helper::sanitize($_POST['name_si'] ?? ''),
            'name_ta'     => Helper::sanitize($_POST['name_ta'] ?? ''),
            'short_desc'  => Helper::sanitize($_POST['short_desc'] ?? ''),
            'short_desc_si'=> Helper::sanitize($_POST['short_desc_si'] ?? ''),
            'short_desc_ta'=> Helper::sanitize($_POST['short_desc_ta'] ?? ''),
            'description' => $_POST['description'] ?? '',
            'description_si'=> $_POST['description_si'] ?? '',
            'description_ta'=> $_POST['description_ta'] ?? '',
            'price'       => (float)($_POST['price'] ?? 0),
            'sale_price'  => (float)($_POST['sale_price'] ?? 0) ?: null,
            'cost_price'  => (float)($_POST['cost_price'] ?? 0),
            'tax_rate'    => (float)($_POST['tax_rate'] ?? 0),
            'weight'      => (float)($_POST['weight'] ?? 0),
            'tags'        => Helper::sanitize($_POST['tags'] ?? ''),
            'status'      => $_POST['status'] ?? 'active',
            'is_featured' => !empty($_POST['is_featured']) ? 1 : 0,
            'is_new'      => !empty($_POST['is_new']) ? 1 : 0,
            'track_stock' => !empty($_POST['track_stock']) ? 1 : 0,
            'slug'        => $newSlug,
            'meta_title'  => Helper::sanitize($_POST['meta_title'] ?? ''),
            'meta_desc'   => Helper::sanitize($_POST['meta_desc'] ?? ''),
            'updated_at'  => date('Y-m-d H:i:s'),
        ];
        if ($thumbnail) $upd['thumbnail'] = $thumbnail;

        $old = $this->db->selectOne("SELECT * FROM products WHERE id=$id");
        $this->db->update('products', $upd, "id=$id");

        // Payment methods
        $this->saveProductPm($id, $_POST['payment_methods'] ?? []);

        // Additional gallery images
        if (!empty($_FILES['images']['name'][0])) {
            $nextOrder = (int)($this->db->selectOne("SELECT COALESCE(MAX(sort_order),0)+1 AS next FROM product_images WHERE product_id=$id")['next'] ?? 0);
            $count = count($_FILES['images']['name']);
            for ($i = 0; $i < $count; $i++) {
                if (($_FILES['images']['error'][$i] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) continue;
                $singleFile = [
                    'name'     => $_FILES['images']['name'][$i],
                    'type'     => $_FILES['images']['type'][$i],
                    'tmp_name' => $_FILES['images']['tmp_name'][$i],
                    'error'    => $_FILES['images']['error'][$i],
                    'size'     => $_FILES['images']['size'][$i],
                ];
                $img = Helper::uploadImage($singleFile, 'products');
                if ($img) {
                    $this->db->insert('product_images', ['product_id' => $id, 'image' => $img, 'sort_order' => $nextOrder + $i]);
                }
            }
        }

        // ── Variations ──────────────────────────────────────────
        $postedVars   = is_array($_POST['variations'] ?? null) ? $_POST['variations'] : [];
        $submittedIds = [];

        foreach ($postedVars as $var) {
            if (empty(trim($var['name'] ?? ''))) continue;

            $varId  = (int)($var['id']    ?? 0);
            $vName  = Helper::sanitize($var['name']);
            $vSku   = Helper::sanitize($var['sku']   ?? '');
            $vPrice = (float)($var['price'] ?? 0) ?: null;
            $vStock = max(0, (int)($var['stock'] ?? 0));

            if ($varId > 0) {
                $this->db->update('product_variations',
                    ['name' => $vName, 'sku' => $vSku, 'price' => $vPrice],
                    "id=$varId AND product_id=$id"
                );
                $hasStock = $this->db->selectOne(
                    "SELECT id FROM product_stock WHERE product_id=$id AND variation_id=$varId AND warehouse='Main'"
                );
                if ($hasStock) {
                    $this->db->update('product_stock', ['quantity' => $vStock],
                        "product_id=$id AND variation_id=$varId AND warehouse='Main'");
                } else {
                    $this->db->insert('product_stock',
                        ['product_id'=>$id,'variation_id'=>$varId,'warehouse'=>'Main','quantity'=>$vStock]);
                }
                $submittedIds[] = $varId;
            } else {
                $newId = $this->db->insert('product_variations', [
                    'product_id' => $id,
                    'name'       => $vName,
                    'sku'        => $vSku,
                    'price'      => $vPrice,
                    'status'     => 1,
                    'sort_order' => 0,
                ]);
                if ($newId) {
                    $this->db->insert('product_stock',
                        ['product_id'=>$id,'variation_id'=>$newId,'warehouse'=>'Main','quantity'=>$vStock]);
                    $submittedIds[] = $newId;
                }
            }
        }

        // Delete variations removed from the form
        if (!empty($submittedIds)) {
            $keep = implode(',', $submittedIds);
            $this->db->query("DELETE FROM product_stock WHERE product_id=$id AND variation_id IS NOT NULL AND variation_id NOT IN ($keep)");
            $this->db->query("DELETE FROM product_variations WHERE product_id=$id AND id NOT IN ($keep)");
        } else {
            $this->db->query("DELETE FROM product_stock WHERE product_id=$id AND variation_id IS NOT NULL");
            $this->db->query("DELETE FROM product_variations WHERE product_id=$id");
        }
        // ────────────────────────────────────────────────────────

        $this->db->logActivity(Auth::id(), 'UPDATE', 'products', $id, $old, $upd);
        $this->json(['success'=>true,'message'=>'Product updated.','redirect'=>url('admin/products')]);
    }

    public function delete(array $p = []): void {
        CSRF::check();
        $id = (int)($_POST['id'] ?? 0);
        $this->db->update('products', ['status'=>'deleted'], "id=$id");
        $this->db->logActivity(Auth::id(), 'DELETE', 'products', $id);
        $this->json(['success'=>true,'message'=>'Product removed.']);
    }

    // AI description generator using Anthropic API
    public function aiDescription(array $p = []): void {
        CSRF::check();
        $name     = Helper::sanitize($_POST['name'] ?? '');
        $category = Helper::sanitize($_POST['category'] ?? '');
        $keywords = Helper::sanitize($_POST['keywords'] ?? '');
        $lang     = $_POST['lang'] ?? 'en';

        if (!$name) { $this->json(['success'=>false,'message'=>'Product name required.']); }

        $langMap  = ['en'=>'English','si'=>'Sinhala','ta'=>'Tamil'];
        $langName = $langMap[$lang] ?? 'English';

        $prompt = "Write a compelling e-commerce product description in {$langName} for: '{$name}'.";
        if ($category) $prompt .= " Category: {$category}.";
        if ($keywords) $prompt .= " Keywords: {$keywords}.";
        $prompt .= " Write 2-3 engaging paragraphs. Focus on benefits, features, and why customers should buy it. Be persuasive and natural.";

        // Call Anthropic API
        $config  = parse_ini_file(ROOT . '/config.ini');
        $apiKey  = $config['anthropic_api_key'] ?? '';
        if (!$apiKey) { $this->json(['success'=>false,'message'=>'Anthropic API key not set in config.ini']); }
        $payload = json_encode([
            'model'      => 'claude-haiku-4-5-20251001',
            'max_tokens' => 600,
            'messages'   => [['role'=>'user','content'=>$prompt]],
        ]);

        $ch = curl_init('https://api.anthropic.com/v1/messages');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'x-api-key: '          . $apiKey,
                'anthropic-version: 2023-06-01',
            ],
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err      = curl_error($ch);
        curl_close($ch);

        if ($err) {
            $this->json(['success'=>false,'message'=>"cURL error: $err"]);
        }

        $data = json_decode($response, true);

        if ($httpCode !== 200) {
            $errMsg = $data['error']['message'] ?? "API returned HTTP $httpCode";
            $this->json(['success'=>false,'message'=>"Anthropic API error: $errMsg"]);
        }

        $text = $data['content'][0]['text'] ?? null;

        if (!$text) {
            $this->json(['success'=>false,'message'=>'No description returned. Check your API key and quota.']);
        }

        $this->json(['success'=>true,'description'=>$text]);
    }


    // ── Delete product image ──────────────────────────────────────
    public function deleteImage(array $p = []): void {
        CSRF::check();
        $id        = (int)($_POST['image_id'] ?? 0);
        $productId = (int)($_POST['product_id'] ?? 0);
        $row = $this->db->selectOne("SELECT image FROM product_images WHERE id=$id AND product_id=$productId");
        if ($row) {
            $path = ROOT . '/uploads/products/' . $row['image'];
            if (file_exists($path)) @unlink($path);
            $this->db->delete('product_images', "id=$id");
        }
        $this->json(['success'=>true,'message'=>'Image deleted.']);
    }

    // ── Category tree ─────────────────────────────────────────────────

    private function buildCategoryTree(): array {
        $all = $this->db->select("SELECT id, name, parent_id FROM categories ORDER BY name") ?: [];
        if (empty($all)) return [];

        // Group rows by parent_id (0 = root)
        $byParent = [];
        foreach ($all as $c) {
            $byParent[(int)($c['parent_id'] ?: 0)][] = $c;
        }

        // Recursively flatten with depth
        $flat = [];
        $walk = function (int $parentId, int $depth) use (&$walk, &$flat, $byParent): void {
            foreach ($byParent[$parentId] ?? [] as $cat) {
                $flat[] = [
                    'id'        => $cat['id'],
                    'name'      => $cat['name'],
                    'parent_id' => $cat['parent_id'],
                    'depth'     => $depth,
                ];
                $walk((int)$cat['id'], $depth + 1);
            }
        };
        $walk(0, 0);
        return $flat;
    }

    // ── Payment methods helpers ───────────────────────────────────────

    private function ensurePmTable(): void {
        $this->db->connect()->query("
            CREATE TABLE IF NOT EXISTS product_payment_methods (
                product_id  INT UNSIGNED NOT NULL,
                method_code VARCHAR(50)  NOT NULL,
                PRIMARY KEY (product_id, method_code),
                KEY idx_product (product_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
    }

    private function saveProductPm(int $productId, array $selected): void {
        $conn = $this->db->connect();
        $conn->query("DELETE FROM product_payment_methods WHERE product_id=$productId");
        foreach ($selected as $code) {
            $code = trim($code);
            if ($code === '') continue;
            $c = $conn->real_escape_string($code);
            $conn->query("INSERT IGNORE INTO product_payment_methods (product_id, method_code) VALUES ($productId, '$c')");
        }
    }
}
