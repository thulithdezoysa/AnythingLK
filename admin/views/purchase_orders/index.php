<?php /* admin/views/purchase_orders/index.php */ ?>
<style>
/* ════════════════════════════════════════════════════════════
   PURCHASE ORDERS INDEX — RESPONSIVE
   Scoped to #poTable to avoid bleed to other pages.
   ════════════════════════════════════════════════════════════ */

/* ── 1. Scroll wrapper ──────────────────────────────────────
   .admin-table has overflow:hidden (border-radius clipping).
   A sibling wrapper takes over horizontal scroll so they
   don't fight each other.                                    */
.po-table-wrap {
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
  scrollbar-width: thin;
  scrollbar-color: rgba(255,255,255,.12) transparent;
}
.po-table-wrap::-webkit-scrollbar       { height: 4px; }
.po-table-wrap::-webkit-scrollbar-track { background: transparent; }
.po-table-wrap::-webkit-scrollbar-thumb { background: rgba(255,255,255,.12); border-radius: 4px; }

/* Table minimum width on tablet+ */
@media (min-width: 768px) {
  #poTable { min-width: 780px; }
}

/* ── 2. Mobile (< 768 px): table → stacked card layout ───── */
@media (max-width: 767px) {

  /* Release .admin-table's overflow:hidden so cards breathe */
  .po-table-wrap .admin-table { overflow: visible !important; }

  #poTable { min-width: 0 !important; }

  /* Hide column headers */
  #poTable thead { display: none; }

  /* 3-column card grid per row:
     Col A (1fr)  : PO# + Vendor + dates
     Col B (auto) : Total + Status + item-count
     Col C (auto) : Action button              */
  #poTable tbody tr {
    display: grid;
    grid-template-columns: 1fr auto auto;
    grid-template-rows: auto auto auto auto;
    column-gap: 10px;
    row-gap: 3px;
    padding: 12px 14px;
    border-top: none;
    border-bottom: 1px solid var(--border);
    transition: background .15s;
  }
  #poTable tbody tr:last-child { border-bottom: none; }
  #poTable tbody tr:hover      { background: var(--surface2); }

  /* Strip all td defaults */
  #poTable tbody td {
    display: block !important;
    padding: 0 !important;
    border: none !important;
    background: transparent !important;
    vertical-align: unset !important;
  }
  #poTable tbody tr:hover td { background: transparent !important; }

  /* td1: PO Number — col A, row 1 */
  #poTable tbody td:nth-child(1) {
    grid-column: 1;
    grid-row: 1;
    align-self: center;
  }
  #poTable tbody td:nth-child(1) a { font-size: 13px !important; }

  /* td2: Vendor name — col A, row 2 */
  #poTable tbody td:nth-child(2) {
    grid-column: 1;
    grid-row: 2;
    font-size: 12px;
    color: var(--text-dim);
  }

  /* td3: Items count — col B, row 3 (small label) */
  #poTable tbody td:nth-child(3) {
    grid-column: 2;
    grid-row: 3;
    text-align: right;
    font-size: 11px;
    color: var(--text-muted);
  }
  #poTable tbody td:nth-child(3)::after { content: ' items'; font-size: 10px; }

  /* td4: Total — col B, row 1 (prominent) */
  #poTable tbody td:nth-child(4) {
    grid-column: 2;
    grid-row: 1;
    text-align: right;
    font-size: 13px !important;
    font-weight: 800 !important;
    align-self: center;
    white-space: nowrap;
  }

  /* td5: Expected date — col A, row 3 */
  #poTable tbody td:nth-child(5) {
    grid-column: 1;
    grid-row: 3;
    font-size: 11px;
    color: var(--text-muted);
  }
  #poTable tbody td:nth-child(5)::before { content: 'Due: '; font-size: 10px; }

  /* td6: Status badge — col B, row 2 */
  #poTable tbody td:nth-child(6) {
    grid-column: 2;
    grid-row: 2;
    text-align: right;
    align-self: center;
  }

  /* td7: Created date — col A, row 4 */
  #poTable tbody td:nth-child(7) {
    grid-column: 1;
    grid-row: 4;
    font-size: 10px;
    color: var(--text-muted);
  }
  #poTable tbody td:nth-child(7)::before { content: 'Created: '; }

  /* td8: View action — col C, spans all 4 rows */
  #poTable tbody td:nth-child(8) {
    grid-column: 3;
    grid-row: 1 / 5;
    align-self: center;
    text-align: center;
  }

  /* Empty state: bypass grid */
  #poTable tbody tr.po-empty-row {
    display: block !important;
    border-bottom: none;
  }
  #poTable tbody tr.po-empty-row td {
    display: block !important;
    padding: 40px 14px !important;
    text-align: center !important;
    color: var(--text-muted);
  }
}

/* ── 3. Very small phones (< 380 px) ───────────────────── */
@media (max-width: 379px) {
  #poTable tbody tr {
    grid-template-columns: 1fr auto 48px;
    column-gap: 7px;
    padding: 10px 10px;
  }
  #poTable tbody td:nth-child(4) { font-size: 12px !important; white-space: normal; }
}

