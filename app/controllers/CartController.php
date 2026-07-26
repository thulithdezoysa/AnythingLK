<?php
require_once CORE . '/Controller.php';
require_once APP  . '/models/CartModel.php';

class CartController extends Controller {

    public function index(array $params = []): void {
        $cart     = new CartModel();
        $data     = $cart->totals();
        $shipping = $this->db->select("SELECT * FROM shipping_methods WHERE status=1 ORDER BY sort_order ASC, price ASC") ?: [];
        $this->view('cart/index', compact('data','shipping'));
    }

    public function add(array $params = []): void {
        CSRF::check();
        $pid = (int)($_POST['product_id'] ?? 0);
        $qty = max(1, (int)($_POST['quantity'] ?? 1));
        $vid = (int)($_POST['variation_id'] ?? 0) ?: null;
        if (!$pid) { $this->json(['success'=>false,'message'=>'Invalid product.']); }

        // Check stock
        $pm = new ProductModel();
        $stock = $pm->getStock($pid, $vid);
        $inCart = (new CartModel())->getQtyInCart($pid, $vid);
        if ($stock > 0 && ($inCart + $qty) > $stock) {
            $this->json(['success'=>false,'message'=>"Only $stock available (you have $inCart in cart)."]);
        }

        $cart   = new CartModel();
        $result = $cart->add($pid, $qty, $vid);
        $this->json(['success'=>true,'message'=>'Added to cart! 🛒','count'=>$result['count']]);
    }

    public function update(array $params = []): void {
        CSRF::check();
        $cartId = (int)($_POST['cart_id'] ?? 0);
        $qty    = (int)($_POST['quantity'] ?? 0);
        $cart   = new CartModel();
        $cart->updateQty($cartId, $qty);
        $data      = $cart->totals();
        $lineTotal = 0;
        foreach ($data['items'] as $item) {
            if ((int)$item['id'] === $cartId) { $lineTotal = $item['line_total']; break; }
        }
        $this->json([
            'success'    => true,
            'subtotal'   => $data['subtotal'],
            'discount'   => $data['discount'],
            'total'      => $data['total'],
            'count'      => $cart->count(),
            'line_total' => $lineTotal,
        ]);
    }

    public function remove(array $params = []): void {
        CSRF::check();
        $cartId = (int)($_POST['cart_id'] ?? 0);
        (new CartModel())->removeById($cartId);
        $cart = new CartModel();
        $data = $cart->totals();
        $this->json([
            'success'  => true,
            'count'    => $cart->count(),
            'subtotal' => $data['subtotal'],
            'discount' => $data['discount'],
            'total'    => $data['total'],
        ]);
    }

    public function applyCoupon(array $params = []): void {
        CSRF::check();
        $code = $_POST['coupon'] ?? '';
        $cart = new CartModel();
        $this->json($cart->applyCoupon($code, $cart->subtotal()));
    }

    public function removeCoupon(array $params = []): void {
        CSRF::check();
        unset($_SESSION['coupon']);
        $cart = new CartModel();
        $data = $cart->totals();
        $this->json(['success'=>true,'count'=>$cart->count(),'subtotal'=>$data['subtotal'],'total'=>$data['total']]);
    }

    public function count(array $params = []): void {
        $this->json(['count' => (new CartModel())->count()]);
    }

    // Mini cart panel HTML
    public function mini(array $params = []): void {
        $cart  = new CartModel();
        $items = $cart->getItems();
        $total = $cart->subtotal();
        $count = $cart->count();
        ob_start();
        foreach ($items as $item):
            $thumb = !empty($item['thumbnail']) ? url('uploads/products/'.e($item['thumbnail'])) : url('assets/img/placeholder.webp');
        ?>
        <div class="d-flex align-items-center gap-3 p-3 border-bottom">
          <img src="<?= $thumb ?>" style="width:56px;height:56px;object-fit:cover;border-radius:8px;" alt="">
          <div class="flex-grow-1 overflow-hidden">
            <a href="<?= url('product/'.e($item['slug'])) ?>" class="text-decoration-none text-dark fw-semibold" style="font-size:13px;"><?= e(Helper::truncate($item['name'],35)) ?></a>
            <?php if ($item['variation_name']): ?><div class="text-muted" style="font-size:11px;"><?= e($item['variation_name']) ?></div><?php endif; ?>
            <div class="d-flex align-items-center gap-2 mt-1">
              <span class="fw-bold small" style="color:var(--primary);">LKR <?= number_format($item['effective_price'],2) ?></span>
              <span class="text-muted small">× <?= $item['quantity'] ?></span>
            </div>
          </div>
          <button class="btn btn-sm btn-outline-danger mini-cart-remove py-0 px-2" data-cart-id="<?= $item['id'] ?>"><i class="fa fa-times"></i></button>
        </div>
        <?php endforeach;
        if (empty($items)): ?>
        <div class="p-5 text-center text-muted">
          <i class="fa fa-shopping-cart fa-3x mb-3 d-block"></i>
          <p>Your cart is empty.</p>
          <a href="<?= url('products') ?>" class="btn btn-primary btn-sm">Start Shopping</a>
        </div>
        <?php endif;
        $html = ob_get_clean();
        $this->json(['html'=>$html,'count'=>$count,'total'=>$total]);
    }

    public function items(array $params = []): void {
        $cart  = new CartModel();
        $items = $cart->getItems();
        $count = $cart->count();
        $this->json(['items'=>$items,'count'=>$count,'subtotal'=>$cart->subtotal()]);
    }
}
