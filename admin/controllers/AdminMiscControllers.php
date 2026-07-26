<?php
require_once ADMIN . '/AdminController.php';

// ============================================================
// AdminReviewController
// ============================================================
class AdminReviewController extends AdminController {
    public function index(array $p = []): void {
        $pageTitle = 'Reviews';
        $db        = $this->db->connect();
        $status    = $db->real_escape_string($_GET['status'] ?? 'pending');
        $search    = $db->real_escape_string(trim($_GET['search'] ?? ''));
        $perPage   = in_array((int)($_GET['per_page'] ?? 25), [10,25,50,100]) ? (int)($_GET['per_page'] ?? 25) : 25;
        $page      = max(1, (int)($_GET['page'] ?? 1));
        $offset    = ($page - 1) * $perPage;

        $where = "r.status='$status'";
        if ($search) $where .= " AND (p.name LIKE '%$search%' OR u.full_name LIKE '%$search%')";

        $total = (int)($this->db->selectOne(
            "SELECT COUNT(*) AS cnt FROM product_reviews r
             JOIN users u ON u.id=r.user_id
             JOIN products p ON p.id=r.product_id
             WHERE $where"
        )['cnt'] ?? 0);

        $reviews = $this->db->select(
            "SELECT r.*, u.full_name, p.name AS product_name, p.slug
             FROM product_reviews r
             JOIN users u ON u.id=r.user_id
             JOIN products p ON p.id=r.product_id
             WHERE $where
             ORDER BY r.created_at DESC
             LIMIT $perPage OFFSET $offset"
        ) ?: [];

        $pagData = Helper::paginate($total, $perPage, $page);
        $this->view('reviews/index', compact('pageTitle','reviews','status','search','perPage','pagData','total'));
    }
    public function updateStatus(array $p = []): void {
        CSRF::check();
        $id     = (int)($_POST['id'] ?? 0);
        $status = in_array($_POST['status']??'', ['approved','rejected']) ? $_POST['status'] : 'pending';
        $this->db->update('product_reviews', ['status'=>$status], "id=$id");
        // Recompute product rating
        $rev = $this->db->selectOne("SELECT product_id FROM product_reviews WHERE id=$id");
        if ($rev) {
            $pid  = $rev['product_id'];
            $avg  = $this->db->selectOne("SELECT AVG(rating) AS a, COUNT(*) AS c FROM product_reviews WHERE product_id=$pid AND status='approved'");
            $this->db->update('products', [
                'rating_avg'  => round($avg['a'] ?? 0, 2),
                'rating_count'=> (int)($avg['c'] ?? 0),
            ], "id=$pid");
        }
        $this->json(['success'=>true,'message'=>"Review $status."]);
    }
}

// ============================================================
// AdminUserController
// ============================================================
class AdminUserController extends AdminController {
    public function index(array $p = []): void {
        $pageTitle = 'Customers';
        $search    = Helper::sanitize($_GET['search'] ?? '');
        $cond      = "u.role='customer'";
        if ($search) {
            $sq    = $this->db->connect()->real_escape_string($search);
            $cond .= " AND (u.full_name LIKE '%$sq%' OR u.email LIKE '%$sq%' OR u.phone LIKE '%$sq%')";
        }

        $perPage = 50;
        $page    = max(1, (int)($_GET['page'] ?? 1));
        $offset  = ($page - 1) * $perPage;

        $total   = (int)($this->db->selectOne(
            "SELECT COUNT(*) AS cnt FROM users u WHERE $cond"
        )['cnt'] ?? 0);

        $users = $this->db->select(
            "SELECT u.*,
                    (SELECT COUNT(*) FROM orders WHERE user_id=u.id) AS order_count,
                    (SELECT SUM(total_amount) FROM orders WHERE user_id=u.id AND status NOT IN ('cancelled','refunded')) AS total_spent
             FROM users u WHERE $cond
             ORDER BY u.created_at DESC
             LIMIT $perPage OFFSET $offset"
        ) ?: [];

        $pagData = Helper::paginate($total, $perPage, $page);
        $this->view('users/index', compact('pageTitle','users','search','pagData','total'));
    }
    public function toggleStatus(array $p = []): void {
        CSRF::check();
        $id  = (int)($_POST['id'] ?? 0);
        $cur = $this->db->selectOne("SELECT status FROM users WHERE id=$id");
        $new = ($cur['status'] === 'active') ? 'inactive' : 'active';
        $this->db->update('users', ['status'=>$new], "id=$id AND role!='admin'");
        $this->json(['success'=>true,'message'=>"User $new.",'status'=>$new]);
    }
}

// ============================================================
// AdminVendorController
// ============================================================
class AdminVendorController extends AdminController {
    public function index(array $p = []): void {
        $pageTitle = 'Vendors & Suppliers';
        $db        = $this->db->connect();
        $search    = $db->real_escape_string(trim($_GET['search'] ?? ''));
        $perPage   = in_array((int)($_GET['per_page'] ?? 25), [10,25,50,100]) ? (int)($_GET['per_page'] ?? 25) : 25;
        $page      = max(1, (int)($_GET['page'] ?? 1));
        $offset    = ($page - 1) * $perPage;

        $where = '1=1';
        if ($search) $where = "(v.company_name LIKE '%$search%' OR v.contact_name LIKE '%$search%' OR v.email LIKE '%$search%')";

        $total = (int)($this->db->selectOne("SELECT COUNT(*) AS cnt FROM vendors v WHERE $where")['cnt'] ?? 0);

        $vendors = $this->db->select(
            "SELECT v.*,
                    (SELECT COUNT(*) FROM products WHERE vendor_id=v.id) AS product_count,
                    (SELECT COUNT(*) FROM purchase_orders WHERE vendor_id=v.id) AS po_count
             FROM vendors v WHERE $where
             ORDER BY v.company_name
             LIMIT $perPage OFFSET $offset"
        ) ?: [];

        $pagData = Helper::paginate($total, $perPage, $page);
        $this->view('vendors/index', compact('pageTitle','vendors','search','perPage','pagData','total'));
    }
    public function store(array $p = []): void {
        CSRF::check();
        $logo = null;
        if (!empty($_FILES['logo']['name'])) $logo = Helper::uploadImage($_FILES['logo'], 'vendors');
        $id = $this->db->insert('vendors', [
            'company_name' => Helper::sanitize($_POST['company_name'] ?? ''),
            'contact_name' => Helper::sanitize($_POST['contact_name'] ?? ''),
            'email'        => Helper::sanitize($_POST['email'] ?? ''),
            'phone'        => Helper::sanitize($_POST['phone'] ?? ''),
            'address'      => Helper::sanitize($_POST['address'] ?? ''),
            'type'         => $_POST['type'] ?? 'vendor',
            'status'       => 'active',
            'logo'         => $logo,
            'notes'        => Helper::sanitize($_POST['notes'] ?? ''),
            'created_at'   => date('Y-m-d H:i:s'),
        ]);
        $this->db->logActivity(Auth::id(), 'CREATE', 'vendors', $id);
        $this->json(['success'=>true,'message'=>'Vendor saved.']);
    }
    public function update(array $p = []): void {
        CSRF::check();
        $id = (int)($_POST['id'] ?? 0);
        $this->db->update('vendors', [
            'company_name' => Helper::sanitize($_POST['company_name'] ?? ''),
            'contact_name' => Helper::sanitize($_POST['contact_name'] ?? ''),
            'email'        => Helper::sanitize($_POST['email'] ?? ''),
            'phone'        => Helper::sanitize($_POST['phone'] ?? ''),
            'address'      => Helper::sanitize($_POST['address'] ?? ''),
            'status'       => $_POST['status'] ?? 'active',
            'notes'        => Helper::sanitize($_POST['notes'] ?? ''),
        ], "id=$id");
        $this->json(['success'=>true,'message'=>'Vendor updated.']);
    }
    public function delete(array $p = []): void {
        CSRF::check();
        $id = (int)($_POST['id'] ?? 0);
        $this->db->update('vendors', ['status'=>'inactive'], "id=$id");
        $this->json(['success'=>true,'message'=>'Vendor deactivated.']);
    }
}