/* ── 4. Page-header stacking on very small phones ────────── */
@media (max-width: 480px) {
  .po-index-header { flex-direction: column; align-items: flex-start !important; }
}
</style>

<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2 po-index-header">
  <div>
    <h4>Purchase Orders
      <span class="badge ms-1" style="background:var(--surface2);color:var(--text-muted);font-size:12px;font-weight:600;border-radius:8px;padding:3px 9px;"><?= number_format($total) ?></span>
    </h4>
    <p class="page-subtitle">Manage vendor purchase orders and track deliveries.</p>
  </div>
</div>

<?php
$searchFields = [['name'=>'search','value'=>$search,'placeholder'=>'Search PO number or vendor…']];
$filterFields = [['name'=>'status','value'=>$status,'options'=>[
    '' => 'All Statuses', 'draft'=>'Draft', 'pending'=>'Pending',
    'approved'=>'Approved', 'received'=>'Received', 'cancelled'=>'Cancelled',
]]];
$extraButtons = '<a href="' . url('admin/purchase-orders/create') . '" class="btn btn-primary btn-sm"><i class="fa fa-plus me-1"></i>Create PO</a>';
$itemLabel    = 'purchase orders';
include __DIR__ . '/../layouts/_pagination.php';
?>

<div class="po-table-wrap">
<div class="admin-table">
  <table class="table table-sm align-middle mb-0" id="poTable">
    <thead>
      <tr>
        <th style="width:130px;">PO Number</th>
        <th>Vendor</th>
        <th style="width:60px;text-align:center;">Items</th>
        <th style="width:120px;text-align:right;">Total</th>
        <th style="width:110px;">Expected</th>
        <th style="width:100px;">Status</th>
        <th style="width:100px;">Created</th>
        <th style="width:70px;text-align:center;">Actions</th>
      </tr>
    </thead>
    <tbody>
    <?php
    $statusStyles = [
      'draft'     => ['bg'=>'var(--surface2)',   'color'=>'var(--text-dim)'],
      'pending'   => ['bg'=>'var(--amber-dim)',  'color'=>'var(--amber)'],
      'approved'  => ['bg'=>'var(--cyan-dim)',   'color'=>'var(--cyan)'],
      'received'  => ['bg'=>'var(--green-dim)',  'color'=>'var(--green)'],
      'cancelled' => ['bg'=>'var(--red-dim)',    'color'=>'var(--red)'],
    ];
    foreach ($orders as $o):
      $ss = $statusStyles[$o['status']] ?? $statusStyles['draft'];
    ?>
    <tr>
      <!-- td1: PO Number -->
      <td>
        <a href="<?= url('admin/purchase-orders/view/'.$o['id']) ?>"
           style="font-weight:700;font-size:12px;color:var(--cyan);text-decoration:none;font-family:monospace;">
          <?= e($o['po_number']) ?>
        </a>
      </td>
      <!-- td2: Vendor -->
      <td style="font-size:13px;font-weight:600;"><?= e($o['company_name'] ?? '—') ?></td>
      <!-- td3: Items -->
      <td style="text-align:center;font-size:13px;"><?= (int)$o['item_count'] ?></td>
      <!-- td4: Total -->
      <td style="text-align:right;font-weight:700;font-size:13px;">
        LKR <?= number_format($o['total_amount'], 2) ?>
      </td>
      <!-- td5: Expected -->
      <td style="font-size:12px;color:var(--text-muted);">
        <?= $o['expected_date'] ? date('M j, Y', strtotime($o['expected_date'])) : '—' ?>
      </td>
      <!-- td6: Status -->
      <td>
        <span class="badge" style="background:<?= $ss['bg'] ?>;color:<?= $ss['color'] ?>;">
          <?= ucfirst($o['status']) ?>
        </span>
      </td>
      <!-- td7: Created -->
      <td style="font-size:11px;color:var(--text-muted);">
        <?= date('M j, Y', strtotime($o['created_at'])) ?>
      </td>
      <!-- td8: Actions -->
      <td style="text-align:center;">
        <a href="<?= url('admin/purchase-orders/view/'.$o['id']) ?>"
           style="background:var(--cyan-dim);color:var(--cyan);border:none;padding:4px 10px;border-radius:6px;font-size:12px;text-decoration:none;display:inline-block;white-space:nowrap;">
          View
        </a>
      </td>
    </tr>
    <?php endforeach; ?>
    <?php if (empty($orders)): ?>
    <tr class="po-empty-row">
      <td colspan="8" class="text-center py-5" style="color:var(--text-muted);">No purchase orders found.</td>
    </tr>
    <?php endif; ?>
    </tbody>
  </table>
</div><!-- /.admin-table -->
</div><!-- /.po-table-wrap -->

<?php $itemLabel = 'purchase orders'; include __DIR__ . '/../layouts/_pagnav.php'; ?>
