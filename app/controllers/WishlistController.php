<?php
require_once CORE . '/Controller.php';

class WishlistController extends Controller {

    public function toggle(array $params = []): void {
        if (!Auth::check()) {
            $this->json(['success'=>false,'message'=>'Please login to use your wishlist.','login'=>true]);
        }
        CSRF::check();
        $pid = (int)($_POST['product_id'] ?? 0);
        $uid = Auth::id();
        if (!$pid) { $this->json(['success'=>false,'message'=>'Invalid product.']); }

        $exists = $this->db->selectOne("SELECT id FROM wishlists WHERE user_id=$uid AND product_id=$pid");
        if ($exists) {
            $this->db->delete('wishlists', "user_id=$uid AND product_id=$pid");
            $this->json(['success'=>true,'in_wishlist'=>false,'message'=>'Removed from wishlist.','wish_count'=>$this->count($uid)]);
        } else {
            $this->db->insert('wishlists', ['user_id'=>$uid,'product_id'=>$pid,'added_at'=>date('Y-m-d H:i:s')]);
            $this->json(['success'=>true,'in_wishlist'=>true,'message'=>'Added to wishlist! ❤️','wish_count'=>$this->count($uid)]);
        }
    }

    public function list(array $params = []): void {
        if (!Auth::check()) { $this->json(['ids'=>[]]); }
        $uid  = Auth::id();
        $rows = $this->db->select("SELECT product_id FROM wishlists WHERE user_id=$uid") ?: [];
        $this->json(['ids' => array_column($rows,'product_id')]);
    }

    public function mini(array $params = []): void {
        if (!Auth::check()) { $this->json(['html'=>'<div class="p-4 text-center text-muted">Please <a href="'.url('login').'">login</a> to view your wishlist.</div>','count'=>0]); }
        $uid   = Auth::id();
        $items = $this->db->select(
            "SELECT w.*, p.name, p.slug, p.thumbnail, p.price, p.sale_price
             FROM wishlists w JOIN products p ON p.id=w.product_id
             WHERE w.user_id=$uid ORDER BY w.added_at DESC LIMIT 8"
        ) ?: [];
        $count = $this->count($uid);
        ob_start();
        foreach ($items as $item):
            $price = (float)($item['sale_price'] ?: $item['price']);
            $thumb = $item['thumbnail'] ? url('uploads/products/'.e($item['thumbnail'])) : url('assets/img/placeholder.webp');
        ?>
        <div class="d-flex align-items-center gap-3 p-3 border-bottom mini-wish-item" data-pid="<?= $item['product_id'] ?>">
          <img src="<?= $thumb ?>" style="width:56px;height:56px;object-fit:cover;border-radius:8px;" alt="">
          <div class="flex-grow-1 overflow-hidden">
            <a href="<?= url('product/'.e($item['slug'])) ?>" class="text-decoration-none text-dark fw-semibold" style="font-size:13px;display:block;" class="text-truncate"><?= e(Helper::truncate($item['name'],35)) ?></a>
            <div class="fw-bold small" style="color:var(--primary);">LKR <?= number_format($price,2) ?></div>
          </div>
          <div class="d-flex flex-column gap-1">
            <button class="btn btn-sm btn-primary mini-add-cart py-0 px-2" data-pid="<?= $item['product_id'] ?>" title="Add to Cart"><i class="fa fa-cart-plus"></i></button>
            <button class="btn btn-sm btn-outline-danger mini-rm-wish py-0 px-2" data-pid="<?= $item['product_id'] ?>" title="Remove"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <?php endforeach;
        if (empty($items)): ?>
        <div class="p-5 text-center text-muted">
          <i class="fa fa-heart-o fa-3x mb-3 d-block"></i>
          <p>Your wishlist is empty.</p>
          <a href="<?= url('products') ?>" class="btn btn-primary btn-sm">Browse Products</a>
        </div>
        <?php endif;
        $html = ob_get_clean();
        $this->json(['html'=>$html,'count'=>$count]);
    }

    private function count(int $uid): int {
        return (int)($this->db->selectOne("SELECT COUNT(*) AS c FROM wishlists WHERE user_id=$uid")['c'] ?? 0);
    }

    public function items(array $params = []): void {
        if (!Auth::check()) { $this->json(['items'=>[]]); }
        $uid  = Auth::id();
        $rows = $this->db->select(
            "SELECT w.product_id, p.name, p.slug, p.thumbnail, p.price, p.sale_price
             FROM wishlists w JOIN products p ON p.id=w.product_id
             WHERE w.user_id=$uid ORDER BY w.added_at DESC LIMIT 20"
        ) ?: [];
        $count = count($rows);
        $this->json(['items'=>$rows,'count'=>$count]);
    }
}
