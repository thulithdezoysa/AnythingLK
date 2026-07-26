<?php
require_once CORE . '/Controller.php';
require_once APP  . '/models/OrderModel.php';

class UserController extends Controller {

    public function loginPage(array $p = []): void {
        if (Auth::check()) {
            header('Location: ' . Auth::redirectAfterLogin());
            exit;
        }
        $this->view('user/login');
    }

    public function login(array $p = []): void {
        CSRF::check();

        if (!RateLimit::check('login', 10, 300)) {
            $this->json(['success'=>false,'message'=>'Too many login attempts. Please try again in 5 minutes.']);
        }

        $email    = Helper::sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (!Helper::validEmail($email) || empty($password)) {
            $this->json(['success'=>false,'message'=>'Please enter a valid email and password.']);
        }

        $emailQ = $this->db->escape($email);
        $user   = $this->db->selectOne("SELECT * FROM users WHERE email=$emailQ AND status='active'");

        if (!$user || !Helper::verifyPassword($password, $user['password'])) {
            $this->json(['success'=>false,'message'=>'Invalid email or password.']);
        }

        RateLimit::reset('login');
        Auth::login($user);
        $merged = Auth::mergeGuestCart($this->db);
        $this->db->logLoginActivity($user['id']);
        $this->db->update('users', ['last_login' => date('Y-m-d H:i:s')], "id={$user['id']}");

        $msg = 'Welcome back, ' . e($user['full_name']) . '!';
        if ($merged > 0) {
            $msg .= " ({$merged} cart item" . ($merged > 1 ? 's' : '') . " from your previous session added.)";
        }

        $redirect = Auth::redirectAfterLogin();
        $this->json(['success'=>true,'message'=>$msg,'redirect'=>$redirect]);
    }

    public function registerPage(array $p = []): void {
        if (Auth::check()) { $this->redirect('account'); }
        $this->view('user/register');
    }

    public function register(array $p = []): void {
        CSRF::check();

        if (!RateLimit::check('register', 5, 300)) {
            $this->json(['success'=>false,'message'=>'Too many registration attempts. Please try again in 5 minutes.']);
        }

        $name     = Helper::sanitize($_POST['full_name'] ?? '');
        $email    = Helper::sanitize($_POST['email']     ?? '');
        $password = $_POST['password']          ?? '';
        $confirm  = $_POST['confirm_password']  ?? '';
        $phone    = Helper::sanitize($_POST['phone'] ?? '');

        if (!$name || !Helper::validEmail($email) || strlen($password) < 8) {
            $this->json(['success'=>false,'message'=>'Fill all required fields. Password min 8 characters.']);
        }
        if ($password !== $confirm) {
            $this->json(['success'=>false,'message'=>'Passwords do not match.']);
        }
        $emailQ = $this->db->escape($email);
        if ($this->db->selectOne("SELECT id FROM users WHERE email=$emailQ")) {
            $this->json(['success'=>false,'message'=>'This email is already registered.']);
        }

        $uid = $this->db->insert('users', [
            'full_name' => $name,'email' => $email,'phone' => $phone,
            'password'  => Helper::hashPassword($password),
            'role'      => 'customer','status' => 'active','email_verified' => 1,
            'created_at'=> date('Y-m-d H:i:s'),
        ]);

        $newUser = $this->db->selectOne("SELECT * FROM users WHERE id=$uid");
        Auth::login($newUser);
        Auth::mergeGuestCart($this->db);
        try { MailService::sendWelcome($email, $name); } catch (\Throwable $e) {}

        $this->json(['success'=>true,'message'=>'Welcome to Anything.lk!','redirect'=>url('account')]);
    }

    public function logout(array $p = []): void {
        Auth::logout();
        $this->redirect('');
    }

    public function forgotPage(array $p = []): void { $this->view('user/forgot_password'); }

    public function forgotPassword(array $p = []): void {
        CSRF::check();

        if (!RateLimit::check('forgot_password', 5, 600)) {
            $this->json(['success'=>true,'message'=>'If that email exists, a reset link has been sent.']);
        }

        $email  = Helper::sanitize($_POST['email'] ?? '');
        $emailQ = $this->db->escape($email);
        $user   = $this->db->selectOne("SELECT * FROM users WHERE email=$emailQ AND status='active'");
        if ($user) {
            $token   = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', time() + 3600);
            $this->db->update('users', ['reset_token'=>$token,'reset_expires'=>$expires], "id={$user['id']}");
            try { MailService::sendPasswordReset($user['email'], $user['full_name'], $token); } catch (\Throwable $e) {}
        }
        $this->json(['success'=>true,'message'=>'If that email exists, a reset link has been sent.']);
    }

