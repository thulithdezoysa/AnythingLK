<?php /* admin/views/orders/view.php */
$statusStyles = [
  'pending'    => ['bg'=>'var(--amber-dim)', 'color'=>'var(--amber)'],
  'confirmed'  => ['bg'=>'var(--cyan-dim)',  'color'=>'var(--cyan)'],
  'processing' => ['bg'=>'var(--cyan-dim)',  'color'=>'var(--cyan)'],
  'shipped'    => ['bg'=>'var(--surface2)',  'color'=>'var(--text-dim)'],
  'delivered'  => ['bg'=>'var(--green-dim)', 'color'=>'var(--green)'],
  'cancelled'  => ['bg'=>'var(--red-dim)',   'color'=>'var(--red)'],
  'refunded'   => ['bg'=>'var(--surface2)',  'color'=>'var(--text-muted)'],
];
$payStyles = [
  'paid'     => ['bg'=>'var(--green-dim)', 'color'=>'var(--green)'],
  'unpaid'   => ['bg'=>'var(--amber-dim)', 'color'=>'var(--amber)'],
  'partial'  => ['bg'=>'var(--cyan-dim)',  'color'=>'var(--cyan)'],
  'refunded' => ['bg'=>'var(--surface2)',  'color'=>'var(--text-muted)'],
];
$ss = $statusStyles[$order['status']] ?? $statusStyles['pending'];
$ps = $payStyles[$order['payment_status']] ?? $payStyles['unpaid'];
$isCod      = ($order['payment_method'] === 'cod');
$isPaid     = ($order['payment_status'] === 'paid');
$isTerminal = in_array($order['status'], ['delivered','cancelled','refunded']);

/* ── Resolved customer contact ─────────────────────────────
   Name  : shipping_name is always the most accurate (entered
           at checkout, may differ from profile name).
   Email : registered users → u.email (user_email);
           guests           → o.guest_email.
   Phone : shipping_phone is always from the checkout form.   */
$custName  = $order['shipping_name'] ?: ($order['user_full_name'] ?? 'Guest');
$custEmail = $order['user_id'] ? ($order['user_email'] ?? '') : ($order['guest_email'] ?? '');
$custPhone = $order['shipping_phone'] ?? '';
$isGuest   = !$order['user_id'];
?>
<style>
/* ════════════════════════════════════════════════════════════
   ORDER VIEW — RESPONSIVE
   All selectors scoped to .ov-* / #ovItemsTable.
   ════════════════════════════════════════════════════════════ */

/* ── 1. Page header ─────────────────────────────────────────*/
.ov-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  flex-wrap: wrap;
  gap: 12px;
  margin-bottom: 20px;
}
.ov-actions {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
  align-items: center;
}
@media (max-width: 480px) {
  .ov-actions { width: 100%; }
  .ov-actions > * { flex: 1 1 auto; text-align: center; justify-content: center; }
}

/* ── 2. Items card: override .card overflow:hidden ──────────*/
.ov-items-card { overflow: visible !important; }
.ov-items-scroll {
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
  scrollbar-width: thin;
  scrollbar-color: rgba(255,255,255,.12) transparent;
  border-radius: 0 0 var(--radius) var(--radius);
}
.ov-items-scroll::-webkit-scrollbar       { height: 4px; }
.ov-items-scroll::-webkit-scrollbar-track { background: transparent; }
.ov-items-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,.12); border-radius: 4px; }
@media (min-width: 576px) { #ovItemsTable { min-width: 480px; } }

/* ── 3. Items table: mobile card layout (< 576px) ─────────*/
@media (max-width: 575px) {
  .ov-items-scroll { overflow-x: visible; border-radius: 0; }
  #ovItemsTable    { min-width: 0 !important; }
  #ovItemsTable thead { display: none; }
  #ovItemsTable tbody tr {
    display: block;
    padding: 10px 14px;
    border-bottom: 1px solid var(--border);
  }
  #ovItemsTable tbody tr:last-child { border-bottom: none; }
  #ovItemsTable tbody td {
    display: block !important;
    padding: 0 !important;
    border: none !important;
    background: transparent !important;
  }
  #ovItemsTable tbody td:nth-child(1) { padding-bottom: 6px !important; }
  #ovItemsTable tbody td:nth-child(2) {
    font-size: 11px;
    color: var(--text-muted);
    padding-bottom: 6px !important;
  }
  #ovItemsTable tbody td:nth-child(3),
  #ovItemsTable tbody td:nth-child(4) {
    display: inline-block !important;
    font-size: 12px;
    color: var(--text-dim);
  }
  #ovItemsTable tbody td:nth-child(3)::before { content: 'Unit: '; font-size: 10px; color: var(--text-muted); }
  #ovItemsTable tbody td:nth-child(4)::before { content: ' × '; color: var(--text-muted); font-size: 11px; }
  #ovItemsTable tbody td:nth-child(5) {
    display: flex !important;
    justify-content: flex-end;
    font-weight: 700;
    font-size: 13px;
    color: var(--cyan);
    padding-top: 6px !important;
    border-top: 1px solid var(--border2) !important;
    margin-top: 6px;
  }
  #ovItemsTable tfoot tr {
    display: flex !important;
    justify-content: space-between;
    align-items: center;
    padding: 6px 14px;
    border-top: 1px solid var(--border2);
  }
  #ovItemsTable tfoot td {
    display: block !important;
    border: none !important;
    padding: 2px 0 !important;
    font-size: 12px;
  }
}