// ============================================================
// AdminCouponController — unified discount management
// ============================================================
class AdminCouponController extends AdminController {

    public function index(array $p = []): void {
        $pageTitle       = 'Discounts & Coupons';
        $coupons         = $this->db->select("SELECT * FROM coupons ORDER BY created_at DESC") ?: [];
        $productDiscounts= $this->db->select(
            "SELECT pd.*, p.name AS product_name FROM product_discounts pd
             LEFT JOIN products p ON p.id=pd.product_id ORDER BY pd.id DESC"
        ) ?: [];
        $globalDiscounts = $this->db->select("SELECT * FROM global_discounts ORDER BY priority DESC, id DESC") ?: [];
        $bogoOffers      = $this->db->select(
            "SELECT bo.*,
                    bp.name AS buy_product_name,
                    gp.name AS get_product_name
             FROM bogo_offers bo
             LEFT JOIN products bp ON bp.id=bo.buy_product_id
             LEFT JOIN products gp ON gp.id=bo.get_product_id
             ORDER BY bo.id DESC"
        ) ?: [];
        $products = $this->db->select("SELECT id, name FROM products WHERE status='active' ORDER BY name") ?: [];
        $this->view('promotions/coupons', compact(
            'pageTitle','coupons','productDiscounts','globalDiscounts','bogoOffers','products'
        ));
    }

    // ── Coupon CRUD ───────────────────────────────────────────
    public function couponStore(array $p = []): void {
        CSRF::check();
        $code = strtoupper(Helper::sanitize($_POST['code'] ?? ''));
        if (!$code) $this->json(['success'=>false,'message'=>'Code is required.']);
        if ($this->db->selectOne("SELECT id FROM coupons WHERE code='$code'"))
            $this->json(['success'=>false,'message'=>'Coupon code already exists.']);
        $fields = $this->couponFields();
        $fields['status']     = (int)($_POST['status'] ?? 1);
        $fields['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert('coupons', $fields);
        $this->json(['success'=>true,'message'=>'Coupon created.']);
    }

    public function couponUpdate(array $p = []): void {
        CSRF::check();
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) $this->json(['success'=>false,'message'=>'Invalid ID.']);
        $fields = $this->couponFields();
        $fields['status'] = (int)($_POST['status'] ?? 1);
        $this->db->update('coupons', $fields, "id=$id");
        $this->json(['success'=>true,'message'=>'Coupon updated.']);
    }

    public function couponDelete(array $p = []): void {
        CSRF::check();
        $id = (int)($_POST['id'] ?? 0);
        $this->db->delete('coupons', "id=$id");
        $this->json(['success'=>true,'message'=>'Coupon deleted.']);
    }

    private function couponFields(): array {
        return [
            'code'             => strtoupper(Helper::sanitize($_POST['code'] ?? '')),
            'type'             => in_array($_POST['type']??'',['percentage','fixed']) ? $_POST['type'] : 'percentage',
            'value'            => max(0,(float)($_POST['value'] ?? 0)),
            'min_order'        => max(0,(float)($_POST['min_order'] ?? 0)),
            'max_discount'     => (float)($_POST['max_discount'] ?? 0) ?: null,
            'usage_limit'      => (int)($_POST['usage_limit'] ?? 0) ?: null,
            'per_user_limit'   => max(1,(int)($_POST['per_user_limit'] ?? 1)),
            'excluded_methods' => Helper::sanitize($_POST['excluded_methods'] ?? ''),
            'start_date'       => $_POST['start_date'] ?: null,
            'end_date'         => $_POST['end_date']   ?: null,
        ];
    }

    // ── Product discount CRUD ─────────────────────────────────
    public function productStore(array $p = []): void {
        CSRF::check();
        $pid = (int)($_POST['product_id'] ?? 0);
        if (!$pid) $this->json(['success'=>false,'message'=>'Product is required.']);
        // Conflict check: active discount already exists for this product?
        $existing = $this->db->selectOne(
            "SELECT id FROM product_discounts WHERE product_id=$pid AND status=1
             AND (end_date IS NULL OR end_date >= NOW())"
        );
        if ($existing) $this->json(['success'=>false,'message'=>'An active discount already exists for this product. Edit or delete it first.']);
        $fields = $this->productFields();
        $fields['status'] = (int)($_POST['status'] ?? 1);
        $this->db->insert('product_discounts', $fields);
        $this->json(['success'=>true,'message'=>'Product discount created.']);
    }

