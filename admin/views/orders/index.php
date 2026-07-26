<?php /* admin/views/orders/index.php */ ?>
<style>
/* ════════════════════════════════════════════════════════════
   ORDERS INDEX — RESPONSIVE
   All selectors scoped to .oi-* / #ordersTable to avoid bleed.
   ════════════════════════════════════════════════════════════ */

/* ── 1. Page header ─────────────────────────────────────────*/
@media (max-width: 480px) {
  .oi-header { flex-direction: column; align-items: flex-start !important; }
}

/* ── 2. Status-filter tab bar ───────────────────────────────
   Horizontal pill bar that scrolls on mobile instead of
   wrapping to multiple lines (which wastes vertical space).  */
.oi-tabs-wrap {
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
  scrollbar-width: none;
  margin-bottom: 16px;
  -webkit-mask-image: linear-gradient(to right, #000 85%, transparent 100%);
          mask-image: linear-gradient(to right, #000 85%, transparent 100%);
}
.oi-tabs-wrap::-webkit-scrollbar { display: none; }
.oi-tabs {
  display: flex;
  gap: 6px;
  width: max-content;
  min-width: 100%;
  padding-right: 20px;
}
.oi-tab {
  padding: 5px 14px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
  text-decoration: none;
  white-space: nowrap;
  flex-shrink: 0;
  display: inline-block;
}

/* ── 3. Table scroll wrapper ────────────────────────────────*/
.oi-table-wrap {
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
  scrollbar-width: thin;
  scrollbar-color: rgba(255,255,255,.12) transparent;
}
.oi-table-wrap::-webkit-scrollbar       { height: 4px; }
.oi-table-wrap::-webkit-scrollbar-track { background: transparent; }
.oi-table-wrap::-webkit-scrollbar-thumb { background: rgba(255,255,255,.12); border-radius: 4px; }

/* Table min-width on tablet+ (wider to accommodate customer detail column) */
@media (min-width: 768px) {
  #ordersTable { min-width: 840px; }
}

/* ── 4. Customer contact meta (email + phone) ───────────────
   Visible on desktop; hidden on mobile card layout where the
   cell is too narrow to fit multi-line contact info.         */
@media (max-width: 767px) {
  #ordersTable .oi-cust-meta { display: none !important; }
}

/* ── 5. Mobile (< 768px): table → stacked card layout ───── */
@media (max-width: 767px) {

  .oi-table-wrap .admin-table { overflow: visible !important; }
  #ordersTable { min-width: 0 !important; }
  #ordersTable thead { display: none; }

  /* 3-column card grid:
     Col A (1fr)  : Order # + Customer name + Date
     Col B (auto) : Total + Payment badge
     Col C (auto) : Status badge + View button             */
  #ordersTable tbody tr {
    display: grid;
    grid-template-columns: 1fr auto auto;
    grid-template-rows: auto auto auto;
    column-gap: 10px;
    row-gap: 3px;
    padding: 12px 14px;
    border-top: none;
    border-bottom: 1px solid var(--border);
    transition: background .15s;
  }
  #ordersTable tbody tr:last-child { border-bottom: none; }
  #ordersTable tbody tr:hover      { background: var(--surface2); }

  #ordersTable tbody td {
    display: block !important;
    padding: 0 !important;
    border: none !important;
    background: transparent !important;
    vertical-align: unset !important;
  }
  #ordersTable tbody tr:hover td { background: transparent !important; }

  /* td1: Order # — col A row 1 */
  #ordersTable tbody td:nth-child(1) { grid-column: 1; grid-row: 1; align-self: center; }

  /* td2: Customer — col A row 2 */
  #ordersTable tbody td:nth-child(2) {
    grid-column: 1;
    grid-row: 2;
  }
  /* Name within mobile customer cell */
  #ordersTable tbody td:nth-child(2) .oi-cust-name {
    font-size: 12px;
    color: var(--text-dim);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    max-width: 160px;
    display: inline-block;
  }

  /* td3: Items — hide on mobile */
  #ordersTable tbody td:nth-child(3) { display: none !important; }

  /* td4: Total — col B row 1 */
  #ordersTable tbody td:nth-child(4) {
    grid-column: 2;
    grid-row: 1;
    text-align: right;
    font-size: 13px !important;
    font-weight: 800 !important;
    align-self: center;
    white-space: nowrap;
  }

  /* td5: Payment badge — col B row 2 */
  #ordersTable tbody td:nth-child(5) {
    grid-column: 2;
    grid-row: 2;
    text-align: right;
    align-self: center;
  }

  /* td6: Status badge — col C rows 1-2 */
  #ordersTable tbody td:nth-child(6) {
    grid-column: 3;
    grid-row: 1 / 3;
    align-self: center;
    text-align: right;
  }

  /* td7: Date — col A row 3 */
  #ordersTable tbody td:nth-child(7) {
    grid-column: 1;
    grid-row: 3;
    font-size: 10px;
    color: var(--text-muted);
  }

  /* td8: View — col B-C row 3 */
  #ordersTable tbody td:nth-child(8) {
    grid-column: 2 / 4;
    grid-row: 3;
    text-align: right;
    align-self: center;
  }

  /* Empty state */
  #ordersTable tbody tr.oi-empty-row { display: block !important; border-bottom: none; }
  #ordersTable tbody tr.oi-empty-row td {
    display: block !important;
    padding: 40px 14px !important;
    text-align: center !important;
    color: var(--text-muted);
  }
}

