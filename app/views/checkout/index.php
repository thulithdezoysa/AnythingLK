<?php
$pageTitle   = 'Checkout';
$excludedPm  = json_encode(DiscountEngine::EXCLUDED_METHODS);
$defaultAddr = null;
foreach ($addresses as $a) { if ($a['is_default']) { $defaultAddr = $a; break; } }
if (!$defaultAddr && !empty($addresses)) $defaultAddr = $addresses[0];
$stepOffset = empty($addresses) ? 0 : 1;
?>

<style>
/* ── Checkout Page ─────────────────────────────────────── */
.chk-page { background: var(--bg-soft); min-height: 80vh; }

/* breadcrumb */
.chk-crumb { font-size: .82rem; color: var(--text-muted); }
.chk-crumb a { color: var(--text-muted); text-decoration: none; }
.chk-crumb a:hover { color: var(--brand); }

/* section panels */
.chk-panel {
  background: var(--bg-card);
  border: 1px solid var(--border);
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow);
  margin-bottom: 1rem;
  overflow: hidden;
}
.chk-panel-head {
  display: flex; align-items: center; gap: .75rem;
  padding: 1rem 1.35rem;
  border-bottom: 1px solid var(--border);
}
.chk-panel-body { padding: 1.35rem; }

/* step badge */
.step-dot {
  width: 28px; height: 28px; border-radius: 50%;
  background: var(--grad-brand);
  color: #fff;
  font-size: .78rem; font-weight: 800;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
  box-shadow: 0 2px 8px rgba(230,57,70,.35);
}
.chk-panel-head h6 { margin: 0; font-weight: 700; color: var(--text); font-size: .95rem; }

/* ── Address cards ── */
.addr-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: .6rem; }
.addr-card {
  border: 1.5px solid var(--border);
  border-radius: 10px;
  padding: .85rem 1rem;
  cursor: pointer;
  transition: all .18s;
  background: var(--bg-soft);
  position: relative;
}
.addr-card:hover { border-color: var(--brand); }
.addr-card.selected {
  border-color: var(--brand);
  background: rgba(230,57,70,.05);
  box-shadow: 0 0 0 3px rgba(230,57,70,.12);
}
.addr-card .check-ring {
  width: 18px; height: 18px; border-radius: 50%;
  border: 2px solid var(--border);
  display: flex; align-items: center; justify-content: center;
  position: absolute; top: .65rem; right: .75rem;
  transition: all .15s;
  flex-shrink: 0;
}
.addr-card.selected .check-ring {
  background: var(--brand); border-color: var(--brand);
}
.addr-card.selected .check-ring::after {
  content: '';
  width: 5px; height: 9px;
  border: 2px solid #fff; border-top: 0; border-left: 0;
  transform: rotate(40deg) translate(-1px, -1px);
  display: block;
}
.addr-default-badge {
  display: inline-block;
  font-size: .68rem; font-weight: 700;
  padding: 1px 7px; border-radius: 20px;
  background: var(--brand); color: #fff;
  margin-bottom: .35rem;
}
.addr-name { font-size: .85rem; font-weight: 600; color: var(--text); }
.addr-line { font-size: .76rem; color: var(--text-muted); line-height: 1.4; margin-top: .2rem; }