    public function productUpdate(array $p = []): void {
        CSRF::check();
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) $this->json(['success'=>false,'message'=>'Invalid ID.']);
        $fields = $this->productFields();
        $fields['status'] = (int)($_POST['status'] ?? 1);
        $this->db->update('product_discounts', $fields, "id=$id");
        $this->json(['success'=>true,'message'=>'Product discount updated.']);
    }

    public function productDelete(array $p = []): void {
        CSRF::check();
        $id = (int)($_POST['id'] ?? 0);
        $this->db->delete('product_discounts', "id=$id");
        $this->json(['success'=>true,'message'=>'Product discount deleted.']);
    }

    private function productFields(): array {
        return [
            'name'             => Helper::sanitize($_POST['name'] ?? ''),
            'product_id'       => (int)($_POST['product_id'] ?? 0),
            'type'             => in_array($_POST['type']??'',['percentage','fixed']) ? $_POST['type'] : 'percentage',
            'value'            => max(0,(float)($_POST['value'] ?? 0)),
            'excluded_methods' => Helper::sanitize($_POST['excluded_methods'] ?? 'koko,payzy,mintpay'),
            'start_date'       => $_POST['start_date'] ?: null,
            'end_date'         => $_POST['end_date']   ?: null,
        ];
    }

    // ── Global discount CRUD ──────────────────────────────────
    public function globalStore(array $p = []): void {
        CSRF::check();
        $name = Helper::sanitize($_POST['name'] ?? '');
        if (!$name) $this->json(['success'=>false,'message'=>'Name is required.']);
        $fields = $this->globalFields();
        $fields['status'] = (int)($_POST['status'] ?? 1);
        $this->db->insert('global_discounts', $fields);
        $this->json(['success'=>true,'message'=>'Global discount created.']);
    }

    public function globalUpdate(array $p = []): void {
        CSRF::check();
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) $this->json(['success'=>false,'message'=>'Invalid ID.']);
        $fields = $this->globalFields();
        $fields['status'] = (int)($_POST['status'] ?? 1);
        $this->db->update('global_discounts', $fields, "id=$id");
        $this->json(['success'=>true,'message'=>'Global discount updated.']);
    }

    public function globalDelete(array $p = []): void {
        CSRF::check();
        $id = (int)($_POST['id'] ?? 0);
        $this->db->delete('global_discounts', "id=$id");
        $this->json(['success'=>true,'message'=>'Global discount deleted.']);
    }

    private function globalFields(): array {
        return [
            'name'             => Helper::sanitize($_POST['name'] ?? ''),
            'type'             => in_array($_POST['type']??'',['percentage','fixed']) ? $_POST['type'] : 'percentage',
            'value'            => max(0,(float)($_POST['value'] ?? 0)),
            'min_order'        => max(0,(float)($_POST['min_order'] ?? 0)),
            'max_discount'     => (float)($_POST['max_discount'] ?? 0) ?: null,
            'excluded_methods' => Helper::sanitize($_POST['excluded_methods'] ?? 'koko,payzy,mintpay'),
            'priority'         => (int)($_POST['priority'] ?? 0),
            'start_date'       => $_POST['start_date'] ?: null,
            'end_date'         => $_POST['end_date']   ?: null,
        ];
    }

    // ── BOGO CRUD ─────────────────────────────────────────────
    public function bogoStore(array $p = []): void {
        CSRF::check();
        $name = Helper::sanitize($_POST['name'] ?? '');
        if (!$name) $this->json(['success'=>false,'message'=>'Name is required.']);
        $fields = $this->bogoFields();
        $fields['status'] = (int)($_POST['status'] ?? 1);
        $this->db->insert('bogo_offers', $fields);
        $this->json(['success'=>true,'message'=>'BOGO offer created.']);
    }

    public function bogoUpdate(array $p = []): void {
        CSRF::check();
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) $this->json(['success'=>false,'message'=>'Invalid ID.']);
        $fields = $this->bogoFields();
        $fields['status'] = (int)($_POST['status'] ?? 1);
        $this->db->update('bogo_offers', $fields, "id=$id");
        $this->json(['success'=>true,'message'=>'BOGO offer updated.']);
    }

    public function bogoDelete(array $p = []): void {
        CSRF::check();
        $id = (int)($_POST['id'] ?? 0);
        $this->db->delete('bogo_offers', "id=$id");
        $this->json(['success'=>true,'message'=>'BOGO offer deleted.']);
    }

    private function bogoFields(): array {
        $buyPid = (int)($_POST['buy_product_id'] ?? 0) ?: null;
        $getPid = (int)($_POST['get_product_id'] ?? 0) ?: null;
        return [
            'name'             => Helper::sanitize($_POST['name'] ?? ''),
            'buy_product_id'   => $buyPid,
            'buy_qty'          => max(1,(int)($_POST['buy_qty'] ?? 1)),
            'get_product_id'   => $getPid,
            'get_qty'          => max(1,(int)($_POST['get_qty'] ?? 1)),
            'get_discount_pct' => min(100,max(0,(float)($_POST['get_discount_pct'] ?? 100))),
            'excluded_methods' => Helper::sanitize($_POST['excluded_methods'] ?? 'koko,payzy,mintpay'),
            'start_date'       => $_POST['start_date'] ?: null,
            'end_date'         => $_POST['end_date']   ?: null,
        ];
    }
}

// ============================================================
// AdminBannerController
// ============================================================
class AdminBannerController extends AdminController {
    private array $allowedPositions = ['hero','home_mid','home_promo'];
    private array $allowedDescPos   = ['top-left','top-center','top-right','middle-left','middle-center','middle-right','bottom-left','bottom-center','bottom-right'];

    public function index(array $p = []): void {
        $pageTitle = 'Banners';
        $banners   = $this->db->select("SELECT * FROM banners ORDER BY sort_order, id") ?: [];
        $this->view('promotions/banners', compact('pageTitle','banners'));
    }

    private function bannerFields(bool $isNew = true): array {
        $descPos = in_array($_POST['desc_position'] ?? '', $this->allowedDescPos)
            ? $_POST['desc_position'] : 'middle-left';
        $pos = in_array($_POST['position'] ?? '', $this->allowedPositions)
            ? $_POST['position'] : 'hero';
        $fields = [
            'title'        => Helper::sanitize($_POST['title']       ?? ''),
            'subtitle'     => Helper::sanitize($_POST['subtitle']    ?? ''),
            'description'  => Helper::sanitize($_POST['description'] ?? ''),
            'desc_position'=> $descPos,
            'text_color'   => in_array($_POST['text_color']??'light',['light','dark']) ? $_POST['text_color'] : 'light',
            'link'         => Helper::sanitize($_POST['link']        ?? ''),
            'position'     => $pos,
            'sort_order'   => (int)($_POST['sort_order'] ?? 0),
            'status'       => (int)($_POST['status']     ?? 1),
            'start_date'   => $_POST['start_date'] ?: null,
            'end_date'     => $_POST['end_date']   ?: null,
        ];
        return $fields;
    }

    public function store(array $p = []): void {
        CSRF::check();
        if (empty($_FILES['image']['name'])) {
            $this->json(['success'=>false,'message'=>'An image is required.']);
        }
        $img = Helper::uploadImage($_FILES['image'], 'banners');
        if (!$img) {
            $this->json(['success'=>false,'message'=>'Image upload failed. Use JPEG, PNG, GIF or WebP under the server size limit.']);
        }
        $newId = $this->db->insert('banners', array_merge($this->bannerFields(true), ['image'=>$img]));
        if (!$newId) {
            @unlink(ROOT . '/uploads/banners/' . $img);
            $this->json(['success'=>false,'message'=>'Database error — banner not saved. ' . $this->db->error()]);
        }
        $this->json(['success'=>true,'message'=>'Banner added.']);
    }

    public function update(array $p = []): void {
        CSRF::check();
        $id     = (int)($_POST['id'] ?? 0);
        $banner = $this->db->selectOne("SELECT * FROM banners WHERE id=$id");
        if (!$banner) $this->json(['success'=>false,'message'=>'Banner not found.']);
        $fields = $this->bannerFields(false);
        if (!empty($_FILES['image']['name'])) {
            $img = Helper::uploadImage($_FILES['image'], 'banners');
            if (!$img) {
                $this->json(['success'=>false,'message'=>'Image upload failed. Use JPEG, PNG, GIF or WebP under the server size limit.']);
            }
            $fields['image'] = $img;
            $old = ROOT . '/uploads/banners/' . $banner['image'];
            if (file_exists($old)) @unlink($old);
        }
        $result = $this->db->update('banners', $fields, "id=$id");
        if ($result === false) {
            $this->json(['success'=>false,'message'=>'Database error — banner not updated. ' . $this->db->error()]);
        }
        $this->json(['success'=>true,'message'=>'Banner updated.']);
    }

    public function toggle(array $p = []): void {
        CSRF::check();
        $id  = (int)($_POST['id'] ?? 0);
        $cur = $this->db->selectOne("SELECT status FROM banners WHERE id=$id");
        if (!$cur) $this->json(['success'=>false,'message'=>'Not found.']);
        $new = $cur['status'] ? 0 : 1;
        $this->db->update('banners', ['status'=>$new], "id=$id");
        $this->json(['success'=>true,'status'=>$new,'message'=>$new ? 'Banner activated.' : 'Banner deactivated.']);
    }

    public function delete(array $p = []): void {
        CSRF::check();
        $id     = (int)($_POST['id'] ?? 0);
        $banner = $this->db->selectOne("SELECT image FROM banners WHERE id=$id");
        if ($banner && $banner['image']) {
            $old = ROOT . '/uploads/banners/' . $banner['image'];
            if (file_exists($old)) @unlink($old);
        }
        $this->db->delete('banners', "id=$id");
        $this->json(['success'=>true,'message'=>'Banner deleted.']);
    }
}

