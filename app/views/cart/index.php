<?php $pageTitle = 'Shopping Cart'; ?>

<style>
/* ── Cart Page ───────────────────────────────────────────── */
.cart-page { background: var(--bg-soft); min-height: 70vh; }

/* breadcrumb */
.cart-crumb { font-size: .82rem; color: var(--text-muted); }
.cart-crumb a { color: var(--text-muted); text-decoration: none; }
.cart-crumb a:hover { color: var(--brand); }
.cart-crumb .sep { margin: 0 .4rem; opacity: .4; }

/* panels */
.cart-panel {
  background: var(--bg-card);
  border: 1px solid var(--border);
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow);
  overflow: hidden;
}

/* ── item row ── */
.cart-item {
  display: grid;
  grid-template-columns: 76px 1fr auto auto auto;
  gap: 1rem;
  align-items: center;
  padding: 1.1rem 1.25rem;
  border-bottom: 1px solid var(--border);
  transition: background .18s;
}
.cart-item:last-child { border-bottom: 0; }
.cart-item:hover { background: var(--bg-soft); }

.cart-item-img {
  width: 76px; height: 76px;
  object-fit: cover;
  border-radius: 10px;
  border: 1px solid var(--border);
  flex-shrink: 0;
}

.cart-item-name {
  font-weight: 600;
  font-size: .9rem;
  color: var(--text);
  text-decoration: none;
  line-height: 1.35;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
.cart-item-name:hover { color: var(--brand); }

.cart-item-meta { font-size: .76rem; color: var(--text-muted); margin-top: .2rem; }
.cart-var-badge {
  display: inline-block;
  font-size: .72rem;
  padding: 1px 8px;
  border-radius: 20px;
  background: var(--bg-soft);
  border: 1px solid var(--border);
  color: var(--text-muted);
  margin-top: .25rem;
}
.cart-item-price { font-size: .85rem; color: var(--text-muted); white-space: nowrap; text-align: right; }
.cart-item-price strong { display: block; font-size: .95rem; color: var(--text); }

.cart-item-total { font-size: 1rem; font-weight: 700; color: var(--text); white-space: nowrap; text-align: right; min-width: 110px; }

/* qty spinner */
.qty-wrap { display: flex; align-items: center; gap: 0; border: 1px solid var(--border); border-radius: 8px; overflow: hidden; background: var(--bg-card); }
.qty-btn {
  width: 34px; height: 34px;
  background: var(--bg-soft);
  border: none;
  color: var(--text);
  font-size: 1rem;
  line-height: 1;
  cursor: pointer;
  transition: background .15s, color .15s;
  flex-shrink: 0;
}
.qty-btn:hover { background: var(--brand); color: #fff; }
.qty-input {
  width: 44px; height: 34px;
  border: none;
  border-left: 1px solid var(--border);
  border-right: 1px solid var(--border);
  text-align: center;
  font-weight: 700;
  font-size: .88rem;
  background: transparent;
  color: var(--text);
  -moz-appearance: textfield;
}
.qty-input::-webkit-outer-spin-button,
.qty-input::-webkit-inner-spin-button { -webkit-appearance: none; }

.cart-remove-btn {
  width: 32px; height: 32px;
  border-radius: 8px;
  border: 1px solid var(--border);
  background: transparent;
  color: var(--text-muted);
  display: flex; align-items: center; justify-content: center;
  cursor: pointer;
  transition: all .15s;
  font-size: .8rem;
}
.cart-remove-btn:hover { background: rgba(230,57,70,.1); border-color: var(--brand); color: var(--brand); }

/* stock badges */
.stock-out  { font-size: .72rem; color: #e74c3c; }
.stock-low  { font-size: .72rem; color: #f39c12; }

/* ── Coupon section ── */
.coupon-section { padding: 1.1rem 1.25rem; }
.coupon-applied-box {
  display: flex; align-items: center; gap: .75rem;
  background: rgba(46,196,182,.08);
  border: 1.5px solid rgba(46,196,182,.35);
  border-radius: 10px;
  padding: .7rem 1rem;
}
.coupon-input-group { display: flex; gap: .5rem; }
.coupon-input-group input {
  flex: 1; max-width: 220px;
  height: 38px;
  border: 1.5px solid var(--border);
  border-radius: 8px;
  padding: 0 .85rem;
  font-size: .875rem;
  background: var(--bg-soft);
  color: var(--text);
  outline: none;
  text-transform: uppercase;
  letter-spacing: .05em;
  transition: border-color .2s;
}
.coupon-input-group input:focus { border-color: var(--brand); }
.coupon-input-group input::placeholder { text-transform: none; letter-spacing: 0; opacity: .6; }
.coupon-apply-btn {
  height: 38px; padding: 0 1.1rem;
  border-radius: 8px;
  border: none;
  background: var(--grad-brand);
  color: #fff;
  font-weight: 600;
  font-size: .85rem;
  cursor: pointer;
  transition: opacity .2s;
}
.coupon-apply-btn:hover { opacity: .88; }
.coupon-apply-btn:disabled { opacity: .6; cursor: not-allowed; }

/* ── Summary card ── */
.summary-sticky { position: sticky; top: 82px; }
.summary-panel { background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-lg); box-shadow: var(--shadow); }
.summary-panel .s-head { padding: 1.2rem 1.4rem; border-bottom: 1px solid var(--border); }
.summary-panel .s-body { padding: 1.2rem 1.4rem; }
.summary-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: .6rem; font-size: .88rem; }
.summary-row .label { color: var(--text-muted); }
.summary-row .val { font-weight: 600; color: var(--text); }
.summary-row.saving .label { color: #2ec4b6; }
.summary-row.saving .val { color: #2ec4b6; }
.summary-total { display: flex; justify-content: space-between; align-items: center; padding-top: .8rem; margin-top: .4rem; border-top: 2px solid var(--border); }
.summary-total .t-label { font-size: 1rem; font-weight: 700; color: var(--text); }
.summary-total .t-val { font-size: 1.2rem; font-weight: 800; color: var(--brand); }

/* shipping options */
.ship-option {
  display: flex; align-items: center; justify-content: space-between;
  border: 1.5px solid var(--border);
  border-radius: 10px;
  padding: .6rem .85rem;
  margin-bottom: .5rem;
  cursor: pointer;
  transition: all .18s;
}
.ship-option:last-child { margin-bottom: 0; }
.ship-option:has(input:checked) { border-color: var(--brand); background: rgba(230,57,70,.05); }
.ship-option input { accent-color: var(--brand); }
.ship-option .ship-name { font-size: .85rem; font-weight: 600; color: var(--text); margin-bottom: 1px; }
.ship-option .ship-days { font-size: .72rem; color: var(--text-muted); }
.ship-cost { font-size: .85rem; font-weight: 700; }
.ship-cost.free { color: #2ec4b6; }

.checkout-btn {
  display: block; width: 100%;
  padding: .85rem;
  border-radius: 10px;
  background: var(--grad-brand);
  color: #fff;
  font-weight: 700;
  font-size: 1rem;
  text-align: center;
  text-decoration: none;
  border: none;
  cursor: pointer;
  letter-spacing: .02em;
  transition: opacity .2s, transform .15s;
  box-shadow: 0 4px 16px rgba(230,57,70,.3);
}
.checkout-btn:hover { opacity: .92; transform: translateY(-1px); color: #fff; }

.trust-badges { display: flex; justify-content: center; gap: 1.2rem; margin-top: .85rem; }
.trust-badge { font-size: .72rem; color: var(--text-muted); display: flex; align-items: center; gap: .28rem; }

/* ── Empty state ── */
.cart-empty {
  text-align: center;
  padding: 4rem 2rem;
  background: var(--bg-card);
  border: 1px solid var(--border);
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow);
}
.cart-empty-icon {
  width: 90px; height: 90px;
  border-radius: 50%;
  background: var(--bg-soft);
  display: flex; align-items: center; justify-content: center;
  margin: 0 auto 1.5rem;
  font-size: 2.2rem;
}

/* ── Mobile ── */
@media (max-width: 767px) {
  .cart-item {
    grid-template-columns: 64px 1fr auto;
    grid-template-rows: auto auto;
    row-gap: .6rem;
  }
  .cart-item-img { width: 64px; height: 64px; }
  .cart-item-price { display: none; }
  .cart-item-total { grid-column: 2; grid-row: 2; text-align: left; font-size: .9rem; }
  .cart-item-qty   { grid-column: 3; grid-row: 2; }
  .cart-remove-btn { grid-column: 3; grid-row: 1; }
  .coupon-input-group input { max-width: 100%; flex: 1; }
}
@media (max-width: 480px) {
  .cart-item { padding: .85rem 1rem; }
  .summary-panel .s-body, .summary-panel .s-head { padding: 1rem; }
}
</style>

<div class="cart-page py-4 py-lg-5">
<div class="container">

  <!-- Breadcrumb + header -->
  <div class="mb-3">
    <div class="cart-crumb mb-2">
      <a href="<?= url('') ?>">Home</a>
      <span class="sep">›</span>
      <span>Shopping Cart</span>
    </div>
    <div class="d-flex align-items-center justify-content-between gap-2">
      <h2 class="fw-bold mb-0" style="color:var(--text);font-family:'Outfit',sans-serif;">
        My Cart
        <?php if (!empty($data['items'])): ?>
        <span class="badge rounded-pill ms-2" style="background:var(--brand);font-size:.6em;vertical-align:middle;">
          <?= array_sum(array_column($data['items'],'quantity')) ?> items
        </span>
        <?php endif; ?>
      </h2>
      <a href="<?= url('products') ?>" class="d-none d-sm-flex align-items-center gap-1"
         style="font-size:.85rem;color:var(--text-muted);text-decoration:none;">
        <i class="fa fa-arrow-left fa-xs"></i> Continue Shopping
      </a>
    </div>
  </div>

  <?php if (empty($data['items'])): ?>
  <!-- ── Empty state ─────────────────────────── -->
  <div class="cart-empty">
    <div class="cart-empty-icon">
      <i class="fa fa-cart-shopping" style="color:var(--brand);"></i>
    </div>
    <h4 class="fw-bold mb-2" style="color:var(--text);font-family:'Outfit',sans-serif;">Your cart is empty</h4>
    <p style="color:var(--text-muted);" class="mb-4">Looks like you haven't added anything yet. Browse our collection!</p>
    <a href="<?= url('products') ?>" class="checkout-btn d-inline-block px-5" style="width:auto;">
      <i class="fa fa-bag-shopping me-2"></i>Start Shopping
    </a>
  </div>

  <?php else: ?>

  <div id="cartMeta"
       data-subtotal="<?= (float)$data['subtotal'] ?>"
       data-discount="<?= (float)$data['discount'] ?>"
       hidden></div>

  <div class="row g-4 align-items-start">

    <!-- ── LEFT: Items + Coupon ──────────────── -->
    <div class="col-lg-8">

      <!-- Items panel -->
      <div class="cart-panel">
        <!-- Panel header -->
        <div style="padding:.9rem 1.25rem;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;">
          <span style="font-weight:700;font-size:.92rem;color:var(--text);">
            <i class="fa fa-bag-shopping me-2" style="color:var(--brand);"></i>Cart Items
          </span>
          <span style="font-size:.78rem;color:var(--text-muted);"><?= count($data['items']) ?> product<?= count($data['items'])!==1?'s':'' ?></span>
        </div>

        <!-- Items -->
        <div id="cartItemsWrap">
          <?php foreach ($data['items'] as $item): ?>
          <div class="cart-item" id="cartRow-<?= $item['id'] ?>">

            <!-- Thumb -->
            <a href="<?= url('product/'.e($item['slug'])) ?>" style="grid-row:1/3;">
              <img class="cart-item-img"
                   src="<?= !empty($item['thumbnail']) ? url('uploads/products/'.e($item['thumbnail'])) : url('assets/img/placeholder.webp') ?>"
                   alt="<?= e($item['name']) ?>">
            </a>

            <!-- Info -->
            <div style="min-width:0;">
              <a href="<?= url('product/'.e($item['slug'])) ?>" class="cart-item-name"><?= e($item['name']) ?></a>
              <?php if (!empty($item['variation_name'])): ?>
              <span class="cart-var-badge"><?= e($item['variation_name']) ?></span>
              <?php endif; ?>
              <?php if ($item['track_stock'] && $item['stock_qty'] <= 0): ?>
              <div class="stock-out mt-1"><i class="fa fa-circle-xmark me-1"></i>Out of stock</div>
              <?php elseif ($item['track_stock'] && $item['stock_qty'] < $item['quantity']): ?>
              <div class="stock-low mt-1"><i class="fa fa-triangle-exclamation me-1"></i>Only <?= (int)$item['stock_qty'] ?> left</div>
              <?php endif; ?>
            </div>

            <!-- Unit price -->
            <div class="cart-item-price">
              <span style="font-size:.72rem;text-transform:uppercase;letter-spacing:.04em;">Unit price</span>
              <strong>LKR <?= number_format($item['effective_price'],2) ?></strong>
            </div>

            <!-- Qty -->
            <div class="cart-item-qty">
              <div class="qty-wrap">
                <button class="qty-btn" data-cart-id="<?= $item['id'] ?>" data-action="minus" title="Decrease">−</button>
                <input type="number" class="qty-input cart-qty-input"
                       value="<?= (int)$item['quantity'] ?>" min="1"
                       data-cart-id="<?= $item['id'] ?>" aria-label="Quantity">
                <button class="qty-btn" data-cart-id="<?= $item['id'] ?>" data-action="plus" title="Increase">+</button>
              </div>
            </div>

            <!-- Line total -->
            <div class="cart-item-total cart-line-<?= $item['id'] ?>">
              LKR <?= number_format($item['line_total'],2) ?>
            </div>

            <!-- Remove -->
            <button class="cart-remove-btn cart-remove" data-cart-id="<?= $item['id'] ?>" title="Remove item">
              <i class="fa fa-xmark"></i>
            </button>

          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Coupon panel -->
      <div class="cart-panel mt-3">
        <div style="padding:.9rem 1.25rem;border-bottom:1px solid var(--border);">
          <span style="font-weight:700;font-size:.92rem;color:var(--text);">
            <i class="fa fa-tag me-2" style="color:var(--brand);"></i>Coupon Code
          </span>
        </div>
        <div class="coupon-section">
          <?php if (!empty($_SESSION['coupon'])): ?>
          <div class="coupon-applied-box">
            <i class="fa fa-circle-check fa-lg" style="color:#2ec4b6;flex-shrink:0;"></i>
            <div style="flex:1;min-width:0;">
              <div style="font-weight:700;font-size:.9rem;color:var(--text);">
                <?= e($_SESSION['coupon']['code']) ?>
                <span style="font-weight:400;font-size:.8rem;color:var(--text-muted);margin-left:.3rem;">applied</span>
              </div>
              <div style="font-size:.8rem;color:#2ec4b6;font-weight:600;">
                You save LKR <?= number_format($_SESSION['coupon']['discount'],2) ?>
              </div>
            </div>
            <button id="removeCouponBtn" type="button"
                    style="border:1px solid rgba(230,57,70,.4);background:transparent;color:var(--brand);font-size:.78rem;padding:.3rem .75rem;border-radius:7px;cursor:pointer;white-space:nowrap;transition:all .15s;">
              Remove
            </button>
          </div>
          <?php else: ?>
          <div class="coupon-input-group">
            <input type="text" id="couponInput" placeholder="Enter coupon code">
            <button class="coupon-apply-btn" id="applyCouponBtn" type="button">Apply</button>
          </div>
          <div id="couponMsg" style="font-size:.8rem;margin-top:.5rem;"></div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Continue shopping (mobile) -->
      <div class="d-sm-none mt-3">
        <a href="<?= url('products') ?>"
           style="display:flex;align-items:center;justify-content:center;gap:.4rem;padding:.7rem;border:1px solid var(--border);border-radius:10px;font-size:.85rem;color:var(--text-muted);text-decoration:none;background:var(--bg-card);">
          <i class="fa fa-arrow-left fa-xs"></i> Continue Shopping
        </a>
      </div>

    </div><!-- /col-lg-8 -->

    <!-- ── RIGHT: Summary ────────────────────── -->
    <div class="col-lg-4">
      <div class="summary-sticky">
        <div class="summary-panel" id="orderSummary">

          <div class="s-head">
            <span style="font-weight:700;font-size:.95rem;color:var(--text);">Order Summary</span>
          </div>

          <div class="s-body">

            <!-- Shipping -->
            <div style="margin-bottom:1.1rem;">
              <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--text-muted);margin-bottom:.6rem;">
                Delivery Method
              </div>
              <?php if (empty($shipping)): ?>
              <div style="padding:.6rem .85rem;background:rgba(46,196,182,.07);border:1px solid rgba(46,196,182,.2);border-radius:9px;font-size:.82rem;color:var(--text-muted);display:flex;align-items:center;gap:.5rem;">
                <i class="fa fa-truck" style="color:#2ec4b6;"></i> Free shipping on this order
              </div>
              <?php else: foreach ($shipping as $i => $sm):
                $smCost = ($sm['is_free'] || ($sm['free_above'] > 0 && $data['subtotal'] >= (float)$sm['free_above'])) ? 0 : (float)$sm['price'];
              ?>
              <label class="ship-option">
                <div style="display:flex;align-items:center;gap:.6rem;">
                  <input type="radio" name="shipping_method"
                         id="sm-<?= $sm['id'] ?>"
                         value="<?= $sm['id'] ?>"
                         data-cost="<?= $smCost ?>"
                         <?= $i===0 ? 'checked' : '' ?>>
                  <div>
                    <div class="ship-name"><?= e($sm['name']) ?></div>
                    <div class="ship-days"><?= (int)$sm['min_days'] ?>–<?= (int)$sm['max_days'] ?> business days</div>
                  </div>
                </div>
                <span class="ship-cost <?= $smCost===0.0 ? 'free' : '' ?>">
                  <?= $smCost===0.0 ? '<i class="fa fa-check me-1"></i>Free' : 'LKR '.number_format($smCost,2) ?>
                </span>
              </label>
              <?php endforeach; endif; ?>
            </div>

            <!-- Divider -->
            <div style="height:1px;background:var(--border);margin-bottom:1rem;"></div>

            <!-- Totals -->
            <div class="summary-row">
              <span class="label">Subtotal</span>
              <span class="val" id="summarySubtotal">LKR <?= number_format($data['subtotal'],2) ?></span>
            </div>
            <div class="summary-row saving" id="summaryDiscountRow"
                 <?= $data['discount'] <= 0 ? 'style="display:none;"' : '' ?>>
              <span class="label"><i class="fa fa-tag me-1"></i>Discount</span>
              <span class="val" id="summaryDiscount">−LKR <?= number_format($data['discount'],2) ?></span>
            </div>
            <div class="summary-row">
              <span class="label">Shipping</span>
              <span class="val" id="summaryShipping">—</span>
            </div>

            <div class="summary-total">
              <span class="t-label">Total</span>
              <span class="t-val" id="summaryTotal">LKR <?= number_format($data['subtotal']-$data['discount'],2) ?></span>
            </div>

            <a href="<?= url('checkout') ?>" class="checkout-btn mt-4" id="checkoutLink">
              <i class="fa fa-lock me-2"></i>Proceed to Checkout
            </a>

            <div class="trust-badges">
              <span class="trust-badge"><i class="fa fa-shield-halved"></i> Secure</span>
              <span class="trust-badge"><i class="fa fa-rotate-left"></i> Easy Returns</span>
              <span class="trust-badge"><i class="fa fa-headset"></i> 24/7 Support</span>
            </div>
            <?php if (!empty($footerPaymentMethods)):
              $paymentMethods = $footerPaymentMethods;
              $paymentSize    = 'sm';
              $paymentLabel   = true;
            ?>
            <div class="cart-pm-strip" style="margin-top:.85rem;justify-content:center;">
              <?php include dirname(__DIR__) . '/components/payment_logos.php'; ?>
            </div>
            <?php endif; ?>

          </div>
        </div><!-- /summary-panel -->
      </div>
    </div><!-- /col-lg-4 -->

  </div><!-- /row -->
  <?php endif; ?>

</div><!-- /container -->
</div><!-- /cart-page -->

<?php $extraScript = <<<'JSEOF'
<script>
(function () {
  const meta = document.getElementById('cartMeta');
  if (!meta) return;

  let curSub  = parseFloat(meta.dataset.subtotal) || 0;
  let curDisc = parseFloat(meta.dataset.discount)  || 0;
  let curShip = 0;

  const fmt = n => 'LKR ' + parseFloat(n).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

  function recalc(sub, disc) {
    if (sub  !== undefined) curSub  = sub;
    if (disc !== undefined) curDisc = disc;
    document.getElementById('summarySubtotal').textContent = fmt(curSub);
    const discRow = document.getElementById('summaryDiscountRow');
    if (curDisc > 0) {
      discRow.style.display = '';
      document.getElementById('summaryDiscount').textContent = '−' + fmt(curDisc);
    } else {
      discRow.style.display = 'none';
    }
    const shipEl = document.getElementById('summaryShipping');
    if (curShip === 0) {
      shipEl.innerHTML = '<span style="color:#2ec4b6;font-weight:700;">Free</span>';
    } else {
      shipEl.textContent = fmt(curShip);
    }
    document.getElementById('summaryTotal').textContent = fmt(Math.max(0, curSub - curDisc + curShip));
  }

  // Shipping radio
  function updateCheckoutLink() {
    var sel = $('[name="shipping_method"]:checked');
    var link = document.getElementById('checkoutLink');
    if (!link) return;
    var base = link.href.split('?')[0];
    link.href = sel.length ? base + '?ship=' + sel.val() : base;
  }
  $(document).on('change', '[name="shipping_method"]', function () {
    curShip = parseFloat($(this).data('cost')) || 0;
    recalc();
    updateCheckoutLink();
  });
  var $firstShip = $('[name="shipping_method"]:checked');
  if (!$firstShip.length) { $firstShip = $('[name="shipping_method"]:first'); $firstShip.prop('checked', true); }
  curShip = parseFloat($firstShip.data('cost')) || 0;
  recalc();
  updateCheckoutLink();

  // Qty controls
  let qTimer;
  $(document).on('click', '.qty-btn', function () {
    const id  = $(this).data('cart-id');
    const inp = $(`.cart-qty-input[data-cart-id="${id}"]`);
    let qty   = parseInt(inp.val()) || 1;
    qty = $(this).data('action') === 'plus' ? qty + 1 : Math.max(0, qty - 1);
    inp.val(qty);
    clearTimeout(qTimer);
    qTimer = setTimeout(() => doUpdate(id, qty), 500);
  });

  $(document).on('change', '.cart-qty-input', function () {
    clearTimeout(qTimer);
    const id  = $(this).data('cart-id');
    const qty = Math.max(0, parseInt($(this).val()) || 0);
    $(this).val(qty);
    qTimer = setTimeout(() => doUpdate(id, qty), 500);
  });

  function doUpdate(id, qty) {
    apPost('cart/update', { cart_id: id, quantity: qty }, function (res) {
      if (!res.success) return;
      if (qty === 0) {
        $('#cartRow-' + id).fadeOut(220, function () { $(this).remove(); checkEmpty(); });
      } else {
        $(`.cart-line-${id}`).text(fmt(res.line_total));
      }
      $('#cartCount').text(res.count);
      recalc(res.subtotal, res.discount);
    });
  }

  // Remove item
  $(document).on('click', '.cart-remove', function () {
    const id  = $(this).data('cart-id');
    const $el = $(this);
    Swal.fire({
      title: 'Remove this item?',
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Remove',
      confirmButtonColor: '#e74c3c',
      cancelButtonText: 'Keep',
      reverseButtons: true
    }).then(function (r) {
      if (!r.isConfirmed) return;
      apPost('cart/remove', { cart_id: id }, function (res) {
        if (res.success) {
          $('#cartRow-' + id).fadeOut(220, function () { $(this).remove(); checkEmpty(); });
          $('#cartCount').text(res.count);
          recalc(res.subtotal, res.discount);
        }
      });
    });
  });

  function checkEmpty() {
    if ($('#cartItemsWrap .cart-item').length === 0) location.reload();
  }

  // Apply coupon
  $(document).on('click', '#applyCouponBtn', function () {
    const code = $('#couponInput').val().trim();
    if (!code) { toast('warning','Coupon','Enter a coupon code'); return; }
    const btn = $(this);
    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1" style="width:12px;height:12px;"></span>');
    apPost('cart/apply-coupon', { coupon: code }, function (res) {
      btn.prop('disabled', false).text('Apply');
      if (res.success) {
        Swal.fire({ icon:'success', title:'Coupon Applied!', text:res.message, timer:1600, showConfirmButton:false })
          .then(() => location.reload());
      } else {
        $('#couponMsg').html('<span style="color:var(--brand);"><i class="fa fa-circle-xmark me-1"></i>' + res.message + '</span>');
      }
    });
  });

  $(document).on('click', '#removeCouponBtn', function () {
    apPost('cart/remove-coupon', {}, function (res) { if (res.success) location.reload(); });
  });

  $(document).on('input', '#couponInput', function () {
    const pos = this.selectionStart;
    this.value = this.value.toUpperCase();
    this.setSelectionRange(pos, pos);
    $('#couponMsg').html('');
  });

})();
</script>
JSEOF;
?>