/* ── 4. Workflow indicator ───────────────────────────────── */
.ov-flow-wrap {
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
  scrollbar-width: none;
  padding-bottom: 4px;
  margin-bottom: 16px;
}
.ov-flow-wrap::-webkit-scrollbar { display: none; }
.ov-flow-inner { display: flex; align-items: center; gap: 0; width: max-content; min-width: 100%; }

/* ── 5. Status update form row ─────────────────────────────*/
.ov-status-row { display: flex; gap: 8px; flex-wrap: wrap; align-items: flex-end; }
.ov-status-row > div { flex: 1; min-width: 140px; }
@media (max-width: 479px) { .ov-status-row > button { width: 100%; } }

/* ── 6. Right sidebar: pull above items on tablet/mobile ───*/
@media (max-width: 991px) { .ov-info-col { order: -1; } }

/* ── 7. Info row pattern (label / value) ───────────────────
   Prevents label–value collision on long values.            */
.ov-info-row {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 8px;
  padding: 8px 0;
  border-bottom: 1px solid var(--border2);
}
.ov-info-row:last-child { border-bottom: none; }
.ov-info-label { font-size: 12px; color: var(--text-muted); flex-shrink: 0; }
.ov-info-value { font-size: 12px; text-align: right; word-break: break-word; }

/* ── 8. Payment buttons full-width on mobile ───────────────*/
@media (max-width: 575px) {
  .ov-pay-actions { flex-direction: column; }
  .ov-pay-actions > button { width: 100%; }
}

/* ── 9. Customer card ──────────────────────────────────────*/
.ov-cust-avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 16px;
  font-weight: 700;
  flex-shrink: 0;
}
.ov-cust-contact-row {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 7px 0;
  border-bottom: 1px solid var(--border2);
  font-size: 12px;
}
.ov-cust-contact-row:last-child { border-bottom: none; }
.ov-cust-contact-icon {
  width: 26px;
  height: 26px;
  border-radius: 6px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  font-size: 11px;
}
.ov-cust-contact-link {
  text-decoration: none;
  color: var(--text);
  font-size: 12px;
  word-break: break-all;
  transition: color .15s;
}
.ov-cust-contact-link:hover { color: var(--cyan); }
</style>

<!-- ── Page header ──────────────────────────────────────── -->
<div class="ov-header">
  <div>
    <h4 style="font-family:monospace;"><?= e($order['order_number']) ?></h4>
    <p class="page-subtitle" style="display:flex;flex-wrap:wrap;gap:6px;align-items:center;margin:0;">
      <span class="badge" style="background:<?= $ss['bg'] ?>;color:<?= $ss['color'] ?>;"><?= ucfirst($order['status']) ?></span>
      <span class="badge" style="background:<?= $ps['bg'] ?>;color:<?= $ps['color'] ?>;"><?= ucfirst($order['payment_status']) ?> — <?= ucfirst(str_replace('_',' ',$order['payment_method'])) ?></span>
      <span style="font-size:12px;color:var(--text-muted);"><?= date('M j, Y H:i', strtotime($order['created_at'])) ?></span>
    </p>
  </div>
  <div class="ov-actions">
    <a href="<?= url('admin/orders/invoice/'.$order['id']) ?>" target="_blank"
       style="background:var(--surface2);color:var(--text-dim);border:1px solid var(--border2);padding:5px 12px;border-radius:7px;font-size:12px;text-decoration:none;display:inline-flex;align-items:center;gap:4px;" title="Preview Invoice">
      <i class="fa fa-file-text-o"></i><span class="d-none d-sm-inline">Invoice</span>
    </a>
    <a href="<?= url('admin/orders/invoice/'.$order['id'].'/download') ?>" target="_blank"
       style="background:var(--cyan-dim);color:var(--cyan);border:1px solid rgba(0,212,255,.25);padding:5px 12px;border-radius:7px;font-size:12px;text-decoration:none;display:inline-flex;align-items:center;gap:4px;" title="Download Invoice PDF">
      <i class="fa fa-download"></i><span class="d-none d-sm-inline">Download</span>
    </a>
    <?php if ($isPaid): ?>
    <a href="<?= url('admin/orders/receipt/'.$order['id']) ?>" target="_blank"
       style="background:var(--green-dim);color:var(--green);border:1px solid rgba(16,185,129,.2);padding:5px 12px;border-radius:7px;font-size:12px;text-decoration:none;display:inline-flex;align-items:center;gap:4px;" title="Preview Receipt">
      <i class="fa fa-check-circle"></i><span class="d-none d-sm-inline">Receipt</span>
    </a>
    <a href="<?= url('admin/orders/receipt/'.$order['id'].'/download') ?>" target="_blank"
       style="background:var(--green-dim);color:var(--green);border:1px solid rgba(16,185,129,.2);padding:5px 12px;border-radius:7px;font-size:12px;text-decoration:none;display:inline-flex;align-items:center;gap:4px;" title="Download Receipt PDF">
      <i class="fa fa-download"></i><span class="d-none d-sm-inline">DL Receipt</span>
    </a>
    <?php endif; ?>
    <a href="<?= url('admin/orders') ?>"
       class="btn btn-sm" style="background:var(--surface2);color:var(--text-dim);border:1px solid var(--border2);">
      <i class="fa fa-arrow-left me-1"></i><span class="d-none d-sm-inline">Back</span>
    </a>
  </div>