/* ── Form fields ── */
.chk-label {
  display: block;
  font-size: .78rem; font-weight: 700; text-transform: uppercase; letter-spacing: .04em;
  color: var(--text-muted); margin-bottom: .35rem;
}
.chk-label .req { color: var(--brand); margin-left: 2px; }
.chk-input, .chk-select, .chk-textarea {
  width: 100%;
  background: var(--bg-soft);
  border: 1.5px solid var(--border);
  border-radius: 9px;
  color: var(--text);
  font-size: .88rem;
  padding: .55rem .85rem;
  transition: border-color .18s, box-shadow .18s;
  outline: none;
  font-family: inherit;
}
.chk-input:focus, .chk-select:focus, .chk-textarea:focus {
  border-color: var(--brand);
  box-shadow: 0 0 0 3px rgba(230,57,70,.1);
}
.chk-select { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%238b949e' d='M6 8L1 3h10z'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right .75rem center; padding-right: 2rem; }
.chk-textarea { resize: vertical; min-height: 70px; }
.field-err { font-size: .75rem; color: var(--brand); margin-top: .25rem; display: none; }
.was-validated .chk-input:invalid { border-color: var(--brand); }
.was-validated .chk-input:invalid + .field-err { display: block; }

/* ── Shipping options ── */
.ship-opt {
  display: flex; align-items: center; justify-content: space-between;
  border: 1.5px solid var(--border);
  border-radius: 10px;
  padding: .75rem 1rem;
  cursor: pointer;
  transition: all .18s;
  margin-bottom: .5rem;
  background: var(--bg-soft);
}
.ship-opt:last-child { margin-bottom: 0; }
.ship-opt:has(input:checked) { border-color: var(--brand); background: rgba(230,57,70,.05); box-shadow: 0 0 0 3px rgba(230,57,70,.08); }
.ship-opt input { accent-color: var(--brand); width: 16px; height: 16px; cursor: pointer; }
.ship-opt-name { font-size: .88rem; font-weight: 600; color: var(--text); }
.ship-opt-days { font-size: .73rem; color: var(--text-muted); margin-top: 1px; }
.ship-opt-cost { font-size: .88rem; font-weight: 700; white-space: nowrap; }
.ship-opt-cost.free { color: #2ec4b6; }

/* ── Payment options ── */
.pay-opt {
  display: flex; align-items: center; gap: .85rem;
  border: 1.5px solid var(--border);
  border-radius: 12px;
  padding: .85rem 1rem;
  cursor: pointer;
  transition: all .18s;
  margin-bottom: .5rem;
  background: var(--bg-soft);
  position: relative;
}
.pay-opt:last-child { margin-bottom: 0; }
.pay-opt:has(input:checked) { border-color: var(--brand); background: rgba(230,57,70,.05); box-shadow: 0 0 0 3px rgba(230,57,70,.08); }
.pay-opt.pay-disabled { opacity: .45; cursor: not-allowed; pointer-events: none; filter: grayscale(.5); }
.pay-opt input { accent-color: var(--brand); width: 16px; height: 16px; cursor: pointer; flex-shrink: 0; }
.pay-opt-icon {
  width: 42px; height: 42px; border-radius: 10px; display: flex;
  align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0;
}
.pay-opt-icon--svg {
  background: #fff; border: 1px solid rgba(0,0,0,.08); padding: 4px;
  overflow: hidden;
}
.pay-opt-name { font-size: .9rem; font-weight: 700; color: var(--text); }
.pay-opt-desc { font-size: .73rem; color: var(--text-muted); margin-top: 2px; line-height: 1.35; }
.pay-opt-badge {
  position: absolute; top: .5rem; right: .75rem;
  font-size: .62rem; font-weight: 700; letter-spacing: .04em; text-transform: uppercase;
  padding: 2px 7px; border-radius: 20px;
}
.pay-badge-popular { background: rgba(230,57,70,.12); color: var(--brand); }
.pay-badge-bnpl    { background: rgba(245,158,11,.12); color: #d97706; }
.pay-badge-na      { background: var(--border); color: var(--text-muted); }
.pay-no-common {
  background: rgba(245,158,11,.08); border: 1.5px solid rgba(245,158,11,.3);
  border-radius: 10px; padding: .8rem 1rem; font-size: .82rem; color: var(--text);
  display: flex; gap: .6rem; align-items: flex-start; margin-bottom: .75rem;
}

.excl-notice {
  display: flex; align-items: flex-start; gap: .6rem;
  background: rgba(243,156,18,.08);
  border: 1.5px solid rgba(243,156,18,.35);
  border-radius: 9px;
  padding: .65rem .9rem;
  font-size: .8rem; color: var(--text);
  margin-top: .75rem;
}
.excl-notice i { color: #f39c12; margin-top: 1px; flex-shrink: 0; }

/* ── Summary panel ── */
.summary-sticky { position: sticky; top: 82px; }
.summary-panel {
  background: var(--bg-card);
  border: 1px solid var(--border);
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow);
  overflow: hidden;
}
.summary-head { padding: 1rem 1.35rem; border-bottom: 1px solid var(--border); }
.summary-head span { font-weight: 700; color: var(--text); font-size: .95rem; }
.summary-body { padding: 1.2rem 1.35rem; }

/* item list */
.sum-item { display: flex; align-items: flex-start; gap: .75rem; padding: .6rem 0; border-bottom: 1px solid var(--border); }
.sum-item:last-child { border-bottom: 0; }
.sum-item-img { position: relative; flex-shrink: 0; }
.sum-item-img img { width: 48px; height: 48px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border); }
.sum-item-img .qty-bubble {
  position: absolute; top: -6px; right: -6px;
  background: var(--brand); color: #fff;
  font-size: .62rem; font-weight: 800;
  width: 18px; height: 18px; border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
}
.sum-item-name { font-size: .8rem; font-weight: 600; color: var(--text); line-height: 1.35; }
.sum-item-var  { font-size: .7rem; color: var(--text-muted); margin-top: 1px; }
.sum-item-price { font-size: .82rem; font-weight: 700; color: var(--text); white-space: nowrap; margin-left: auto; }

.sum-items-wrap { max-height: 260px; overflow-y: auto; margin-bottom: .75rem; }
.sum-items-wrap::-webkit-scrollbar { width: 4px; }
.sum-items-wrap::-webkit-scrollbar-thumb { background: var(--border); border-radius: 2px; }

/* coupon applied */
.sum-coupon {
  display: flex; align-items: center; gap: .6rem;
  background: rgba(46,196,182,.07);
  border: 1.5px solid rgba(46,196,182,.3);
  border-radius: 9px;
  padding: .55rem .85rem;
  margin-bottom: .75rem;
  font-size: .8rem;
}
.sum-coupon .code { font-weight: 700; color: var(--text); }
.sum-coupon .save { color: #2ec4b6; font-weight: 700; margin-left: auto; white-space: nowrap; }

/* totals */
.sum-row { display: flex; justify-content: space-between; align-items: center; font-size: .86rem; margin-bottom: .5rem; }
.sum-row .lbl { color: var(--text-muted); }
.sum-row .val { font-weight: 600; color: var(--text); }
.sum-row.disc .lbl, .sum-row.disc .val { color: #2ec4b6; }
.sum-total {
  display: flex; justify-content: space-between; align-items: center;
  padding-top: .85rem; margin-top: .35rem;
  border-top: 2px solid var(--border);
}
.sum-total .tl { font-size: 1rem; font-weight: 800; color: var(--text); }
.sum-total .tv { font-size: 1.25rem; font-weight: 800; color: var(--brand); }

/* place order button */
.place-btn {
  display: block; width: 100%;
  padding: .9rem;
  border-radius: 10px;
  border: none;
  background: var(--grad-brand);
  color: #fff;
  font-size: 1rem; font-weight: 800;
  letter-spacing: .02em;
  cursor: pointer;
  transition: opacity .2s, transform .15s;
  box-shadow: 0 4px 18px rgba(230,57,70,.32);
  margin-top: 1.1rem;
}
.place-btn:hover:not(:disabled) { opacity: .9; transform: translateY(-1px); }
.place-btn:disabled { opacity: .65; cursor: not-allowed; }

.secure-note {
  display: flex; justify-content: center; align-items: center; gap: .35rem;
  font-size: .73rem; color: var(--text-muted); margin-top: .65rem;
}

/* ── Responsive ── */
@media (max-width: 767px) {
  .addr-grid { grid-template-columns: 1fr 1fr; }
  .chk-panel-body { padding: 1rem; }
  .summary-sticky { position: static; }
}
@media (max-width: 440px) {
  .addr-grid { grid-template-columns: 1fr; }
}
</style>

<!-- Meta for JS -->
<div id="chkMeta"
     data-subtotal="<?= (float)$data['subtotal'] ?>"
     data-discount="<?= (float)$data['discount'] ?>"
     data-excluded='<?= $excludedPm ?>'
     hidden></div>

<div class="chk-page py-4 py-lg-5">
<div class="container">

  <!-- Breadcrumb + header -->
  <div class="mb-3">
    <div class="chk-crumb mb-2">
      <a href="<?= url('') ?>">Home</a>
      <span style="margin:0 .4rem;opacity:.4;">›</span>
      <a href="<?= url('cart') ?>">Cart</a>
      <span style="margin:0 .4rem;opacity:.4;">›</span>
      <span>Checkout</span>
    </div>
    <div class="d-flex align-items-center gap-3">
      <a href="<?= url('cart') ?>"
         style="width:34px;height:34px;border-radius:8px;border:1px solid var(--border);background:var(--bg-card);display:flex;align-items:center;justify-content:center;color:var(--text-muted);text-decoration:none;flex-shrink:0;transition:all .15s;"
         onmouseover="this.style.borderColor='var(--brand)';this.style.color='var(--brand)'"
         onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-muted)'">
        <i class="fa fa-arrow-left fa-xs"></i>
      </a>
      <h2 class="mb-0 fw-bold" style="color:var(--text);font-family:'Outfit',sans-serif;">Checkout</h2>
    </div>
  </div>

  <form id="checkoutForm" novalidate>
    <?= CSRF::field() ?>

    <div class="row g-4 align-items-start">

      <!-- ── LEFT: Steps ─────────────────────── -->
      <div class="col-lg-7">

        <?php if (!empty($addresses)): ?>
        <!-- Step 1 — Saved Addresses -->
        <div class="chk-panel">
          <div class="chk-panel-head">
            <span class="step-dot">1</span>
            <h6>Saved Addresses</h6>
          </div>
          <div class="chk-panel-body">
            <div class="addr-grid">
              <?php foreach ($addresses as $addr): ?>
              <div class="addr-card <?= ($addr === $defaultAddr) ? 'selected' : '' ?>"
                   data-name="<?= e($addr['full_name']) ?>"
                   data-phone="<?= e($addr['phone'] ?? '') ?>"
                   data-address="<?= e(trim($addr['address_line1'].(!empty($addr['address_line2']) ? ', '.$addr['address_line2'] : ''))) ?>"
                   data-city="<?= e($addr['city']) ?>"
                   data-state="<?= e($addr['state'] ?? '') ?>"
                   data-postal="<?= e($addr['postal_code'] ?? '') ?>">
                <div class="check-ring"></div>
                <?php if ($addr['is_default']): ?>
                <div class="addr-default-badge">Default</div>
                <?php endif; ?>
                <div class="addr-name"><?= e($addr['full_name']) ?></div>
                <div class="addr-line">
                  <?= e($addr['address_line1']) ?><?= !empty($addr['address_line2']) ? ', '.e($addr['address_line2']) : '' ?>
                </div>
                <div class="addr-line"><?= e($addr['city']) ?><?= !empty($addr['state']) ? ', '.$addr['state'] : '' ?></div>
                <?php if (!empty($addr['phone'])): ?>
                <div class="addr-line" style="margin-top:.3rem;">
                  <i class="fa fa-phone fa-xs me-1"></i><?= e($addr['phone']) ?>
                </div>
                <?php endif; ?>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
        <?php endif; ?>

        <!-- Step: Delivery Details -->
        <div class="chk-panel">
          <div class="chk-panel-head">
            <span class="step-dot"><?= 1 + $stepOffset ?></span>
            <h6>Delivery Details</h6>
          </div>
          <div class="chk-panel-body">
            <div class="row g-3">
              <div class="col-sm-6">
                <label class="chk-label">Full Name <span class="req">*</span></label>
                <input type="text" name="full_name" id="shpName" class="chk-input" required
                       value="<?= Auth::check() ? e(Auth::user()['name']) : '' ?>"
                       placeholder="Your full name">
                <div class="field-err">Please enter your name.</div>
              </div>
              <div class="col-sm-6">
                <label class="chk-label">Phone <span class="req">*</span></label>
                <input type="text" name="phone" id="shpPhone" class="chk-input" required
                       value="<?= $defaultAddr ? e($defaultAddr['phone'] ?? '') : '' ?>"
                       placeholder="+94 77 000 0000">
                <div class="field-err">Enter a valid phone number.</div>
              </div>
              <div class="col-12">
                <label class="chk-label">Email <span style="font-weight:400;text-transform:none;letter-spacing:0;">(optional — for order confirmation)</span></label>
                <input type="email" name="email" class="chk-input"
                       value="<?= Auth::check() ? e(Auth::user()['email']) : '' ?>"
                       placeholder="you@example.com">
              </div>
              <div class="col-12">
                <label class="chk-label">Street Address <span class="req">*</span></label>
                <input type="text" name="address" id="shpAddress" class="chk-input" required
                       value="<?= $defaultAddr ? e(trim($defaultAddr['address_line1'].(!empty($defaultAddr['address_line2']) ? ', '.$defaultAddr['address_line2'] : ''))) : '' ?>"
                       placeholder="House number, street name">
                <div class="field-err">Please enter your address.</div>
              </div>
              <div class="col-sm-5">
                <label class="chk-label">City <span class="req">*</span></label>
                <input type="text" name="city" id="shpCity" class="chk-input" required
                       value="<?= $defaultAddr ? e($defaultAddr['city'] ?? '') : '' ?>"
                       placeholder="e.g. Colombo">
                <div class="field-err">Please enter your city.</div>
              </div>
              <div class="col-sm-4">
                <label class="chk-label">Province</label>
                <select name="state" id="shpState" class="chk-select">
                  <option value="">Select…</option>
                  <?php
                  $provinces = ['Western','Central','Southern','Northern','Eastern','North Western','North Central','Uva','Sabaragamuwa'];
                  $selState  = $defaultAddr['state'] ?? '';
                  foreach ($provinces as $prov): ?>
                  <option value="<?= $prov ?>" <?= $prov === $selState ? 'selected' : '' ?>><?= $prov ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-sm-3">
                <label class="chk-label">Postal Code</label>
                <input type="text" name="postal" id="shpPostal" class="chk-input"
                       value="<?= $defaultAddr ? e($defaultAddr['postal_code'] ?? '') : '' ?>"
                       placeholder="00000">
              </div>
              <div class="col-12">
                <label class="chk-label">Order Notes <span style="font-weight:400;text-transform:none;letter-spacing:0;">(optional)</span></label>
                <textarea name="notes" class="chk-textarea" placeholder="Special delivery instructions…"></textarea>
              </div>
            </div>
          </div>
        </div>

        <!-- Step: Shipping Method -->
        <div class="chk-panel">
          <div class="chk-panel-head">
            <span class="step-dot"><?= 2 + $stepOffset ?></span>
            <h6>Shipping Method</h6>
          </div>
          <div class="chk-panel-body">
            <?php if (empty($shipping)): ?>
            <div style="padding:.75rem;background:rgba(46,196,182,.08);border:1px solid rgba(46,196,182,.25);border-radius:10px;font-size:.85rem;color:var(--text-muted);display:flex;align-items:center;gap:.6rem;">
              <i class="fa fa-truck" style="color:#2ec4b6;"></i>
              Free shipping on this order.
              <input type="hidden" name="shipping_method_id" value="0">
            </div>
            <?php else:
              // Determine which method to pre-check: from ?ship= param, else first
              $_shipIds  = array_column($shipping, 'id');
              $_precheck = (isset($preselectedShip) && in_array($preselectedShip, $_shipIds)) ? $preselectedShip : $_shipIds[0];
              foreach ($shipping as $sm):
                $smCost   = ($sm['is_free'] || ($sm['free_above'] > 0 && $data['subtotal'] >= (float)$sm['free_above'])) ? 0 : (float)$sm['price'];
                $smZone   = $sm['zone'] ?? '';
                $zoneLabel = ['all'=>'All Island','colombo'=>'Colombo','western'=>'Western Province','outstation'=>'Outstation','express'=>'Express'][$smZone] ?? '';
            ?>
            <label class="ship-opt">
              <div style="display:flex;align-items:center;gap:.75rem;">
                <input class="shipping-method-radio" type="radio"
                       name="shipping_method_id"
                       id="sm-<?= $sm['id'] ?>"
                       value="<?= $sm['id'] ?>"
                       data-cost="<?= $smCost ?>"
                       data-name="<?= e($sm['name']) ?>"
                       <?= (int)$sm['id'] === $_precheck ? 'checked' : '' ?>>
                <div>
                  <div class="ship-opt-name">
                    <?= e($sm['name']) ?>
                    <?php if ($zoneLabel): ?>
                    <span style="font-size:.7rem;font-weight:600;color:var(--text-muted);margin-left:6px;"><?= e($zoneLabel) ?></span>
                    <?php endif; ?>
                  </div>
                  <div class="ship-opt-days"><i class="fa fa-clock fa-xs me-1"></i><?= (int)$sm['min_days'] ?>–<?= (int)$sm['max_days'] ?> business days</div>
                </div>
              </div>
              <span class="ship-opt-cost <?= $smCost===0.0 ? 'free' : '' ?>">
                <?= $smCost===0.0 ? '<i class="fa fa-check me-1"></i>Free' : 'LKR '.number_format($smCost,2) ?>
              </span>
            </label>
            <?php endforeach; endif; ?>
          </div>
        </div>

        <!-- Step: Payment Method -->
        <div class="chk-panel" style="margin-bottom:0;">
          <div class="chk-panel-head">
            <span class="step-dot"><?= 3 + $stepOffset ?></span>
            <h6>Payment Method</h6>
          </div>
          <div class="chk-panel-body">
            <?php
            $pmIconMap = [
              'cod'        => ['icon'=>'fa-truck-fast',      'bg'=>'#dcfce7','color'=>'#16a34a','badge'=>''],
              'koko'       => ['icon'=>'fa-layer-group',     'bg'=>'#ffedd5','color'=>'#ea580c','badge'=>'BNPL'],
              'mintpay'    => ['icon'=>'fa-calendar-check',  'bg'=>'#ccfbf1','color'=>'#0d9488','badge'=>'BNPL'],
              'payzy'      => ['icon'=>'fa-shield-halved',   'bg'=>'#dbeafe','color'=>'#2563eb','badge'=>''],
              'card'       => ['icon'=>'fa-credit-card',     'bg'=>'#ede9fe','color'=>'#6d28d9','badge'=>'Popular'],
              'visa'       => ['icon'=>'fa-cc-visa',         'bg'=>'#e0e7ff','color'=>'#1a1f71','badge'=>''],
              'mastercard' => ['icon'=>'fa-cc-mastercard',   'bg'=>'#fee2e2','color'=>'#eb001b','badge'=>''],
              'bank'       => ['icon'=>'fa-building-columns','bg'=>'#f3f4f6','color'=>'#374151','badge'=>''],
              'payhere'    => ['icon'=>'fa-credit-card',     'bg'=>'#dbeafe','color'=>'#2563eb','badge'=>''],
            ];
            $_svgLogoBase = url('assets/payments/');
            $_hasSvgLogo  = ['cod','visa','mastercard','koko','mintpay','payzy'];
            $firstChecked = false;
            ?>

            <?php if (!empty($noCommonMethod)): ?>
            <div class="pay-no-common">
              <i class="fa fa-triangle-exclamation" style="color:#d97706;margin-top:2px;flex-shrink:0;"></i>
              <div>
                <strong>No common payment method</strong> — items in your cart have conflicting payment restrictions.
                Consider splitting your order or removing incompatible items.
              </div>
            </div>
            <?php endif; ?>

            <?php foreach ($paymentMethods as $pm):
              $code     = strtolower(trim($pm['code'] ?? ''));
              $pi       = $pmIconMap[$code] ?? ['icon'=>'fa-wallet','bg'=>'#f3f4f6','color'=>'#6b7280','badge'=>''];
              $allowed  = $pm['is_allowed'] ?? true;
              $doCheck  = $allowed && !$firstChecked;
              if ($doCheck) $firstChecked = true;
            ?>
            <label class="pay-opt<?= !$allowed ? ' pay-disabled' : '' ?>"
                   <?= !$allowed ? 'title="Not available for all items in your cart"' : '' ?>>
              <input class="payment-method-radio" type="radio"
                     name="payment_method"
                     id="pm-<?= e($code) ?>"
                     value="<?= e($code) ?>"
                     <?= $doCheck ? 'checked' : '' ?>
                     <?= !$allowed ? 'disabled' : '' ?>>
              <span class="pay-opt-icon<?= in_array($code, $_hasSvgLogo) ? ' pay-opt-icon--svg' : '' ?>" style="<?= !in_array($code, $_hasSvgLogo) ? "background:{$pi['bg']};color:{$pi['color']};" : '' ?>">
                <?php if (in_array($code, $_hasSvgLogo)): ?>
                  <img src="<?= $_svgLogoBase . e($code) ?>.svg" alt="<?= e($pm['display_name']) ?>" height="24" style="display:block;border-radius:4px;">
                <?php else: ?>
                  <i class="fas <?= $pi['icon'] ?>"></i>
                <?php endif; ?>
              </span>
              <div style="flex:1;min-width:0;">
                <div class="pay-opt-name"><?= e($pm['display_name']) ?></div>
                <?php if (!empty($pm['description'])): ?>
                <div class="pay-opt-desc"><?= e($pm['description']) ?></div>
                <?php endif; ?>
              </div>
              <?php if (!$allowed): ?>
                <span class="pay-opt-badge pay-badge-na">Unavailable</span>
              <?php elseif ($pi['badge'] === 'Popular'): ?>
                <span class="pay-opt-badge pay-badge-popular">Popular</span>
              <?php elseif ($pi['badge'] === 'BNPL'): ?>
                <span class="pay-opt-badge pay-badge-bnpl">BNPL</span>
              <?php endif; ?>
            </label>
            <?php endforeach; ?>

            <!-- Discount exclusion notice -->
            <div id="discountExclusionNotice" class="excl-notice" style="display:none;">
              <i class="fa fa-triangle-exclamation mt-1"></i>
              <span>Coupons &amp; discounts are not applicable with this payment method.</span>
            </div>
          </div>
        </div>

      </div><!-- /col-lg-7 -->

      <!-- ── RIGHT: Summary ──────────────────── -->
      <div class="col-lg-5">
        <div class="summary-sticky">
          <div class="summary-panel" id="orderSummary">

            <div class="summary-head">
              <span><i class="fa fa-receipt me-2" style="color:var(--brand);"></i>Order Summary</span>
            </div>

            <div class="summary-body">

              <!-- Items -->
              <div class="sum-items-wrap">
                <?php foreach ($data['items'] as $item): ?>
                <div class="sum-item">
                  <div class="sum-item-img">
                    <img src="<?= !empty($item['thumbnail']) ? url('uploads/products/'.e($item['thumbnail'])) : url('assets/img/placeholder.webp') ?>" alt="">
                    <span class="qty-bubble"><?= (int)$item['quantity'] ?></span>
                  </div>
                  <div style="flex:1;min-width:0;">
                    <div class="sum-item-name"><?= e(Helper::truncate($item['name'],38)) ?></div>
                    <?php if (!empty($item['variation_name'])): ?>
                    <div class="sum-item-var"><?= e($item['variation_name']) ?></div>
                    <?php endif; ?>
                  </div>
                  <div class="sum-item-price">LKR <?= number_format($item['line_total'],2) ?></div>
                </div>
                <?php endforeach; ?>
              </div>

              <!-- Coupon -->
              <?php if (!empty($_SESSION['coupon'])): ?>
              <div class="sum-coupon">
                <i class="fa fa-tag" style="color:#2ec4b6;flex-shrink:0;"></i>
                <span class="code"><?= e($_SESSION['coupon']['code']) ?></span>
                <span style="color:var(--text-muted);font-size:.78rem;">applied</span>
                <span class="save">−LKR <?= number_format($_SESSION['coupon']['discount'],2) ?></span>
              </div>
              <?php endif; ?>

              <!-- Divider -->
              <div style="height:1px;background:var(--border);margin:.35rem 0 .85rem;"></div>

              <!-- Totals -->
              <div class="sum-row">
                <span class="lbl">Subtotal</span>
                <span class="val">LKR <?= number_format($data['subtotal'],2) ?></span>
              </div>
              <div class="sum-row disc" id="summaryDiscountRow"
                   <?= $data['discount'] <= 0 ? 'style="display:none;"' : '' ?>>
                <span class="lbl"><i class="fa fa-tag me-1"></i>Discount</span>
                <span class="val" id="summaryDiscount">−LKR <?= number_format($data['discount'],2) ?></span>
              </div>
              <div class="sum-row">
                <span class="lbl">Shipping</span>
                <span class="val" id="summaryShipping">—</span>
              </div>

              <div class="sum-total">
                <span class="tl">Total</span>
                <span class="tv" id="summaryTotal">LKR <?= number_format($data['subtotal']-$data['discount'],2) ?></span>
              </div>

              <button type="submit" class="place-btn" id="placeOrderBtn">
                <i class="fa fa-lock me-2"></i>Place Order
              </button>

              <div class="secure-note">
                <i class="fa fa-shield-halved"></i> SSL Encrypted
                <span style="margin:0 .3rem;opacity:.4;">·</span>
                <i class="fa fa-rotate-left"></i> Easy Returns
                <span style="margin:0 .3rem;opacity:.4;">·</span>
                <i class="fa fa-headset"></i> 24/7 Support
              </div>

            </div>
          </div>
        </div>
      </div><!-- /col-lg-5 -->

    </div><!-- /row -->
  </form>

</div><!-- /container -->
</div><!-- /chk-page -->

<?php $extraScript = <<<'JSEOF'
<script>
(function () {
  const meta     = document.getElementById('chkMeta');
  const subtotal = parseFloat(meta.dataset.subtotal) || 0;
  const excluded = JSON.parse(meta.dataset.excluded  || '[]');
  let   discount = parseFloat(meta.dataset.discount) || 0;
  let   shipping = 0;

  const fmt = n => 'LKR ' + parseFloat(n).toLocaleString('en-US', { minimumFractionDigits:2, maximumFractionDigits:2 });

  function recalc() {
    const shipEl = document.getElementById('summaryShipping');
    if (shipping === 0) {
      shipEl.innerHTML = '<span style="color:#2ec4b6;font-weight:700;"><i class="fa fa-check me-1"></i>Free</span>';
    } else {
      shipEl.textContent = fmt(shipping);
    }
    const discRow = document.getElementById('summaryDiscountRow');
    discRow.style.display = discount > 0 ? '' : 'none';
    document.getElementById('summaryDiscount').textContent = '−' + fmt(discount);
    document.getElementById('summaryTotal').textContent    = fmt(Math.max(0, subtotal - discount + shipping));
  }

  // Shipping
  document.querySelectorAll('.shipping-method-radio').forEach(r => {
    r.addEventListener('change', function () { shipping = parseFloat(this.dataset.cost) || 0; recalc(); });
  });
  const initShip = document.querySelector('.shipping-method-radio:checked') || document.querySelector('.shipping-method-radio');
  shipping = initShip ? (parseFloat(initShip.dataset.cost) || 0) : 0;
  recalc();

  // Payment exclusion
  document.querySelectorAll('.payment-method-radio').forEach(r => {
    r.addEventListener('change', function () {
      const isExcluded = excluded.map(x => x.toLowerCase()).includes(this.value.toLowerCase());
      discount = isExcluded ? 0 : (parseFloat(meta.dataset.discount) || 0);
      document.getElementById('discountExclusionNotice').style.display = isExcluded ? '' : 'none';
      recalc();
    });
  });

  // Address cards
  document.querySelectorAll('.addr-card').forEach(card => {
    card.addEventListener('click', function () {
      document.querySelectorAll('.addr-card').forEach(c => c.classList.remove('selected'));
      this.classList.add('selected');
      var d = this.dataset;
      var set = (id, val) => { var el = document.getElementById(id); if (el) el.value = val || ''; };
      set('shpName',    d.name);
      set('shpPhone',   d.phone);
      set('shpAddress', d.address);
      set('shpCity',    d.city);
      set('shpPostal',  d.postal);
      var sel = document.getElementById('shpState');
      if (sel && d.state) {
        for (var i = 0; i < sel.options.length; i++) {
          if (sel.options[i].value === d.state) { sel.selectedIndex = i; break; }
        }
      }
    });
  });

  // Submit
  document.getElementById('checkoutForm').addEventListener('submit', function (e) {
    e.preventDefault();
    if (!this.checkValidity()) {
      this.classList.add('was-validated');
      var first = this.querySelector('.chk-input:invalid, .chk-select:invalid');
      if (first) first.scrollIntoView({ behavior: 'smooth', block: 'center' });
      return;
    }
    var btn = document.getElementById('placeOrderBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" style="width:16px;height:16px;"></span>Processing…';

    $.ajax({
      url:      SITE_URL + '/checkout/place-order',
      type:     'POST',
      data:     new URLSearchParams(new FormData(this)).toString(),
      dataType: 'json',
      success: function (res) {
        if (res.success) {
          btn.innerHTML = '<i class="fa fa-circle-check me-2"></i>Order Placed!';
          btn.style.background = 'linear-gradient(135deg,#2ec4b6,#1a9e95)';
          btn.style.boxShadow  = '0 4px 18px rgba(46,196,182,.35)';
          setTimeout(() => window.location.href = res.redirect, 900);
        } else {
          btn.disabled = false;
          btn.innerHTML = '<i class="fa fa-lock me-2"></i>Place Order';
          Swal.fire({ icon:'error', title:'Oops', text:res.message });
        }
      },
      error: function (xhr) {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa fa-lock me-2"></i>Place Order';
        var msg = 'Network error. Please try again.';
        try { msg = JSON.parse(xhr.responseText).message || msg; } catch (_) {}
        Swal.fire({ icon:'error', title:'Error', text:msg });
      }
    });
  });

})();
</script>
JSEOF;
?>
