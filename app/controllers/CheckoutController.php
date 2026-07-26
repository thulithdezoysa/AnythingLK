<?php
require_once CORE . '/Controller.php';
require_once APP  . '/models/CartModel.php';
require_once APP  . '/models/OrderModel.php';

class CheckoutController extends Controller {

    public function index(array $params = []): void {
        $cart = new CartModel();
        $data = $cart->totals();
        if (empty($data['items'])) { $this->redirect('cart'); }

        $shipping        = $this->db->select("SELECT * FROM shipping_methods WHERE status=1 ORDER BY sort_order ASC, price ASC") ?: [];
        $preselectedShip = (int)($_GET['ship'] ?? 0);
        $allMethods   = $this->db->select("SELECT * FROM payment_methods WHERE is_active=1 ORDER BY sort_order") ?: [
            ['code'=>'cod',     'display_name'=>'Cash on Delivery',  'description'=>'Pay when your order arrives.',           'icon'=>'💵'],
            ['code'=>'koko',    'display_name'=>'KOKO',              'description'=>'Split into 3 interest-free payments.',   'icon'=>'🟠'],
            ['code'=>'payzy',   'display_name'=>'Payzy',             'description'=>'Secure online payment gateway.',         'icon'=>'💳'],
            ['code'=>'mintpay', 'display_name'=>'MintPay',           'description'=>'Pay in 3 or 6 easy installments.',       'icon'=>'💚'],
        ];

        // Cart-level intersection of allowed methods
        $allowedCodes = $cart->allowedPaymentMethodCodes(); // empty = no restrictions
        $paymentMethods = [];
        $firstAllowed   = null;
        foreach ($allMethods as $pm) {
            $pm['is_allowed'] = empty($allowedCodes) || in_array($pm['code'], $allowedCodes);
            if ($pm['is_allowed'] && $firstAllowed === null) $firstAllowed = $pm['code'];
            $paymentMethods[] = $pm;
        }
        $noCommonMethod = !empty($allowedCodes) && $firstAllowed === null;

        $addresses = [];
        if (Auth::check()) {
            $uid       = Auth::id();
            $addresses = $this->db->select("SELECT * FROM user_addresses WHERE user_id=$uid ORDER BY is_default DESC") ?: [];
        }
        $this->view('checkout/index', compact('data','shipping','paymentMethods','addresses','allowedCodes','noCommonMethod','preselectedShip'));
    }

    public function placeOrder(array $params = []): void {
        CSRF::check();

        $cart  = new CartModel();
        $items = $cart->getItems();
        if (empty($items)) { $this->json(['success'=>false,'message'=>'Your cart is empty.']); }

        // Validate required fields
        $required = ['full_name','phone','address','city'];
        foreach ($required as $f) {
            if (empty(trim($_POST[$f] ?? ''))) {
                $this->json(['success'=>false,'message'=>"Please fill in: " . ucfirst(str_replace('_',' ',$f))]);
            }
        }

        // Validate phone format
        $phone = trim($_POST['phone'] ?? '');
        if (!preg_match('/^[\d\+\-\s\(\)]{7,15}$/', $phone)) {
            $this->json(['success'=>false,'message'=>'Please enter a valid phone number.']);
        }

        // Validate payment method is globally active
        $dbMethods = $this->db->select("SELECT code FROM payment_methods WHERE is_active=1") ?: [];
        $globalAllowed = !empty($dbMethods) ? array_column($dbMethods, 'code') : ['cod','koko','mintpay','payzy'];
        $selectedMethod = $_POST['payment_method'] ?? 'cod';
        if (!in_array($selectedMethod, $globalAllowed)) {
            $this->json(['success'=>false,'message'=>'Invalid payment method selected.']);
        }
        // Validate against cart-level product restrictions
        $cartAllowed = $cart->allowedPaymentMethodCodes();
        if (!empty($cartAllowed) && !in_array($selectedMethod, $cartAllowed)) {
            $this->json(['success'=>false,'message'=>'The selected payment method is not available for all items in your cart.']);
        }

        // Check stock
        foreach ($items as $item) {
            if ($item['track_stock'] && $item['stock_qty'] < $item['quantity']) {
                $this->json(['success'=>false,'message'=>"Sorry, \"{$item['name']}\" only has {$item['stock_qty']} in stock."]);
            }
        }

        $shippingId    = (int)($_POST['shipping_method_id'] ?? 0);
        $paymentMethod = Helper::sanitize($_POST['payment_method'] ?? 'cod');

        // Enforce discount exclusions for KOKO / Payzy / MintPay
        if (DiscountEngine::isExcluded($paymentMethod)) {
            unset($_SESSION['coupon']);
        }

        $data = $cart->totals($shippingId, $paymentMethod);
        $post = $_POST;

        // Resolve shipping method name for order record
        if ($shippingId) {
            $sm = $this->db->selectOne("SELECT name FROM shipping_methods WHERE id=$shippingId AND status=1");
            if ($sm) $post['shipping_method'] = $sm['name'];
        }
        if (empty($post['shipping_method'])) $post['shipping_method'] = 'Standard';
        $post['user_id'] = Auth::id();

        $totalDiscount = $data['discount'] + ($data['bogoSavings'] ?? 0);

        $om      = new OrderModel();
        $orderId = $om->create($post, $items, $data['subtotal'], $totalDiscount, $data['shipping']);

        if (!$orderId) {
            $this->json(['success'=>false,'message'=>'Failed to place order. Please try again.']);
        }

        // Send confirmation email
        $order = $om->getById($orderId);
        try { MailService::sendOrderConfirmation($order, $order['items']); } catch (\Throwable $e) {}

        $cart->clear();
        unset($_SESSION['coupon']);

        $this->json(['success'=>true,'redirect'=>url("checkout/success/$orderId")]);
    }

    public function success(array $params): void {
        $id    = (int)($params['id'] ?? 0);
        $order = (new OrderModel())->getById($id);
        if (!$order) { http_response_code(404); $this->view('errors/404'); return; }
        if ($order['user_id'] && Auth::id() && (int)$order['user_id'] !== Auth::id()) {
            $this->redirect('account/orders');
        }
        $this->view('checkout/success', compact('order'));
    }

    public function invoice(array $params): void {
        $id    = (int)($params['id'] ?? 0);
        $order = (new OrderModel())->getById($id);
        if (!$order) { http_response_code(404); echo "Order not found."; return; }
        if ($order['user_id'] && Auth::id() && (int)$order['user_id'] !== Auth::id()) {
            if (!Auth::isAdmin()) { $this->redirect('login'); }
        }
        (new InvoiceGenerator($order, $order['items']))->stream();
    }

    public function download(array $params): void {
        $id    = (int)($params['id'] ?? 0);
        $order = (new OrderModel())->getById($id);
        if (!$order) { http_response_code(404); echo "Order not found."; return; }
        if ($order['user_id'] && Auth::id() && (int)$order['user_id'] !== Auth::id()) {
            if (!Auth::isAdmin()) { $this->redirect('login'); }
        }
        (new InvoiceGenerator($order, $order['items']))->streamDownload();
    }
}