    public function resetPage(array $p = []): void {
        $token  = Helper::sanitize($_GET['token'] ?? '');
        $tokenQ = $this->db->escape($token);
        $valid  = $this->db->selectOne("SELECT id FROM users WHERE reset_token=$tokenQ AND reset_expires > NOW()");
        if (!$valid) { $this->redirect('login'); }
        $this->view('user/reset_password', ['token' => $token]);
    }

    public function resetPassword(array $p = []): void {
        CSRF::check();
        $token = Helper::sanitize($_POST['token'] ?? '');
        $pw    = $_POST['password'] ?? '';
        $cpw   = $_POST['confirm_password'] ?? '';
        if (strlen($pw) < 8 || $pw !== $cpw) {
            $this->json(['success'=>false,'message'=>'Passwords must match and be at least 8 characters.']);
        }
        $tokenQ = $this->db->escape($token);
        $user   = $this->db->selectOne("SELECT id FROM users WHERE reset_token=$tokenQ AND reset_expires > NOW()");
        if (!$user) { $this->json(['success'=>false,'message'=>'Reset link expired. Request a new one.']); }
        $this->db->update('users', ['password'=>Helper::hashPassword($pw),'reset_token'=>null,'reset_expires'=>null], "id={$user['id']}");
        $this->json(['success'=>true,'message'=>'Password reset! You can now log in.','redirect'=>url('login')]);
    }

    // ── Customer Account ─────────────────────────────────────────
    public function account(array $p = []): void {
        $this->requireLogin();
        if (!Auth::isCustomer()) { header('Location: ' . Auth::redirectAfterLogin()); exit; }
        $uid  = Auth::id();
        $user = $this->db->selectOne("SELECT * FROM users WHERE id=$uid");
        $recentOrders  = (new OrderModel())->getByUser($uid, 1, 5);
        $orderStats    = $this->db->selectOne(
            "SELECT COUNT(*) AS total,
                    SUM(status IN ('pending','confirmed')) AS pending,
                    SUM(status='delivered') AS delivered
             FROM orders WHERE user_id=$uid"
        ) ?: [];
        $totalOrders    = (int)($orderStats['total']    ?? 0);
        $pendingCount   = (int)($orderStats['pending']  ?? 0);
        $deliveredCount = (int)($orderStats['delivered']?? 0);
        $wishlistCount  = (int)($this->db->selectOne("SELECT COUNT(*) AS c FROM wishlists WHERE user_id=$uid")['c'] ?? 0);
        $this->view('user/account', compact('user','recentOrders','wishlistCount','totalOrders','pendingCount','deliveredCount'));
    }

    public function orders(array $p = []): void {
        $this->requireLogin();
        if (!Auth::isCustomer()) { $this->redirect('account'); }
        $uid     = Auth::id();
        $page    = max(1, (int)($_GET['page'] ?? 1));
        $orders  = (new OrderModel())->getByUser($uid, $page, 10);
        $total   = (int)($this->db->selectOne("SELECT COUNT(*) AS c FROM orders WHERE user_id=$uid")['c'] ?? 0);
        $pagData = Helper::paginate($total, 10, $page);
        $this->view('user/orders', compact('orders','page','pagData'));
    }

    public function orderDetail(array $params): void {
        $this->requireLogin();
        $id    = (int)($params['id'] ?? 0);
        $order = (new OrderModel())->getById($id);
        if (!$order || (int)$order['user_id'] !== Auth::id()) { $this->redirect('account/orders'); }
        $this->view('user/order_detail', compact('order'));
    }

    public function cancelOrder(array $params): void {
        CSRF::check();
        $this->requireLogin();
        $id    = (int)($params['id'] ?? 0);
        $uid   = Auth::id();
        $om    = new OrderModel();
        $order = $om->getById($id);

        if (!$order || (int)$order['user_id'] !== $uid) {
            $this->json(['success'=>false,'message'=>'Order not found.']);
        }
        if (!in_array($order['status'], ['pending', 'confirmed'])) {
            $this->json(['success'=>false,'message'=>'This order can no longer be cancelled.']);
        }

        $om->updateStatus($id, 'cancelled', 'Cancelled by customer');
        $this->json(['success'=>true,'message'=>'Your order has been cancelled.','redirect'=>url('account/orders')]);
    }

    public function wishlist(array $p = []): void {
        $this->requireLogin();
        $uid   = Auth::id();
        $items = $this->db->select(
            "SELECT w.product_id, w.added_at,
                    p.id, p.name, p.slug, p.thumbnail, p.price, p.sale_price,
                    p.is_new, p.is_featured, p.rating_avg, p.rating_count,
                    COALESCE(ps.quantity, 0) AS stock_qty
             FROM wishlists w
             JOIN products p ON p.id = w.product_id
             LEFT JOIN product_stock ps ON ps.product_id = p.id AND ps.variation_id IS NULL
             WHERE w.user_id=$uid ORDER BY w.added_at DESC"
        ) ?: [];
        $this->view('user/wishlist', compact('items'));
    }