/* ── 6. Very small phones (< 380 px) ───────────────────── */
@media (max-width: 379px) {
  #ordersTable tbody tr { grid-template-columns: 1fr auto 52px; column-gap: 7px; padding: 10px 10px; }
  #ordersTable tbody td:nth-child(4) { font-size: 12px !important; white-space: normal; }
  #ordersTable tbody td:nth-child(2) .oi-cust-name { max-width: 120px; }
}
</style>

<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2 oi-header">
  <div>
    <h4>Orders
      <span class="badge ms-1" style="background:var(--surface2);color:var(--text-muted);font-size:12px;font-weight:600;border-radius:8px;padding:3px 9px;"><?= number_format($total) ?></span>
    </h4>
    <p class="page-subtitle">Manage customer orders, track fulfilment and payments.</p>
  </div>
</div>

<?php
$searchFields = [['name'=>'search','value'=>$search,'placeholder'=>'Order #, name, email or phone…']];
$filterFields = [];
$extraButtons = '';
$itemLabel    = 'orders';
include __DIR__ . '/../layouts/_pagination.php';
?>

<!-- Status filter tabs -->
<div class="oi-tabs-wrap">
  <div class="oi-tabs">
    <?php
    $tabStyles = [
      ''           => ['bg'=>'var(--surface2)',  'color'=>'var(--text-dim)'],
      'pending'    => ['bg'=>'var(--amber-dim)', 'color'=>'var(--amber)'],
      'confirmed'  => ['bg'=>'var(--cyan-dim)',  'color'=>'var(--cyan)'],
      'processing' => ['bg'=>'var(--cyan-dim)',  'color'=>'var(--cyan)'],
      'shipped'    => ['bg'=>'var(--surface2)',  'color'=>'var(--text-dim)'],
      'delivered'  => ['bg'=>'var(--green-dim)', 'color'=>'var(--green)'],
      'cancelled'  => ['bg'=>'var(--red-dim)',   'color'=>'var(--red)'],
      'refunded'   => ['bg'=>'var(--surface2)',  'color'=>'var(--text-muted)'],
    ];
    $allTabs = array_merge([''], $statuses);
    foreach ($allTabs as $s):
      $isActive = ($status === $s);
      $ts = $tabStyles[$s] ?? $tabStyles[''];
      $label = $s === '' ? 'All' : ucfirst($s);
      $href  = '?' . http_build_query(array_merge($_GET, ['status' => $s, 'page' => 1]));
    ?>
    <a href="<?= $href ?>" class="oi-tab"
       style="background:<?= $isActive ? $ts['bg'] : 'var(--surface2)' ?>;
              color:<?= $isActive ? $ts['color'] : 'var(--text-muted)' ?>;
              border:1px solid <?= $isActive ? 'rgba(255,255,255,.08)' : 'var(--border2)' ?>;">
      <?= $label ?>
    </a>
    <?php endforeach; ?>
  </div>
</div>

