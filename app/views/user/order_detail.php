<?php
$pageTitle = 'Order: ' . e($order['order_number']);
$allStatuses = ['pending','confirmed','processing','shipped','delivered'];
$currentIdx  = array_search($order['status'], $allStatuses);
$isCancelled = in_array($order['status'], ['cancelled','refunded']);
$canCancel   = in_array($order['status'], ['pending','confirmed']);
?>

<style>
.acct-card{background:var(--bg-card,#fff);border-radius:14px;box-shadow:0 1px 6px rgba(0,0,0,.07);border:1px solid var(--border,#f0f0f0);}
.timeline-dot{width:38px;height:38px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:14px;position:relative;z-index:1;}
.order-item-border{border-bottom:1px solid var(--border,#eee);}
.order-total-border{border-top:1px solid var(--border,#eee);}
address{color:var(--text,#333);}
.acct-card .text-muted{color:var(--text-muted) !important;}
.acct-card dl dd{color:var(--text) !important;}
.acct-card .fw-semibold,.acct-card .fw-bold{color:var(--text);}
code{background:var(--bg-soft,#f1f3f5) !important;color:var(--primary,#E63946) !important;padding:2px 7px;border-radius:5px;font-size:12px;}
/* ── Mobile fixes ── */
@media (max-width: 575.98px) {
  .order-timeline-wrap { overflow-x: auto; padding-bottom: 8px; }
  .order-timeline-inner { min-width: 340px; }
  .timeline-dot { width: 32px; height: 32px; font-size: 12px; }
  .timeline-label { font-size: 10px !important; }
  .acct-card.p-4 { padding: 1rem !important; }
  .order-totals { max-width: 100% !important; }
}
</style>

<div class="container py-4 py-lg-5">
  <div class="row g-4">

    <div class="col-lg-3">
      <?php include __DIR__ . '/_sidebar.php'; ?>
    </div>

    <div class="col-lg-9">

      <!-- Header -->
      <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
        <div>
          <h5 class="fw-bold mb-0"><?= e($order['order_number']) ?></h5>
          <div class=" small"><?= date('F j, Y \a\t H:i', strtotime($order['created_at'])) ?></div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
          <?php if ($canCancel): ?>
          <button class="btn btn-outline-danger btn-sm btn-cancel-order" data-id="<?= $order['id'] ?>">
            <i class="fa fa-xmark me-1"></i>Cancel
          </button>
          <?php endif; ?>
          <a href="<?= url('invoice/'.$order['id']) ?>" target="_blank" class="btn btn-outline-secondary btn-sm">
            <i class="fa fa-eye me-1"></i>Preview
          </a>
          <a href="<?= url('invoice/'.$order['id'].'/download') ?>" target="_blank" class="btn btn-outline-success btn-sm">
            <i class="fa fa-download me-1"></i>Download PDF
          </a>
          <a href="<?= url('account/orders') ?>" class="btn btn-outline-secondary btn-sm">
            <i class="fa fa-arrow-left me-1"></i>Orders
          </a>
        </div>
      </div>

      <!-- Status -->
      <?php if ($isCancelled): ?>
      <div class="alert alert-<?= $order['status']==='cancelled'?'danger':'secondary' ?> mb-4 rounded-3">
        <i class="fa fa-circle-xmark me-2"></i>
        Order <?= ucfirst($order['status']) ?>
      </div>
      <?php else: ?>
      <div class="acct-card p-4 mb-4">
        <div class="order-timeline-wrap">
          <div class="order-timeline-inner position-relative d-flex justify-content-between">
            <!-- Progress line -->
            <?php $pct = $currentIdx >= 0 ? ($currentIdx / (count($allStatuses)-1)) * 100 : 0; ?>
            <div class="position-absolute" style="top:16px;left:9%;width:82%;height:3px;background:var(--border,#e5e7eb);z-index:0;">
              <div style="width:<?= $pct ?>%;height:100%;background:var(--primary,#E63946);transition:.4s;"></div>
            </div>
            <?php
            $icons = ['fa-clock','fa-check','fa-gear','fa-truck','fa-house'];
            foreach ($allStatuses as $i => $st):
              $done = $i <= $currentIdx;
            ?>
            <div class="text-center" style="z-index:1;">
              <div class="timeline-dot mx-auto"
                   style="background:<?= $done?'var(--primary,#E63946)':'var(--border,#e5e7eb)' ?>;
                          color:<?= $done?'#fff':'var(--text-muted,#9ca3af)' ?>;">
                <i class="fa <?= $icons[$i] ?>"></i>
              </div>
              <div class="mt-2 timeline-label" style="font-size:11px;font-weight:<?= $done?'600':'400' ?>;
                   color:<?= $done?'var(--primary,#E63946)':'var(--text-muted,#9ca3af)' ?>;">
                <?= ucfirst($st) ?>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
      <?php endif; ?>

      <!-- Items -->
      <div class="acct-card p-4 mb-4">
        <h6 class="fw-bold mb-3">Items Ordered</h6>
        <?php foreach ($order['items'] as $item): ?>
        <div class="d-flex align-items-start gap-3 py-3 order-item-border">
          <img src="<?= !empty($item['thumbnail']) ? url('uploads/products/'.e($item['thumbnail'])) : url('assets/img/placeholder.webp') ?>"
               style="width:60px;height:60px;object-fit:cover;border-radius:8px;flex-shrink:0;" alt="">
          <div class="flex-grow-1 min-w-0">
            <div class="fw-semibold" style="line-height:1.35;"><?= e($item['product_name']) ?></div>
            <?php if (!empty($item['variation_name'])): ?>
            <div class="text-muted small"><?= e($item['variation_name']) ?></div>
            <?php endif; ?>
            <div class="d-flex justify-content-between align-items-center gap-2 mt-1 flex-wrap">
              <div class="text-muted small">
                Qty: <?= (int)$item['quantity'] ?> × LKR <?= number_format($item['unit_price'],2) ?>
              </div>
              <div class="fw-bold text-nowrap small">LKR <?= number_format($item['total_price'],2) ?></div>
            </div>
          </div>
        </div>
        <?php endforeach; ?>

        <!-- Totals -->
        <div class="mt-4 ms-auto order-totals" style="max-width:280px;">
          <div class="d-flex justify-content-between mb-2 small">
            <span class="text-muted">Subtotal</span>
            <span>LKR <?= number_format($order['subtotal'],2) ?></span>
          </div>
          <?php if ((float)($order['discount_amount'] ?? 0) > 0): ?>
          <div class="d-flex justify-content-between mb-2 small text-success">
            <span>Discount</span>
            <span>−LKR <?= number_format($order['discount_amount'],2) ?></span>
          </div>
          <?php endif; ?>
          <div class="d-flex justify-content-between mb-2 small">
            <span class="text-muted">Shipping</span>
            <span><?= (float)($order['shipping_amount']??0) > 0 ? 'LKR '.number_format($order['shipping_amount'],2) : 'Free' ?></span>
          </div>
          <div class="d-flex justify-content-between fw-bold order-total-border pt-2 mt-1">
            <span>Total</span>
            <span style="color:var(--primary,#E63946);">LKR <?= number_format($order['total_amount'],2) ?></span>
          </div>
        </div>
      </div>

      <!-- Shipping + Payment -->
      <div class="row g-3">
        <div class="col-md-6">
          <div class="acct-card p-4 h-100">
            <h6 class="fw-bold mb-3"><i class="fa fa-location-dot me-2" style="color:var(--primary,#E63946);"></i>Delivery Address</h6>
            <address class="mb-0 small lh-lg">
              <strong><?= e($order['shipping_name']) ?></strong><br>
              <?= e($order['shipping_phone']) ?><br>
              <?= e($order['shipping_address']) ?><br>
              <?= e($order['shipping_city']) ?>
              <?php if (!empty($order['shipping_state'])): ?>, <?= e($order['shipping_state']) ?><?php endif; ?><br>
              <?= e($order['shipping_country'] ?? 'Sri Lanka') ?>
            </address>
          </div>
        </div>
        <div class="col-md-6">
          <div class="acct-card p-4 h-100">
            <h6 class="fw-bold mb-3"><i class="fa fa-credit-card me-2" style="color:var(--primary,#E63946);"></i>Payment</h6>
            <dl class="row row-cols-2 g-1 mb-0 small lh-lg">
              <dt class="text-muted fw-bold">Method</dt>
              <dd class="fw-semibold"><?= ucwords(str_replace('_',' ',$order['payment_method'])) ?></dd>
              <dt class="text-muted fw-normal">Status</dt>
              <dd>
                <?php $ops = $order['payment_status'] ?? 'unpaid'; ?>
                <span class="badge <?= $ops==='paid'?'bg-success':'bg-warning text-dark' ?>">
                  <?= ucfirst($ops) ?>
                </span>
              </dd>
              <?php if (!empty($order['coupon_code'])): ?>
              <dt class="text-muted fw-normal">Coupon</dt>
              <dd><code><?= e($order['coupon_code']) ?></code></dd>
              <?php endif; ?>
              <?php if (!empty($order['shipping_method'])): ?>
              <dt class="text-muted fw-normal">Shipping</dt>
              <dd><?= e($order['shipping_method']) ?></dd>
              <?php endif; ?>
            </dl>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<?php $extraScript = <<<'JS'
<script>
(function () {
  var btn = document.querySelector('.btn-cancel-order');
  if (!btn) return;
  btn.addEventListener('click', function () {
    var id = this.dataset.id;
    Swal.fire({
      title: 'Cancel this order?',
      text:  'This cannot be undone.',
      icon:  'warning',
      showCancelButton:    true,
      confirmButtonColor:  '#e74c3c',
      confirmButtonText:   'Yes, cancel it',
      cancelButtonText:    'Keep it'
    }).then(function (r) {
      if (!r.isConfirmed) return;
      apPost('account/order/' + id + '/cancel', {}, function (res) {
        if (res.success) {
          Swal.fire({ icon: 'success', title: res.message, timer: 1500, showConfirmButton: false })
            .then(function () { window.location.href = res.redirect || (SITE_URL + '/account/orders'); });
        } else {
          Swal.fire('Error', res.message, 'error');
        }
      });
    });
  });
})();
</script>
JS;
?>