    public function addresses(array $p = []): void {
        $this->requireLogin();
        $uid       = Auth::id();
        $addresses = $this->db->select("SELECT * FROM user_addresses WHERE user_id=$uid ORDER BY is_default DESC") ?: [];
        $this->view('user/addresses', compact('addresses'));
    }

    public function updateProfile(array $p = []): void {
        CSRF::check();
        $this->requireLogin();
        $uid = Auth::id();

        // Accept either split first/last or a combined full_name
        $firstName = Helper::sanitize($_POST['first_name'] ?? '');
        $lastName  = Helper::sanitize($_POST['last_name']  ?? '');
        $fullName  = trim("$firstName $lastName");
        if ($fullName === '') {
            $fullName = Helper::sanitize($_POST['full_name'] ?? '');
        }
        if ($fullName === '') {
            $this->json(['success'=>false,'message'=>'Name is required.']);
        }

        $phone = Helper::sanitize($_POST['phone'] ?? '');
        $upd   = ['full_name'=>$fullName,'phone'=>$phone,'updated_at'=>date('Y-m-d H:i:s')];

        if (!empty($_POST['new_password'])) {
            $newPw = $_POST['new_password'];
            if (strlen($newPw) < 8) {
                $this->json(['success'=>false,'message'=>'New password must be at least 8 characters.']);
            }
            $row = $this->db->selectOne("SELECT password FROM users WHERE id=$uid");
            if (!Helper::verifyPassword($_POST['current_password'] ?? '', $row['password'])) {
                $this->json(['success'=>false,'message'=>'Current password is incorrect.']);
            }
            if ($newPw !== ($_POST['confirm_new_password'] ?? '')) {
                $this->json(['success'=>false,'message'=>'New passwords do not match.']);
            }
            $upd['password'] = Helper::hashPassword($newPw);
        }

        $avatarFile = null;
        if (!empty($_FILES['avatar']['name'])) {
            $fn = Helper::uploadImage($_FILES['avatar'], 'avatars');
            if ($fn) {
                $upd['avatar']            = $fn;
                $_SESSION['user_avatar']  = $fn;
                $avatarFile               = $fn;
            }
        }

        $this->db->update('users', $upd, "id=$uid");
        $_SESSION['user_name'] = $fullName;

        // Return fresh user data so the page can update in-place
        $fresh = $this->db->selectOne("SELECT id,full_name,email,phone,avatar,created_at FROM users WHERE id=$uid");
        $this->json([
            'success'   => true,
            'message'   => 'Profile updated successfully.',
            'user'      => [
                'full_name'  => $fresh['full_name'],
                'email'      => $fresh['email'],
                'phone'      => $fresh['phone'] ?? '',
                'avatar'     => $fresh['avatar'] ?? null,
                'created_at' => $fresh['created_at'],
            ],
        ]);
    }

    public function addAddress(array $p = []): void {
        CSRF::check();
        $this->requireLogin();
        $uid = Auth::id();
        if (!empty($_POST['is_default'])) {
            $this->db->update('user_addresses', ['is_default'=>0], "user_id=$uid");
        }
        $this->db->insert('user_addresses', [
            'user_id'       => $uid,
            'label'         => Helper::sanitize($_POST['label']         ?? 'Home'),
            'full_name'     => Helper::sanitize($_POST['full_name']     ?? ''),
            'phone'         => Helper::sanitize($_POST['phone']         ?? ''),
            'address_line1' => Helper::sanitize($_POST['address_line1'] ?? ''),
            'address_line2' => Helper::sanitize($_POST['address_line2'] ?? ''),
            'city'          => Helper::sanitize($_POST['city']          ?? ''),
            'state'         => Helper::sanitize($_POST['state']         ?? ''),
            'postal_code'   => Helper::sanitize($_POST['postal_code']   ?? ''),
            'is_default'    => !empty($_POST['is_default']) ? 1 : 0,
        ]);
        $this->json(['success'=>true,'message'=>'Address saved.']);
    }

    public function deleteAddress(array $p = []): void {
        CSRF::check();
        $this->requireLogin();
        $id  = (int)($_POST['address_id'] ?? 0);
        $uid = Auth::id();
        $this->db->delete('user_addresses', "id=$id AND user_id=$uid");
        $this->json(['success'=>true]);
    }

    public function setDefaultAddress(array $p = []): void {
        CSRF::check();
        $this->requireLogin();
        $id  = (int)($_POST['address_id'] ?? 0);
        $uid = Auth::id();
        $this->db->update('user_addresses', ['is_default'=>0], "user_id=$uid");
        $this->db->update('user_addresses', ['is_default'=>1], "id=$id AND user_id=$uid");
        $this->json(['success'=>true]);
    }
}