<div class="oi-table-wrap">
<div class="admin-table">
  <table class="table table-sm align-middle mb-0" id="ordersTable">
    <thead>
      <tr>
        <th style="width:130px;">Order #</th>
        <th>Customer</th>
        <th style="width:55px;text-align:center;">Items</th>
        <th style="width:120px;text-align:right;">Total</th>
        <th style="width:90px;">Payment</th>
        <th style="width:100px;">Status</th>
        <th style="width:95px;">Date</th>
        <th style="width:65px;text-align:center;">Actions</th>
      </tr>
    </thead>
    <tbody>
    <?php
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
    foreach ($orders as $o):
      $ss = $statusStyles[$o['status']] ?? $statusStyles['pending'];
      $ps = $payStyles[$o['payment_status']] ?? $payStyles['unpaid'];

      /* ── Resolved customer contact ─────────────────────────
         Name   : shipping_name (always set at checkout)
         Email  : user account email for members; guest_email for guests
         Phone  : shipping_phone (always set at checkout)          */
      $custName  = $o['shipping_name'] ?: ($o['full_name'] ?? 'Guest');
      $custEmail = $o['user_id'] ? ($o['user_email'] ?? '') : ($o['guest_email'] ?? '');
      $custPhone = $o['shipping_phone'] ?? '';
      $isGuest   = !$o['user_id'];
    ?>
    <tr>
      <!-- td1: Order # -->
      <td>
        <a href="<?= url('admin/orders/view/'.$o['id']) ?>"
           style="font-weight:700;font-size:12px;color:var(--cyan);text-decoration:none;font-family:monospace;">
          <?= e($o['order_number']) ?>
        </a>
      </td>

      <!-- td2: Customer — name + type badge + email + phone (email/phone hidden on mobile) -->
      <td>
        <div style="display:flex;flex-direction:column;gap:2px;min-width:0;">
          <!-- Name row: name + guest/member badge -->
          <div style="display:flex;align-items:center;gap:5px;">
            <span class="oi-cust-name"
                  style="font-size:12px;font-weight:600;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:200px;">
              <?= e($custName) ?>
            </span>
            <?php if ($isGuest): ?>
            <span style="font-size:9px;padding:1px 5px;border-radius:4px;background:var(--surface2);
                         color:var(--text-muted);font-weight:700;flex-shrink:0;letter-spacing:.3px;">GUEST</span>
            <?php else: ?>
            <span style="font-size:9px;padding:1px 5px;border-radius:4px;background:var(--cyan-dim);
                         color:var(--cyan);font-weight:700;flex-shrink:0;letter-spacing:.3px;">MEMBER</span>
            <?php endif; ?>
          </div>
          <!-- Email (desktop only) -->
          <?php if ($custEmail): ?>
          <a href="mailto:<?= e($custEmail) ?>" class="oi-cust-meta"
             style="font-size:11px;color:var(--text-muted);text-decoration:none;
                    display:flex;align-items:center;gap:3px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:220px;">
            <i class="fa fa-envelope-o" style="font-size:10px;flex-shrink:0;opacity:.7;"></i>
            <?= e($custEmail) ?>
          </a>
          <?php endif; ?>
          <!-- Phone (desktop only) -->
          <?php if ($custPhone): ?>
          <a href="tel:<?= e($custPhone) ?>" class="oi-cust-meta"
             style="font-size:11px;color:var(--text-muted);text-decoration:none;
                    display:flex;align-items:center;gap:3px;">
            <i class="fa fa-phone" style="font-size:10px;flex-shrink:0;opacity:.7;"></i>
            <?= e($custPhone) ?>
          </a>
          <?php endif; ?>
        </div>
      </td>

      <!-- td3: Items -->
      <td style="text-align:center;font-size:13px;"><?= (int)$o['item_count'] ?></td>

      <!-- td4: Total -->
      <td style="text-align:right;font-weight:700;font-size:13px;">
        LKR <?= number_format($o['total_amount'], 2) ?>
      </td>

      <!-- td5: Payment -->
      <td>
        <span class="badge" style="background:<?= $ps['bg'] ?>;color:<?= $ps['color'] ?>;">
          <?= ucfirst($o['payment_status']) ?>
        </span>
      </td>

      <!-- td6: Status -->
      <td>
        <span class="badge" style="background:<?= $ss['bg'] ?>;color:<?= $ss['color'] ?>;">
          <?= ucfirst($o['status']) ?>
        </span>
      </td>

      <!-- td7: Date -->
      <td style="font-size:11px;color:var(--text-muted);">
        <?= date('M j, Y', strtotime($o['created_at'])) ?>
      </td>

      <!-- td8: Actions -->
      <td style="text-align:center;">
        <a href="<?= url('admin/orders/view/'.$o['id']) ?>"
           style="background:var(--cyan-dim);color:var(--cyan);border:none;padding:4px 10px;
                  border-radius:6px;font-size:12px;text-decoration:none;display:inline-block;">
          View
        </a>
      </td>
    </tr>
    <?php endforeach; ?>
    <?php if (empty($orders)): ?>
    <tr class="oi-empty-row">
      <td colspan="8" class="text-center py-5" style="color:var(--text-muted);">
        No orders found<?= $status ? ' with status "'.ucfirst($status).'"' : '' ?>.
      </td>
    </tr>
    <?php endif; ?>
    </tbody>
  </table>
</div><!-- /.admin-table -->
</div><!-- /.oi-table-wrap -->

<?php $itemLabel = 'orders'; include __DIR__ . '/../layouts/_pagnav.php'; ?>