// ============================================================
// AdminSettingsController
// ============================================================
class AdminSettingsController extends AdminController { /* Settings - super_admin only for sensitive ops */
    public function index(array $p = []): void {
        $pageTitle = 'Settings';
        $config    = parse_ini_file(ROOT . '/config.ini') ?: [];
        // Pull DB settings to pre-fill form
        $dbSettings = [];
        $rows = $this->db->select("SELECT `key`,`value` FROM settings");
        foreach ($rows ?: [] as $r) $dbSettings[$r['key']] = $r['value'];
        $this->view('settings/index', compact('pageTitle','config','dbSettings'));
    }
    public function save(array $p = []): void {
        CSRF::check();
        $db = $this->db->connect();

        // ── 1. Save text fields to DB settings table ───────────
        $dbKeys = ['site_name','site_tagline','site_address','currency','site_website','bank_name','bank_account','bank_branch','invoice_footer_note'];
        foreach ($dbKeys as $k) {
            $val = Helper::sanitize($_POST[$k] ?? '');
            $esc = $db->real_escape_string($val);
            if (!$db->query("INSERT INTO settings (`key`,`value`,`group`) VALUES ('$k','$esc','general')
                             ON DUPLICATE KEY UPDATE `value`='$esc'")) {
                $this->json(['success'=>false,'message'=>'DB error saving '.$k.': '.$db->error]);
            }
        }

        // ── 2. Logo upload ─────────────────────────────────────
        if (!empty($_FILES['site_logo']['name']) && $_FILES['site_logo']['error'] === UPLOAD_ERR_OK) {
            $filename = Helper::uploadImage($_FILES['site_logo'], 'logo');
            if ($filename) {
                // Delete old logo file
                $old = $db->query("SELECT `value` FROM settings WHERE `key`='site_logo'");
                if ($old && ($row = $old->fetch_assoc()) && $row['value']) {
                    $oldFile = ROOT . '/uploads/logo/' . $row['value'];
                    if (file_exists($oldFile)) @unlink($oldFile);
                }
                $esc = $db->real_escape_string($filename);
                $db->query("INSERT INTO settings (`key`,`value`,`group`) VALUES ('site_logo','$esc','general')
                            ON DUPLICATE KEY UPDATE `value`='$esc'");
            } else {
                $this->json(['success'=>false,'message'=>'Logo upload failed. Use JPEG, PNG, GIF or WebP.']);
            }
        }

        // ── 3. config.ini (mirrors key fields; keeps app_url + api key) ──
        $configFile = ROOT . '/config.ini';
        if (is_writable($configFile)) {
            if (isset($_POST['site_name'])) $_POST['app_name'] = $_POST['site_name'];
            $iniAllowed = ['app_name','app_url','support_email','support_phone','currency','whatsapp_number','anthropic_api_key'];
            $lines   = file($configFile, FILE_IGNORE_NEW_LINES) ?: [];
            $updated = []; $written = [];
            foreach ($lines as $line) {
                $t = trim($line);
                if ($t === '' || in_array($t[0], [';','#','['])) { $updated[] = $line; continue; }
                if (preg_match('/^([a-zA-Z_][a-zA-Z0-9_]*)\s*=/', $line, $m)) {
                    $key = $m[1];
                    if (in_array($key, $iniAllowed) && isset($_POST[$key])) {
                        $updated[] = $key . ' = "' . addslashes(Helper::sanitize($_POST[$key])) . '"';
                        $written[] = $key; continue;
                    }
                }
                $updated[] = $line;
            }
            foreach ($iniAllowed as $key) {
                if (!in_array($key, $written) && isset($_POST[$key])) {
                    $updated[] = $key . ' = "' . addslashes(Helper::sanitize($_POST[$key])) . '"';
                }
            }
            file_put_contents($configFile, implode(PHP_EOL, $updated) . PHP_EOL);
        }

        $this->json(['success'=>true,'message'=>'Settings saved successfully.']);
    }

    public function changePassword(array $p = []): void {
        CSRF::check();
        $uid     = Auth::id();
        $current = $_POST['current_password'] ?? '';
        $new     = $_POST['new_password']     ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        if (strlen($new) < 8) {
            $this->json(['success'=>false,'message'=>'New password must be at least 8 characters.']);
        }
        if ($new !== $confirm) {
            $this->json(['success'=>false,'message'=>'New passwords do not match.']);
        }

        $user = $this->db->selectOne("SELECT password FROM users WHERE id=$uid");
        if (!$user || !Helper::verifyPassword($current, $user['password'])) {
            $this->json(['success'=>false,'message'=>'Current password is incorrect.']);
        }

        $this->db->update('users', ['password' => Helper::hashPassword($new)], "id=$uid");
        $this->json(['success'=>true,'message'=>'Password changed successfully.']);
    }
}

// ============================================================
// AdminOrderController (extended)
// ============================================================
class AdminOrderController extends AdminController {
    public function index(array $p = []): void {
        $pageTitle = 'Orders';
        $status    = $this->db->connect()->real_escape_string($_GET['status'] ?? '');
        $search    = Helper::sanitize($_GET['search'] ?? '');
        $cond      = "WHERE 1=1";
        if ($status) $cond .= " AND o.status='$status'";
        if ($search) {
            $sq    = $this->db->connect()->real_escape_string($search);
            $cond .= " AND (o.order_number LIKE '%$sq%' OR o.shipping_name LIKE '%$sq%'"
                   . " OR o.shipping_phone LIKE '%$sq%' OR o.guest_email LIKE '%$sq%'"
                   . " OR u.full_name LIKE '%$sq%' OR u.email LIKE '%$sq%')";
        }

        $perPage = in_array((int)($_GET['per_page'] ?? 25), [10,25,50,100]) ? (int)($_GET['per_page'] ?? 25) : 25;
        $page    = max(1, (int)($_GET['page'] ?? 1));
        $offset  = ($page - 1) * $perPage;

        $total   = (int)($this->db->selectOne(
            "SELECT COUNT(DISTINCT o.id) AS cnt
             FROM orders o LEFT JOIN users u ON u.id=o.user_id $cond"
        )['cnt'] ?? 0);

        $orders = $this->db->select(
            "SELECT o.*, u.full_name, u.email AS user_email, u.phone AS user_phone,
                    (SELECT COUNT(*) FROM order_items WHERE order_id=o.id) AS item_count
             FROM orders o LEFT JOIN users u ON u.id=o.user_id
             $cond ORDER BY o.created_at DESC
             LIMIT $perPage OFFSET $offset"
        ) ?: [];

        $pagData  = Helper::paginate($total, $perPage, $page);
        $statuses = ['pending','confirmed','processing','shipped','delivered','cancelled','refunded'];
        $this->view('orders/index', compact('pageTitle','orders','status','statuses','search','pagData','total','perPage'));
    }
    public function show(array $params): void {
        $pageTitle       = 'Order Details';
        $id              = (int)($params['id'] ?? 0);
        require_once APP . '/models/OrderModel.php';
        $order           = (new OrderModel())->getById($id);
        if (!$order) $this->redirect('admin/orders');
        $statuses        = ['pending','confirmed','processing','shipped','delivered','cancelled','refunded'];
        $shippingMethods = $this->db->select("SELECT * FROM shipping_methods WHERE status=1 ORDER BY price") ?: [];
        $this->view('orders/view', compact('pageTitle','order','statuses','shippingMethods'));
    }

    public function updateStatus(array $p = []): void {
        CSRF::check();
        $id       = (int)($_POST['order_id'] ?? 0);
        $status   = Helper::sanitize($_POST['status'] ?? '');
        $notes    = Helper::sanitize($_POST['notes']  ?? '');
        $tracking = Helper::sanitize($_POST['tracking_number'] ?? '');
        $valid    = ['pending','confirmed','processing','shipped','delivered','cancelled','refunded'];
        if (!in_array($status, $valid)) $this->json(['success'=>false,'message'=>'Invalid status.']);
        require_once APP . '/models/OrderModel.php';
        $om    = new OrderModel();
        $order = $om->getById($id);
        if (!$order) $this->json(['success'=>false,'message'=>'Order not found.']);
        $om->updateStatus($id, $status, $notes, Auth::id());
        if ($tracking) $this->db->update('orders', ['tracking_number'=>$tracking], "id=$id");
        $this->db->logActivity(Auth::id(), 'UPDATE_STATUS', 'orders', $id, ['status'=>$order['status']], ['status'=>$status]);
        try { MailService::sendOrderStatusUpdate(array_merge($order, ['tracking_number'=>$tracking ?: $order['tracking_number']]), $status, $notes); } catch (\Throwable $e) {}
        $this->json(['success'=>true,'message'=>'Order status updated to: '.ucfirst($status)]);
    }

    public function updatePayment(array $p = []): void {
        CSRF::check();
        $id      = (int)($_POST['order_id'] ?? 0);
        $status  = Helper::sanitize($_POST['payment_status'] ?? '');
        $ref     = Helper::sanitize($_POST['payment_ref'] ?? '');
        $valid   = ['unpaid','paid','partial','refunded'];
        if (!in_array($status, $valid)) $this->json(['success'=>false,'message'=>'Invalid payment status.']);
        $upd = ['payment_status'=>$status, 'updated_at'=>date('Y-m-d H:i:s')];
        if ($ref) $upd['payment_ref'] = $ref;
        $this->db->update('orders', $upd, "id=$id");
        $this->db->insert('order_status_history', [
            'order_id'   => $id,
            'status'     => 'payment_'.$status,
            'notes'      => 'Payment marked as '.ucfirst($status).($ref ? ' — Ref: '.$ref : ''),
            'changed_by' => Auth::id(),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        $this->db->logActivity(Auth::id(), 'UPDATE_PAYMENT', 'orders', $id, null, ['payment_status'=>$status]);
        $this->json(['success'=>true,'message'=>'Payment updated to: '.ucfirst($status)]);
    }

    public function updateShipping(array $p = []): void {
        CSRF::check();
        $id     = (int)($_POST['order_id'] ?? 0);
        $method = Helper::sanitize($_POST['shipping_method'] ?? '');
        $amount = max(0, (float)($_POST['shipping_amount'] ?? 0));
        $order  = $this->db->selectOne("SELECT * FROM orders WHERE id=$id");
        if (!$order) $this->json(['success'=>false,'message'=>'Order not found.']);
        $newTotal = (float)$order['subtotal'] - (float)$order['discount_amount'] + $amount + (float)$order['tax_amount'];
        $this->db->update('orders', [
            'shipping_method' => $method,
            'shipping_amount' => $amount,
            'total_amount'    => $newTotal,
            'updated_at'      => date('Y-m-d H:i:s'),
        ], "id=$id");
        $this->json(['success'=>true,'message'=>'Shipping updated.','new_total'=>number_format($newTotal,2)]);
    }

    public function invoice(array $params): void {
        $id    = (int)($params['id'] ?? 0);
        require_once APP . '/models/OrderModel.php';
        $order = (new OrderModel())->getById($id);
        if (!$order) { echo 'Order not found.'; return; }
        (new InvoiceGenerator($order, $order['items'], 'invoice'))->stream();
    }

    public function receipt(array $params): void {
        $id    = (int)($params['id'] ?? 0);
        require_once APP . '/models/OrderModel.php';
        $order = (new OrderModel())->getById($id);
        if (!$order) { echo 'Order not found.'; return; }
        (new InvoiceGenerator($order, $order['items'], 'receipt'))->stream();
    }

    public function download(array $params): void {
        $id    = (int)($params['id'] ?? 0);
        require_once APP . '/models/OrderModel.php';
        $order = (new OrderModel())->getById($id);
        if (!$order) { echo 'Order not found.'; return; }
        (new InvoiceGenerator($order, $order['items'], 'invoice'))->streamDownload();
    }

    public function downloadReceipt(array $params): void {
        $id    = (int)($params['id'] ?? 0);
        require_once APP . '/models/OrderModel.php';
        $order = (new OrderModel())->getById($id);
        if (!$order) { echo 'Order not found.'; return; }
        (new InvoiceGenerator($order, $order['items'], 'receipt'))->streamDownload();
    }
}

// ============================================================
// AdminReportController
// ============================================================
class AdminReportController extends AdminController {
    public function index(array $p = []): void { $this->redirect('admin/reports/sales'); }
    public function sales(array $p = []): void {
        $pageTitle = 'Sales Report';
        $from  = $this->db->connect()->real_escape_string($_GET['from'] ?? date('Y-m-01'));
        $to    = $this->db->connect()->real_escape_string($_GET['to']   ?? date('Y-m-t'));
        $daily = $this->db->select(
            "SELECT DATE(created_at) AS day, COUNT(*) AS orders, SUM(total_amount) AS revenue
             FROM orders WHERE status NOT IN ('cancelled','refunded')
               AND DATE(created_at) BETWEEN '$from' AND '$to'
             GROUP BY day ORDER BY day"
        ) ?: [];
        $summary = $this->db->selectOne(
            "SELECT COUNT(*) AS total_orders, SUM(total_amount) AS total_revenue,
                    AVG(total_amount) AS avg_order
             FROM orders WHERE status NOT IN ('cancelled','refunded')
               AND DATE(created_at) BETWEEN '$from' AND '$to'"
        ) ?? ['total_orders'=>0,'total_revenue'=>0,'avg_order'=>0];
        $topProducts = $this->db->select(
            "SELECT p.name, SUM(oi.quantity) AS qty, SUM(oi.total_price) AS revenue
             FROM order_items oi JOIN products p ON p.id=oi.product_id
             JOIN orders o ON o.id=oi.order_id
             WHERE o.status NOT IN ('cancelled','refunded')
               AND DATE(o.created_at) BETWEEN '$from' AND '$to'
             GROUP BY p.id ORDER BY revenue DESC LIMIT 10"
        ) ?: [];
        $this->view('reports/sales', compact('pageTitle','daily','summary','topProducts','from','to'));
    }
    public function products(array $p = []): void {
        $pageTitle = 'Product Report';
        $products  = $this->db->select(
            "SELECT p.name, p.sku, p.price, p.views,
                    COALESCE(ps.quantity,0) AS stock,
                    COALESCE(SUM(oi.quantity),0) AS sold,
                    COALESCE(SUM(oi.total_price),0) AS revenue,
                    p.rating_avg, p.rating_count
             FROM products p
             LEFT JOIN product_stock ps ON ps.product_id=p.id AND ps.variation_id IS NULL AND ps.warehouse='Main'
             LEFT JOIN order_items oi ON oi.product_id=p.id
             LEFT JOIN orders o ON o.id=oi.order_id AND o.status NOT IN ('cancelled','refunded')
             WHERE p.status='active'
             GROUP BY p.id ORDER BY revenue DESC"
        ) ?: [];
        $this->view('reports/products', compact('pageTitle','products'));
    }
}

// ============================================================
// AdminBrandController
// ============================================================
class AdminBrandController extends AdminController {
    public function index(array $p = []): void {
        $pageTitle = 'Brands';
        $db        = $this->db->connect();
        $search    = $db->real_escape_string(trim($_GET['search'] ?? ''));
        $perPage   = in_array((int)($_GET['per_page'] ?? 25), [10,25,50,100]) ? (int)($_GET['per_page'] ?? 25) : 25;
        $page      = max(1, (int)($_GET['page'] ?? 1));
        $offset    = ($page - 1) * $perPage;

        $having = $search ? "HAVING b.name LIKE '%$search%' OR b.slug LIKE '%$search%'" : '';

        $total = (int)($this->db->selectOne(
            "SELECT COUNT(*) AS cnt FROM (
               SELECT b.id FROM brands b
               LEFT JOIN products p ON p.brand_id=b.id AND p.status='active'
               GROUP BY b.id $having
             ) AS sub"
        )['cnt'] ?? 0);

        $brands = $this->db->select(
            "SELECT b.*, COUNT(p.id) AS product_count
             FROM brands b
             LEFT JOIN products p ON p.brand_id=b.id AND p.status='active'
             GROUP BY b.id $having
             ORDER BY b.name
             LIMIT $perPage OFFSET $offset"
        ) ?: [];

        $pagData = Helper::paginate($total, $perPage, $page);
        $this->view('brands/index', compact('pageTitle','brands','search','perPage','pagData','total'));
    }
    public function store(array $p = []): void {
        CSRF::check();
        $name = Helper::sanitize($_POST['name'] ?? '');
        if (!$name) $this->json(['success'=>false,'message'=>'Brand name is required.']);
        $rawSlug = trim($_POST['slug'] ?? '');
        $slug    = Helper::slug($rawSlug ?: $name);
        $db      = $this->db->connect();
        $esc     = $db->real_escape_string($slug);
        if ($this->db->selectOne("SELECT id FROM brands WHERE slug='$esc'")) $slug .= '-'.time();
        $logo = null;
        if (!empty($_FILES['logo']['name'])) $logo = Helper::uploadImage($_FILES['logo'], 'brands');
        $id = $this->db->insert('brands', ['name'=>$name,'slug'=>$slug,'logo'=>$logo,'status'=>(int)($_POST['status']??1)]);
        $this->json(['success'=>true,'message'=>'Brand created.','id'=>$id,'slug'=>$slug]);
    }
    public function update(array $p = []): void {
        CSRF::check();
        $id   = (int)($_POST['id'] ?? 0);
        $name = Helper::sanitize($_POST['name'] ?? '');
        if (!$name) $this->json(['success'=>false,'message'=>'Brand name is required.']);
        // Resolve slug — use submitted slug if present, else derive from name
        $rawSlug = trim($_POST['slug'] ?? '');
        $newSlug = Helper::slug($rawSlug ?: $name);
        $db      = $this->db->connect();
        $esc     = $db->real_escape_string($newSlug);
        if ($this->db->selectOne("SELECT id FROM brands WHERE slug='$esc' AND id != $id")) {
            $newSlug .= '-' . $id;
        }
        $upd = ['name'=>$name, 'slug'=>$newSlug, 'status'=>(int)($_POST['status']??1)];
        if (!empty($_FILES['logo']['name'])) {
            $logo = Helper::uploadImage($_FILES['logo'], 'brands');
            if ($logo) $upd['logo'] = $logo;
        }
        $this->db->update('brands', $upd, "id=$id");
        $this->json(['success'=>true,'message'=>'Brand updated.','slug'=>$newSlug]);
    }
    public function delete(array $p = []): void {
        CSRF::check();
        $id = (int)($_POST['id'] ?? 0);
        $this->db->update('brands', ['status'=>0], "id=$id");
        $this->json(['success'=>true,'message'=>'Brand disabled.']);
    }
}

// ============================================================
// AdminMessagesController
// ============================================================
class AdminMessagesController extends AdminController {
    public function index(array $p = []): void {
        $pageTitle = 'Contact Messages';
        $db        = $this->db->connect();
        $search    = $db->real_escape_string(trim($_GET['search'] ?? ''));
        $filter    = $db->real_escape_string($_GET['filter'] ?? 'all');
        $perPage   = in_array((int)($_GET['per_page'] ?? 25), [10,25,50,100]) ? (int)($_GET['per_page'] ?? 25) : 25;
        $page      = max(1, (int)($_GET['page'] ?? 1));
        $offset    = ($page - 1) * $perPage;

        $where = '1=1';
        if ($filter === 'unread') $where .= ' AND is_read=0';
        if ($filter === 'read')   $where .= ' AND is_read=1';
        if ($search) $where .= " AND (name LIKE '%$search%' OR email LIKE '%$search%' OR subject LIKE '%$search%')";

        $total = (int)($this->db->selectOne("SELECT COUNT(*) AS cnt FROM contact_messages WHERE $where")['cnt'] ?? 0);

        $messages = $this->db->select(
            "SELECT * FROM contact_messages WHERE $where ORDER BY created_at DESC LIMIT $perPage OFFSET $offset"
        ) ?: [];

        $unreadCount = (int)($this->db->selectOne("SELECT COUNT(*) AS cnt FROM contact_messages WHERE is_read=0")['cnt'] ?? 0);
        $pagData = Helper::paginate($total, $perPage, $page);
        $this->view('messages/index', compact('pageTitle','messages','search','filter','perPage','pagData','total','unreadCount'));
    }
    public function markRead(array $p = []): void {
        CSRF::check();
        $id = (int)($_POST['id'] ?? 0);
        $this->db->update('contact_messages', ['is_read'=>1], "id=$id");
        $this->json(['success'=>true]);
    }
}

// ============================================================
// AdminPaymentController
// ============================================================
class AdminPaymentController extends AdminController {

    public function index(array $p = []): void {
        $pageTitle    = 'Payment Methods';
        $methods      = $this->db->select("SELECT * FROM payment_methods ORDER BY sort_order") ?: [];
        $txnStats     = $this->db->selectOne(
            "SELECT COUNT(*) AS total,
                    SUM(CASE WHEN status='pending'  THEN 1 ELSE 0 END) AS pending,
                    SUM(CASE WHEN status='success'  THEN 1 ELSE 0 END) AS success,
                    SUM(CASE WHEN status='failed'   THEN 1 ELSE 0 END) AS failed,
                    SUM(CASE WHEN status='refunded' THEN 1 ELSE 0 END) AS refunded
             FROM payment_transactions"
        ) ?? [];
        $this->view('payments/index', compact('pageTitle','methods','txnStats'));
    }

    public function store(array $p = []): void {
        CSRF::check();
        $code = strtolower(preg_replace('/[^a-z0-9_]/i','',Helper::sanitize($_POST['code'] ?? '')));
        $name = Helper::sanitize($_POST['display_name'] ?? '');
        if (!$code || !$name) $this->json(['success'=>false,'message'=>'Code and name are required.']);
        if ($this->db->selectOne("SELECT id FROM payment_methods WHERE code='$code'"))
            $this->json(['success'=>false,'message'=>'Code already exists.']);
        $this->db->insert('payment_methods', [
            'code'         => $code,
            'display_name' => $name,
            'description'  => Helper::sanitize($_POST['description'] ?? ''),
            'icon'         => Helper::sanitize($_POST['icon'] ?? '💳'),
            'sort_order'   => (int)($_POST['sort_order'] ?? 99),
            'is_active'    => (int)($_POST['is_active'] ?? 1),
        ]);
        $this->json(['success'=>true,'message'=>'Payment method added.']);
    }

    public function update(array $p = []): void {
        CSRF::check();
        $id   = (int)($_POST['id'] ?? 0);
        $name = Helper::sanitize($_POST['display_name'] ?? '');
        if (!$name) $this->json(['success'=>false,'message'=>'Display name is required.']);
        $this->db->update('payment_methods', [
            'display_name' => $name,
            'description'  => Helper::sanitize($_POST['description'] ?? ''),
            'icon'         => Helper::sanitize($_POST['icon']        ?? ''),
            'sort_order'   => (int)($_POST['sort_order'] ?? 0),
            'is_active'    => (int)($_POST['is_active']  ?? 0),
        ], "id=$id");
        $this->json(['success'=>true,'message'=>'Payment method updated.']);
    }

    public function toggle(array $p = []): void {
        CSRF::check();
        $id  = (int)($_POST['id'] ?? 0);
        $cur = $this->db->selectOne("SELECT is_active FROM payment_methods WHERE id=$id");
        if (!$cur) $this->json(['success'=>false,'message'=>'Not found.']);
        $new = $cur['is_active'] ? 0 : 1;
        $this->db->update('payment_methods', ['is_active'=>$new], "id=$id");
        $this->json(['success'=>true,'active'=>$new,'message'=>$new ? 'Enabled.' : 'Disabled.']);
    }

    public function delete(array $p = []): void {
        CSRF::check();
        $id = (int)($_POST['id'] ?? 0);
        // Prevent deleting methods used in orders
        $used = $this->db->selectOne(
            "SELECT COUNT(*) AS c FROM orders o JOIN payment_methods pm ON pm.code=o.payment_method WHERE pm.id=$id"
        );
        if (($used['c'] ?? 0) > 0)
            $this->json(['success'=>false,'message'=>'Cannot delete: method has existing orders. Disable it instead.']);
        $this->db->delete('payment_methods', "id=$id");
        $this->json(['success'=>true,'message'=>'Payment method deleted.']);
    }

    public function transactions(array $p = []): void {
        $pageTitle = 'Payment Transactions';
        $status    = $this->db->connect()->real_escape_string($_GET['status'] ?? '');
        $gateway   = $this->db->connect()->real_escape_string($_GET['gateway'] ?? '');
        $cond      = "WHERE 1=1";
        if ($status)  $cond .= " AND pt.status='$status'";
        if ($gateway) $cond .= " AND pt.gateway='$gateway'";
        $perPage = 50;
        $page    = max(1,(int)($_GET['page'] ?? 1));
        $offset  = ($page-1)*$perPage;
        $total   = (int)($this->db->selectOne("SELECT COUNT(*) AS c FROM payment_transactions pt $cond")['c'] ?? 0);
        $txns    = $this->db->select(
            "SELECT pt.*, o.order_number, o.shipping_name, o.total_amount AS order_total
             FROM payment_transactions pt
             LEFT JOIN orders o ON o.id=pt.order_id
             $cond ORDER BY pt.created_at DESC LIMIT $perPage OFFSET $offset"
        ) ?: [];
        $pagData = Helper::paginate($total, $perPage, $page);
        $gateways = $this->db->select("SELECT DISTINCT gateway FROM payment_transactions") ?: [];
        $this->view('payments/transactions', compact('pageTitle','txns','status','gateway','gateways','pagData','total'));
    }

    public function updateTxn(array $p = []): void {
        CSRF::check();
        $id     = (int)($_POST['id'] ?? 0);
        $status = Helper::sanitize($_POST['status'] ?? '');
        $valid  = ['pending','success','failed','refunded'];
        if (!in_array($status,$valid)) $this->json(['success'=>false,'message'=>'Invalid status.']);
        $this->db->update('payment_transactions', ['status'=>$status], "id=$id");
        // Sync order payment_status when transaction status changes
        $txn = $this->db->selectOne("SELECT order_id FROM payment_transactions WHERE id=$id");
        if ($txn && $txn['order_id']) {
            $map = ['success'=>'paid','failed'=>'unpaid','refunded'=>'refunded','pending'=>'unpaid'];
            $this->db->update('orders', ['payment_status'=>$map[$status]], "id={$txn['order_id']}");
        }
        $this->json(['success'=>true,'message'=>'Transaction updated.']);
    }
}

// ============================================================
// AdminShippingController
// ============================================================
class AdminShippingController extends AdminController {
    public function index(array $p = []): void {
        $pageTitle = 'Shipping Methods';
        $methods   = $this->db->select("SELECT * FROM shipping_methods ORDER BY sort_order ASC, price ASC") ?: [];
        $this->view('shipping/index', compact('pageTitle','methods'));
    }

    private function fields(bool $isNew = false): array {
        $freeAbove = (float)($_POST['free_above'] ?? 0);
        $price     = (float)($_POST['price'] ?? 0);
        $isFree    = (int)($_POST['is_free'] ?? 0);
        $f = [
            'name'        => Helper::sanitize($_POST['name']        ?? ''),
            'description' => Helper::sanitize($_POST['description'] ?? ''),
            'price'       => $isFree ? 0 : $price,
            'min_days'    => max(0, (int)($_POST['min_days'] ?? 1)),
            'max_days'    => max(1, (int)($_POST['max_days'] ?? 5)),
            'zone'        => Helper::sanitize($_POST['zone']        ?? 'all'),
            'is_free'     => $isFree,
            'free_above'  => $isFree ? 0 : ($freeAbove ?: 0),
            'status'      => $isNew ? 1 : (int)($_POST['status'] ?? 1),
            'sort_order'  => (int)($_POST['sort_order'] ?? 0),
        ];
        return $f;
    }

    public function store(array $p = []): void {
        CSRF::check();
        $name = Helper::sanitize($_POST['name'] ?? '');
        if (!$name) $this->json(['success'=>false,'message'=>'Name is required.']);
        $id = $this->db->insert('shipping_methods', $this->fields(true));
        $this->json(['success'=>true,'message'=>'Shipping method added.','id'=>$id]);
    }

    public function update(array $p = []): void {
        CSRF::check();
        $id   = (int)($_POST['id'] ?? 0);
        $name = Helper::sanitize($_POST['name'] ?? '');
        if (!$id)   $this->json(['success'=>false,'message'=>'Invalid ID.']);
        if (!$name) $this->json(['success'=>false,'message'=>'Name is required.']);
        $this->db->update('shipping_methods', $this->fields(false), "id=$id");
        $this->json(['success'=>true,'message'=>'Shipping method updated.']);
    }

    public function delete(array $p = []): void {
        CSRF::check();
        $id = (int)($_POST['id'] ?? 0);
        // Check if method is in use by open orders
        $inUse = $this->db->selectOne(
            "SELECT COUNT(*) AS c FROM orders WHERE shipping_method=(SELECT name FROM shipping_methods WHERE id=$id) AND status NOT IN ('delivered','cancelled','refunded')"
        );
        if (($inUse['c'] ?? 0) > 0) {
            $this->db->update('shipping_methods', ['status'=>0], "id=$id");
            $this->json(['success'=>true,'message'=>'Method has active orders — disabled instead of deleted.','disabled'=>true]);
        }
        $this->db->delete('shipping_methods', "id=$id");
        $this->json(['success'=>true,'message'=>'Shipping method deleted.']);
    }
}

// ============================================================
// AdminCategoryController — unlimited nested
// ============================================================
class AdminCategoryController extends AdminController {
    public function index(array $p = []): void {
        $pageTitle  = 'Categories';
        $allCats    = $this->db->select("SELECT * FROM categories WHERE status=1 ORDER BY depth, parent_id, sort_order") ?: [];
        $tree       = Helper::getCategoryTree($allCats);
        $flatList   = Helper::flattenCategoryTree($tree);
        $this->view('categories/index', compact('pageTitle','tree','flatList','allCats'));
    }
    public function children(array $params): void {
        $parentId = (int)($params['id'] ?? 0);
        $cats = $this->db->select("SELECT * FROM categories WHERE parent_id=$parentId AND status=1 ORDER BY sort_order") ?: [];
        $this->json(['success'=>true,'categories'=>$cats]);
    }
    public function store(array $p = []): void {
        CSRF::check();
        $name     = Helper::sanitize($_POST['name'] ?? '');
        $parentId = (int)($_POST['parent_id'] ?? 0) ?: null;
        $slug     = Helper::slug($name);
        if ($this->db->selectOne("SELECT id FROM categories WHERE slug='$slug'")) $slug .= '-'.substr(uniqid(),0,4);

        // Calculate depth and path
        $depth = 0;
        $path  = $slug;
        if ($parentId) {
            $parent = $this->db->selectOne("SELECT depth, path FROM categories WHERE id=$parentId");
            if ($parent) {
                $depth = $parent['depth'] + 1;
                $path  = $parent['path'] . '/' . $slug;
            }
        }

        $img = null;
        if (!empty($_FILES['image']['name'])) $img = Helper::uploadImage($_FILES['image'], 'categories');

        $id = $this->db->insert('categories', [
            'parent_id'  => $parentId,
            'name'       => $name,
            'name_si'    => Helper::sanitize($_POST['name_si'] ?? ''),
            'name_ta'    => Helper::sanitize($_POST['name_ta'] ?? ''),
            'slug'       => $slug,
            'image'      => $img,
            'icon'       => Helper::sanitize($_POST['icon'] ?? 'fa-tag'),
            'sort_order' => (int)($_POST['sort_order'] ?? 0),
            'depth'      => $depth,
            'path'       => $path,
            'status'     => 1,
        ]);
        $this->db->logActivity(Auth::id(), 'CREATE', 'categories', $id);
        $this->json(['success'=>true,'message'=>'Category created.','id'=>$id]);
    }
    public function update(array $p = []): void {
        CSRF::check();
        $id = (int)($_POST['id'] ?? 0);
        $upd = [
            'name'       => Helper::sanitize($_POST['name']    ?? ''),
            'name_si'    => Helper::sanitize($_POST['name_si'] ?? ''),
            'name_ta'    => Helper::sanitize($_POST['name_ta'] ?? ''),
            'parent_id'  => (int)($_POST['parent_id'] ?? 0) ?: null,
            'icon'       => Helper::sanitize($_POST['icon']       ?? 'fa-tag'),
            'sort_order' => (int)($_POST['sort_order'] ?? 0),
            'status'     => (int)($_POST['status'] ?? 1),
        ];
        if (!empty($_FILES['image']['name'])) {
            $img = Helper::uploadImage($_FILES['image'], 'categories');
            if ($img) $upd['image'] = $img;
        }
        $this->db->update('categories', $upd, "id=$id");
        $this->json(['success'=>true,'message'=>'Category updated.']);
    }
    public function delete(array $p = []): void {
        CSRF::check();
        $id = (int)($_POST['id'] ?? 0);
        // Only block deletion if active children exist
        $children = $this->db->selectOne("SELECT COUNT(*) AS c FROM categories WHERE parent_id=$id AND status=1");
        if ($children && (int)$children['c'] > 0) {
            $this->json(['success'=>false,'message'=>'Cannot delete: has active sub-categories. Remove them first.']);
            return;
        }
        $this->db->update('categories', ['status'=>0], "id=$id");
        $this->json(['success'=>true,'message'=>'Category deleted.']);
    }
}

// ============================================================
// AdminNewsletterController
// ============================================================
class AdminNewsletterController extends AdminController {
    public function index(array $p = []): void {
        $pageTitle = 'Newsletter Subscribers';
        $db        = $this->db->connect();
        $status    = $db->real_escape_string($_GET['status'] ?? 'active');
        $search    = $db->real_escape_string(trim($_GET['search'] ?? ''));
        $perPage   = in_array((int)($_GET['per_page'] ?? 25), [10,25,50,100]) ? (int)($_GET['per_page'] ?? 25) : 25;
        $page      = max(1, (int)($_GET['page'] ?? 1));
        $offset    = ($page - 1) * $perPage;

        $where = "status='$status'";
        if ($search) $where .= " AND email LIKE '%$search%'";

        $total = (int)($this->db->selectOne("SELECT COUNT(*) AS cnt FROM newsletter_subscribers WHERE $where")['cnt'] ?? 0);

        $subs = $this->db->select(
            "SELECT * FROM newsletter_subscribers WHERE $where ORDER BY subscribed_at DESC LIMIT $perPage OFFSET $offset"
        ) ?: [];

        $counts = $this->db->selectOne(
            "SELECT SUM(status='active') AS active, SUM(status='unsubscribed') AS inactive
             FROM newsletter_subscribers"
        ) ?? ['active' => 0, 'inactive' => 0];

        $pagData = Helper::paginate($total, $perPage, $page);
        $this->view('newsletter/index', compact('pageTitle','subs','status','search','perPage','pagData','total','counts'));
    }
    public function delete(array $p = []): void {
        CSRF::check();
        $id = (int)($_POST['id'] ?? 0);
        $this->db->update('newsletter_subscribers', ['status' => 'unsubscribed'], "id=$id");
        $this->json(['success' => true, 'message' => 'Subscriber unsubscribed.']);
    }
    public function export(array $p = []): void {
        $subs = $this->db->select(
            "SELECT email, subscribed_at FROM newsletter_subscribers WHERE status='active' ORDER BY subscribed_at DESC"
        ) ?: [];
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="newsletter_subscribers_'.date('Y-m-d').'.csv"');
        $out = fopen('php://output', 'w');
        fputcsv($out, ['Email', 'Subscribed At']);
        foreach ($subs as $s) fputcsv($out, [$s['email'], $s['subscribed_at']]);
        fclose($out);
        exit;
    }
}

// ============================================================
// AdminContactSettingsController
// ============================================================
class AdminContactSettingsController extends AdminController {
    private array $keys = [
        'social_facebook', 'social_instagram', 'social_tiktok',
        'social_youtube', 'social_twitter', 'social_skype',
        'support_phone', 'support_email', 'whatsapp_number',
    ];

    public function index(array $p = []): void {
        $pageTitle = 'Contact & Social Settings';
        $settings  = [];
        foreach ($this->keys as $k) {
            $settings[$k] = setting($k);
        }
        $this->view('settings/contact', compact('pageTitle', 'settings'));
    }

    public function save(array $p = []): void {
        CSRF::check();
        $db = $this->db->connect();
        foreach ($this->keys as $k) {
            $val = Helper::sanitize($_POST[$k] ?? '');
            $esc = $db->real_escape_string($val);
            $kEsc = $db->real_escape_string($k);
            $this->db->query(
                "INSERT INTO settings (`key`,`value`) VALUES ('$kEsc','$esc')
                 ON DUPLICATE KEY UPDATE `value`='$esc'"
            );
        }
        $this->json(['success' => true, 'message' => 'Contact & social settings saved.']);
    }
}