</div>

<div class="row g-3">

  <!-- ── Left column: items + status + history ──────────── -->
  <div class="col-lg-8">

    <!-- Order Items -->
    <div class="card mb-3 ov-items-card">
      <div class="card-header">
        <span class="card-title"><i class="fa fa-shopping-cart me-2" style="color:var(--cyan);"></i>Order Items</span>
      </div>
      <div class="ov-items-scroll">
        <table class="table table-sm align-middle mb-0" id="ovItemsTable">
          <thead>
            <tr>
              <th>Product</th>
              <th style="width:100px;">Variation</th>
              <th style="width:100px;text-align:right;">Unit Price</th>
              <th style="width:55px;text-align:center;">Qty</th>
              <th style="width:110px;text-align:right;">Total</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($order['items'] as $item): ?>
          <tr>
            <td>
              <div style="display:flex;align-items:center;gap:10px;">
                <img src="<?= $item['thumbnail'] ? url('uploads/products/'.$item['thumbnail']) : asset('img/placeholder.webp') ?>"
                     style="width:36px;height:36px;object-fit:cover;border-radius:6px;flex-shrink:0;">
                <span style="font-size:12px;font-weight:600;"><?= e($item['product_name']) ?></span>
              </div>
            </td>
            <td style="font-size:11px;color:var(--text-muted);"><?= e($item['variation_name'] ?? '—') ?></td>
            <td style="text-align:right;font-size:12px;">LKR <?= number_format($item['unit_price'], 2) ?></td>
            <td style="text-align:center;font-weight:700;"><?= (int)$item['quantity'] ?></td>
            <td style="text-align:right;font-weight:700;font-size:13px;">LKR <?= number_format($item['total_price'], 2) ?></td>
          </tr>
          <?php endforeach; ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="4" style="text-align:right;font-size:12px;color:var(--text-muted);padding:8px 14px;">Subtotal:</td>
              <td style="text-align:right;font-size:12px;padding:8px 14px;">LKR <?= number_format($order['subtotal'], 2) ?></td>
            </tr>
            <?php if ($order['discount_amount'] > 0): ?>
            <tr>
              <td colspan="4" style="text-align:right;font-size:12px;color:var(--green);padding:4px 14px;">Discount<?= $order['coupon_code'] ? ' ('.$order['coupon_code'].')' : '' ?>:</td>
              <td style="text-align:right;font-size:12px;color:var(--green);padding:4px 14px;">−LKR <?= number_format($order['discount_amount'], 2) ?></td>
            </tr>
            <?php endif; ?>
            <tr>
              <td colspan="4" style="text-align:right;font-size:12px;color:var(--text-muted);padding:4px 14px;">Shipping (<?= e($order['shipping_method'] ?? 'Standard') ?>):</td>
              <td style="text-align:right;font-size:12px;padding:4px 14px;">
                <?= $order['shipping_amount'] > 0 ? 'LKR '.number_format($order['shipping_amount'],2) : 'Free' ?>
              </td>
            </tr>
            <tr>
              <td colspan="4" style="text-align:right;font-weight:700;font-size:14px;padding:10px 14px;border-top:2px solid var(--border2);">Grand Total:</td>
              <td style="text-align:right;font-weight:700;font-size:15px;color:var(--cyan);padding:10px 14px;border-top:2px solid var(--border2);">
                LKR <?= number_format($order['total_amount'], 2) ?>
              </td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>

    <!-- Status Update -->
    <?php if (!in_array($order['status'], ['cancelled','refunded'])): ?>
    <div class="card mb-3">
      <div class="card-header">
        <span class="card-title"><i class="fa fa-refresh me-2" style="color:var(--amber);"></i>Update Order Status</span>
      </div>
      <div class="card-body">
        <!-- Workflow progress indicator -->
        <?php
        $flow = ['pending','confirmed','processing','shipped','delivered'];
        $currentIdx = array_search($order['status'], $flow);
        ?>
        <div class="ov-flow-wrap">
          <div class="ov-flow-inner">
            <?php foreach ($flow as $i => $step):
              $done    = ($currentIdx !== false && $i < $currentIdx);
              $current = ($currentIdx !== false && $i === $currentIdx);
              $ts      = $statusStyles[$step];
            ?>
            <div style="display:flex;align-items:center;flex-shrink:0;">
              <div style="padding:4px 12px;border-radius:20px;font-size:11px;font-weight:600;white-space:nowrap;
                          background:<?= $current ? $ts['bg'] : ($done ? 'var(--green-dim)' : 'var(--surface2)') ?>;
                          color:<?= $current ? $ts['color'] : ($done ? 'var(--green)' : 'var(--text-muted)') ?>;
                          border:1px solid <?= $current ? 'rgba(255,255,255,.1)' : 'var(--border2)' ?>;">
                <?php if ($done): ?><i class="fa fa-check me-1"></i><?php endif; ?>
                <?= ucfirst($step) ?>
              </div>
              <?php if ($i < count($flow) - 1): ?>
              <div style="width:24px;height:1px;background:var(--border2);flex-shrink:0;"></div>
              <?php endif; ?>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
        <!-- Update form -->
        <form id="statusUpdateForm">
          <?= CSRF::field() ?>
          <input type="hidden" name="order_id" value="<?= (int)$order['id'] ?>">
          <div class="ov-status-row">
            <div>
              <label class="form-label" style="font-size:11px;margin-bottom:4px;">New Status</label>
              <select name="status" class="form-control form-control-sm">
                <?php foreach ($statuses as $s): ?>
                <option value="<?= $s ?>" <?= $order['status']===$s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div>
              <label class="form-label" style="font-size:11px;margin-bottom:4px;">Tracking Number</label>
              <input type="text" name="tracking_number" class="form-control form-control-sm"
                     placeholder="e.g. SL12345678"
                     value="<?= e($order['tracking_number'] ?? '') ?>">
            </div>
            <div>
              <label class="form-label" style="font-size:11px;margin-bottom:4px;">Notes</label>
              <input type="text" name="notes" class="form-control form-control-sm" placeholder="Optional note…">
            </div>
            <button type="submit" id="updateStatusBtn" class="btn btn-primary btn-sm" style="height:31px;align-self:flex-end;">
              <i class="fa fa-check me-1"></i>Update
            </button>
          </div>
        </form>
      </div>
    </div>
    <?php endif; ?>

    <!-- Status History -->
    <div class="card">
      <div class="card-header">
        <span class="card-title"><i class="fa fa-history me-2" style="color:var(--text-muted);"></i>Status History</span>
      </div>
      <div class="card-body" style="padding:16px 20px;">
        <?php $history = array_reverse($order['history']); ?>
        <?php if (empty($history)): ?>
        <p style="color:var(--text-muted);font-size:12px;margin:0;">No history recorded.</p>
        <?php endif; ?>
        <?php foreach ($history as $h): ?>
        <div style="display:flex;gap:14px;margin-bottom:14px;align-items:flex-start;">
          <div style="width:80px;flex-shrink:0;font-size:10px;color:var(--text-muted);padding-top:2px;">
            <?= date('M j', strtotime($h['created_at'])) ?><br>
            <?= date('H:i', strtotime($h['created_at'])) ?>
          </div>
          <div style="flex:1;border-left:2px solid var(--border2);padding-left:14px;min-width:0;">
            <?php
            $hStatus = $h['status'];
            $hStyle  = $statusStyles[$hStatus] ?? $payStyles[$hStatus] ?? ['bg'=>'var(--surface2)','color'=>'var(--text-muted)'];
            if (str_starts_with($hStatus, 'payment_')) {
                $pKey = str_replace('payment_', '', $hStatus);
                $hStyle = $payStyles[$pKey] ?? ['bg'=>'var(--surface2)','color'=>'var(--text-muted)'];
                $hStatus = 'Payment: '.ucfirst($pKey);
            }
            ?>
            <span class="badge" style="background:<?= $hStyle['bg'] ?>;color:<?= $hStyle['color'] ?>;">
              <?= ucfirst($hStatus) ?>
            </span>
            <?php if (!empty($h['notes'])): ?>
            <div style="font-size:11px;color:var(--text-muted);margin-top:4px;word-break:break-word;"><?= e($h['notes']) ?></div>
            <?php endif; ?>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

  </div><!-- /.col-lg-8 -->

  <!-- ── Right column: customer + order meta + payment + shipping ── -->
  <div class="col-lg-4 ov-info-col">

    <!-- ── Customer card ──────────────────────────────────── -->
    <div class="card mb-3">
      <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;">
        <span class="card-title">
          <i class="fa fa-user me-2" style="color:var(--purple);"></i>Customer
        </span>
        <?php if (!$isGuest && $custEmail): ?>
        <a href="<?= url('admin/users?search='.urlencode($custEmail)) ?>"
           style="font-size:11px;color:var(--cyan);text-decoration:none;display:inline-flex;align-items:center;gap:3px;">
          <i class="fa fa-external-link" style="font-size:10px;"></i> Profile
        </a>
        <?php endif; ?>
      </div>
      <div class="card-body" style="padding:14px 16px;">

        <!-- Avatar + name + type -->
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
          <?php
          $initials = strtoupper(substr(trim($custName), 0, 1));
          $avatarBg = $isGuest ? 'var(--surface2)' : 'var(--purple-dim)';
          $avatarCl = $isGuest ? 'var(--text-muted)' : 'var(--purple)';
          ?>
          <div class="ov-cust-avatar" style="background:<?= $avatarBg ?>;color:<?= $avatarCl ?>;">
            <?= e($initials) ?>
          </div>
          <div style="min-width:0;">
            <div style="font-size:13px;font-weight:700;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
              <?= e($custName) ?>
            </div>
            <?php if ($isGuest): ?>
            <span style="font-size:10px;padding:1px 7px;border-radius:4px;background:var(--surface2);
                         color:var(--text-muted);font-weight:700;letter-spacing:.4px;display:inline-block;margin-top:3px;">
              GUEST
            </span>
            <?php else: ?>
            <span style="font-size:10px;padding:1px 7px;border-radius:4px;background:var(--cyan-dim);
                         color:var(--cyan);font-weight:700;letter-spacing:.4px;display:inline-block;margin-top:3px;">
              MEMBER
            </span>
            <?php endif; ?>
          </div>
        </div>

        <!-- Contact rows -->
        <?php if ($custEmail): ?>
        <div class="ov-cust-contact-row">
          <div class="ov-cust-contact-icon" style="background:var(--cyan-dim);color:var(--cyan);">
            <i class="fa fa-envelope-o"></i>
          </div>
          <div style="min-width:0;flex:1;">
            <div style="font-size:10px;color:var(--text-muted);margin-bottom:1px;">Email</div>
            <a href="mailto:<?= e($custEmail) ?>" class="ov-cust-contact-link"><?= e($custEmail) ?></a>
          </div>
        </div>
        <?php endif; ?>

        <?php if ($custPhone): ?>
        <div class="ov-cust-contact-row">
          <div class="ov-cust-contact-icon" style="background:var(--green-dim);color:var(--green);">
            <i class="fa fa-phone"></i>
          </div>
          <div style="min-width:0;flex:1;">
            <div style="font-size:10px;color:var(--text-muted);margin-bottom:1px;">Phone</div>
            <a href="tel:<?= e($custPhone) ?>" class="ov-cust-contact-link"><?= e($custPhone) ?></a>
          </div>
        </div>
        <?php endif; ?>

        <?php if (!$custEmail && !$custPhone): ?>
        <p style="color:var(--text-muted);font-size:12px;margin:0;text-align:center;padding:8px 0;">
          No contact details on record.
        </p>
        <?php endif; ?>

        <?php if ($isGuest): ?>
        <div style="margin-top:10px;padding:8px 10px;border-radius:8px;background:var(--surface2);
                    font-size:11px;color:var(--text-muted);display:flex;align-items:center;gap:6px;">
          <i class="fa fa-info-circle" style="flex-shrink:0;"></i>
          Guest checkout — no registered account.
        </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- ── Order Info card ────────────────────────────────── -->
    <div class="card mb-3">
      <div class="card-header">
        <span class="card-title"><i class="fa fa-info-circle me-2" style="color:var(--cyan);"></i>Order Info</span>
      </div>
      <div class="card-body" style="padding:12px 16px;">
        <?php
        $infoRows = [
          ['Order #',  '<span style="font-family:monospace;font-size:12px;">'.e($order['order_number']).'</span>'],
          ['Status',   '<span class="badge" style="background:'.$ss['bg'].';color:'.$ss['color'].';">'.ucfirst($order['status']).'</span>'],
          ['Placed',   date('M j, Y H:i', strtotime($order['created_at']))],
          ['Items',    (int)array_sum(array_column($order['items'], 'quantity')).' item(s)'],
        ];
        if ($order['tracking_number']):
          $infoRows[] = ['Tracking', '<code style="font-size:11px;background:var(--surface2);color:var(--cyan);padding:2px 6px;border-radius:4px;word-break:break-all;">'.e($order['tracking_number']).'</code>'];
        endif;
        if ($order['coupon_code']):
          $infoRows[] = ['Coupon', '<code style="font-size:11px;background:var(--green-dim);color:var(--green);padding:2px 6px;border-radius:4px;">'.e($order['coupon_code']).'</code>'];
        endif;
        if ($order['notes']):
          $infoRows[] = ['Notes', '<span style="font-size:11px;word-break:break-word;">'.e($order['notes']).'</span>'];
        endif;
        foreach ($infoRows as $row):
        ?>
        <div class="ov-info-row">
          <span class="ov-info-label"><?= $row[0] ?></span>
          <span class="ov-info-value"><?= $row[1] ?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- ── Payment card ───────────────────────────────────── -->
    <div class="card mb-3">
      <div class="card-header">
        <span class="card-title"><i class="fa fa-credit-card me-2" style="color:var(--green);"></i>Payment</span>
      </div>
      <div class="card-body" style="display:flex;flex-direction:column;gap:0;">
        <div class="ov-info-row">
          <span class="ov-info-label">Method</span>
          <span class="ov-info-value" style="font-weight:600;"><?= ucfirst(str_replace('_',' ',$order['payment_method'])) ?></span>
        </div>
        <div class="ov-info-row">
          <span class="ov-info-label">Status</span>
          <span class="badge" style="background:<?= $ps['bg'] ?>;color:<?= $ps['color'] ?>;"><?= ucfirst($order['payment_status']) ?></span>
        </div>
        <?php if (!empty($order['payment_ref'])): ?>
        <div class="ov-info-row">
          <span class="ov-info-label">Reference</span>
          <code style="font-size:11px;background:var(--surface2);color:var(--cyan);padding:2px 6px;border-radius:4px;word-break:break-all;text-align:right;"><?= e($order['payment_ref']) ?></code>
        </div>
        <?php endif; ?>
        <div class="ov-info-row" style="border-bottom:none;">
          <span class="ov-info-label">Grand Total</span>
          <span class="ov-info-value" style="font-size:15px;font-weight:800;color:var(--cyan);">LKR <?= number_format($order['total_amount'], 2) ?></span>
        </div>

        <?php if (!$isPaid && !in_array($order['status'], ['cancelled','refunded'])): ?>
        <div style="display:flex;flex-direction:column;gap:8px;padding-top:10px;border-top:1px solid var(--border2);">
          <input type="text" id="payRefInput" class="form-control form-control-sm"
                 placeholder="Payment reference (optional)">
          <?php if ($isCod): ?>
          <button id="confirmCODBtn" class="btn btn-sm"
                  style="background:var(--green-dim);color:var(--green);border:1px solid rgba(16,185,129,.2);width:100%;">
            <i class="fa fa-money me-1"></i>Confirm Cash Collection
          </button>
          <?php else: ?>
          <div style="display:flex;gap:6px;" class="ov-pay-actions">
            <button id="confirmPayBtn" class="btn btn-sm" style="flex:1;background:var(--green-dim);color:var(--green);border:1px solid rgba(16,185,129,.2);">
              <i class="fa fa-check me-1"></i>Mark Paid
            </button>
            <button id="failPayBtn" class="btn btn-sm" style="flex:1;background:var(--red-dim);color:var(--red);border:1px solid rgba(239,68,68,.2);">
              <i class="fa fa-times me-1"></i>Mark Failed
            </button>
          </div>
          <?php endif; ?>
        </div>
        <?php elseif ($isPaid): ?>
        <div style="display:flex;align-items:center;gap:8px;background:var(--green-dim);padding:8px 12px;
                    border-radius:8px;margin-top:10px;border-top:1px solid var(--border2);">
          <i class="fa fa-check-circle" style="color:var(--green);"></i>
          <span style="font-size:12px;color:var(--green);font-weight:600;">Payment confirmed</span>
        </div>
        <?php if ($order['payment_status'] !== 'refunded'): ?>
        <button id="refundBtn"
                style="margin-top:8px;background:var(--red-dim);color:var(--red);border:1px solid rgba(239,68,68,.2);
                       padding:5px 12px;border-radius:7px;font-size:12px;cursor:pointer;width:100%;">
          <i class="fa fa-undo me-1"></i>Issue Refund
        </button>
        <?php endif; ?>
        <?php endif; ?>
      </div>
    </div>

    <!-- ── Shipping card ──────────────────────────────────── -->
    <div class="card mb-3" id="shippingCard">
      <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;">
        <span class="card-title"><i class="fa fa-truck me-2" style="color:var(--amber);"></i>Shipping</span>
        <?php if (!$isTerminal): ?>
        <button onclick="toggleShippingEdit()"
                style="background:none;border:none;color:var(--cyan);font-size:11px;cursor:pointer;padding:0;" id="shippingEditBtn">
          <i class="fa fa-pencil me-1"></i>Edit
        </button>
        <?php endif; ?>
      </div>
      <div class="card-body" style="display:flex;flex-direction:column;gap:0;">
        <div id="shippingView">
          <div class="ov-info-row">
            <span class="ov-info-label">Method</span>
            <span class="ov-info-value" style="font-weight:600;"><?= e($order['shipping_method'] ?? 'Standard') ?></span>
          </div>
          <div class="ov-info-row" style="border-bottom:none;">
            <span class="ov-info-label">Cost</span>
            <span class="ov-info-value" style="font-weight:600;">
              <?= $order['shipping_amount'] > 0 ? 'LKR '.number_format($order['shipping_amount'],2) : 'Free' ?>
            </span>
          </div>
        </div>
        <div id="shippingEdit" style="display:none;flex-direction:column;gap:8px;padding-top:4px;">
          <div>
            <label class="form-label" style="font-size:11px;margin-bottom:4px;">Shipping Method</label>
            <select id="shippingMethodSel" class="form-control form-control-sm">
              <?php foreach ($shippingMethods as $sm): ?>
              <option value="<?= e($sm['name']) ?>"
                      data-price="<?= (float)$sm['price'] ?>"
                      <?= $order['shipping_method'] === $sm['name'] ? 'selected' : '' ?>>
                <?= e($sm['name']) ?> (<?= $sm['price'] > 0 ? 'LKR '.number_format($sm['price'],2) : 'Free' ?>)
              </option>
              <?php endforeach; ?>
              <option value="custom">Custom…</option>
            </select>
          </div>
          <div>
            <label class="form-label" style="font-size:11px;margin-bottom:4px;">Cost (LKR)</label>
            <input type="number" id="shippingAmtInput" class="form-control form-control-sm"
                   value="<?= (float)$order['shipping_amount'] ?>" min="0" step="0.01">
          </div>
          <div style="display:flex;gap:6px;">
            <button id="saveShippingBtn" class="btn btn-primary btn-sm" style="flex:1;">Save</button>
            <button onclick="toggleShippingEdit()" class="btn btn-sm"
                    style="background:var(--surface2);color:var(--text-dim);border:1px solid var(--border2);">Cancel</button>
          </div>
        </div>
      </div>
    </div>

    <!-- ── Shipping Address card ──────────────────────────── -->
    <div class="card">
      <div class="card-header">
        <span class="card-title"><i class="fa fa-map-marker me-2" style="color:var(--text-muted);"></i>Shipping Address</span>
      </div>
      <div class="card-body">
        <address style="margin:0;font-size:12px;color:var(--text-dim);line-height:1.9;">
          <strong style="color:var(--text);"><?= e($order['shipping_name']) ?></strong><br>
          <?php if ($order['shipping_phone']): ?>
          <a href="tel:<?= e($order['shipping_phone']) ?>"
             style="color:var(--text-dim);text-decoration:none;"><?= e($order['shipping_phone']) ?></a><br>
          <?php endif; ?>
          <?= e($order['shipping_address']) ?><br>
          <?= e($order['shipping_city']) ?><?= $order['shipping_state'] ? ', '.e($order['shipping_state']) : '' ?><?= $order['shipping_postal'] ? ' '.e($order['shipping_postal']) : '' ?><br>
          <?= e($order['shipping_country'] ?? 'Sri Lanka') ?>
        </address>
      </div>
    </div>

  </div><!-- /.col-lg-4 -->

</div><!-- /.row -->

<?php $orderId = (int)$order['id']; $extraScript = <<<'JS'
<script>
$(function() {

  // ── Status update ──────────────────────────────────────
  $('#statusUpdateForm').on('submit', function(e) {
    e.preventDefault();
    var btn  = $('#updateStatusBtn');
    var form = $(this);
    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i>Saving…');
    $.ajax({
      url:      SITE_URL + '/admin/orders/update-status',
      type:     'POST',
      data:     form.serialize(),
      dataType: 'json',
      success: function(res) {
        btn.prop('disabled', false).html('<i class="fa fa-check me-1"></i>Update');
        if (res.success) {
          SwalDark.fire({ icon:'success', title: res.message, timer: 1400, showConfirmButton: false })
            .then(function() { location.reload(); });
        } else {
          SwalDark.fire('Error', res.message, 'error');
        }
      },
      error: function(xhr) {
        btn.prop('disabled', false).html('<i class="fa fa-check me-1"></i>Update');
        if (xhr.status === 200) {
          var m = xhr.responseText.match(/\{[\s\S]*"success"[\s\S]*\}/);
          if (m) { try {
            var j = JSON.parse(m[0]);
            if (j.success) { SwalDark.fire({icon:'success',title:j.message||'Updated',timer:1400,showConfirmButton:false}).then(function(){location.reload();}); return; }
            SwalDark.fire('Error', j.message||'Update failed.', 'error'); return;
          } catch(ex) {} }
        }
        var msg = 'Request failed (HTTP ' + xhr.status + ').';
        try { var r = JSON.parse(xhr.responseText); if (r.message) msg = r.message; } catch(ex) {}
        SwalDark.fire('Error', msg, 'error');
      }
    });
  });

  // ── Payment helpers ──────────────────────────────────────
  function updatePayment(payStatus) {
    var ref = $('#payRefInput').val() || '';
    ajaxPost('admin/orders/update-payment',
      { order_id: ORDER_ID, payment_status: payStatus, payment_ref: ref },
      function(res) {
        if (res.success) SwalDark.fire({ icon:'success', title: res.message, timer: 1300, showConfirmButton: false }).then(function() { location.reload(); });
        else SwalDark.fire('Error', res.message, 'error');
      }
    );
  }

  $('#confirmPayBtn').on('click', function() {
    SwalDark.fire({ title:'Mark as Paid?', icon:'question', showCancelButton:true, confirmButtonText:'Yes, Mark Paid' })
    .then(function(r) { if (r.isConfirmed) updatePayment('paid'); });
  });
  $('#failPayBtn').on('click', function() {
    SwalDark.fire({ title:'Mark Payment as Failed?', text:'Order status will not change.', icon:'warning', showCancelButton:true, confirmButtonText:'Mark Failed' })
    .then(function(r) { if (r.isConfirmed) updatePayment('unpaid'); });
  });
  $('#confirmCODBtn').on('click', function() {
    SwalDark.fire({ title:'Confirm Cash Collected?', text:'This marks the payment as received for this COD order.', icon:'question', showCancelButton:true, confirmButtonText:'Confirm' })
    .then(function(r) { if (r.isConfirmed) updatePayment('paid'); });
  });
  $('#refundBtn').on('click', function() {
    SwalDark.fire({ title:'Issue Refund?', text:'This marks payment as refunded.', icon:'warning', showCancelButton:true, confirmButtonText:'Issue Refund', confirmButtonColor:'#ef4444' })
    .then(function(r) { if (r.isConfirmed) updatePayment('refunded'); });
  });

  // ── Shipping edit ────────────────────────────────────────
  window.toggleShippingEdit = function() {
    var isEdit = $('#shippingEdit').is(':visible');
    $('#shippingView').toggle(isEdit);
    $('#shippingEdit').toggle(!isEdit);
  };
  $('#shippingMethodSel').on('change', function() {
    var price = parseFloat($(this).find(':selected').data('price')) || 0;
    if ($(this).val() !== 'custom') $('#shippingAmtInput').val(price);
  });
  $('#saveShippingBtn').on('click', function() {
    var btn    = $(this);
    var method = $('#shippingMethodSel').val();
    var amount = parseFloat($('#shippingAmtInput').val()) || 0;
    btn.prop('disabled', true).text('Saving…');
    ajaxPost('admin/orders/update-shipping',
      { order_id: ORDER_ID, shipping_method: method, shipping_amount: amount },
      function(res) {
        btn.prop('disabled', false).text('Save');
        if (res.success) SwalDark.fire({ icon:'success', title: res.message, timer: 1300, showConfirmButton: false }).then(function() { location.reload(); });
        else SwalDark.fire('Error', res.message, 'error');
      }
    );
  });

});
</script>
JS;
?>
<script>const ORDER_ID = <?= $orderId ?>;</script>
