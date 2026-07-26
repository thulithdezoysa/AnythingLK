<?php /* admin/views/purchase_orders/view.php */
$statusStyles = [
  'draft'     => ['bg'=>'var(--surface2)',  'color'=>'var(--text-dim)'],
  'pending'   => ['bg'=>'var(--amber-dim)', 'color'=>'var(--amber)'],
  'approved'  => ['bg'=>'var(--cyan-dim)',  'color'=>'var(--cyan)'],
  'received'  => ['bg'=>'var(--green-dim)', 'color'=>'var(--green)'],
  'cancelled' => ['bg'=>'var(--red-dim)',   'color'=>'var(--red)'],
];
$ss = $statusStyles[$po['status']] ?? $statusStyles['draft'];
?>
<style>
/* ════════════════════════════════════════════════════════════
   PURCHASE ORDER VIEW — RESPONSIVE
   ════════════════════════════════════════════════════════════ */

/* ── 1. Page header action buttons ─────────────────────────
   Already has flex-wrap:wrap inline; we refine sizing on
   small phones so buttons fill the row evenly.               */
.po-view-actions {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
  align-items: center;
}
@media (max-width: 575px) {
  .po-view-actions { width: 100%; }
  .po-view-actions > * {
    flex: 1 1 auto;
    justify-content: center;
    text-align: center;
    min-width: 100px;
  }
  /* Long "Mark as Received" text — abbreviate on mobile */
  .btn-receive-long { display: none; }
  .btn-receive-short { display: inline !important; }
}
@media (min-width: 576px) {
  .btn-receive-short { display: none !important; }
}

/* ── 2. Page header stacks on very small phones ─────────── */
@media (max-width: 575px) {
  .po-view-header {
    flex-direction: column;
    align-items: flex-start !important;
    gap: 16px;
  }
}

/* ── 3. Row layout: info sidebar above items on mobile ─────
   On tablet (768–991px), col-lg-8/col-lg-4 both become 100%.
   Reorder so PO info appears first (scannability).           */
@media (max-width: 991px) {
  .po-view-row    { display: flex; flex-direction: column; }
  .po-info-col    { order: -1; margin-bottom: 0; }
  .po-items-col   { order:  1; }
}

/* ── 4. Items table inside .card ────────────────────────────
   .card has overflow:hidden for border-radius clipping.
   We override only for the items card so its internal
   overflow-x:auto div can scroll.                            */
.po-items-card { overflow: visible !important; }

/* Give the scroll container the same thin-scrollbar style   */
.po-items-inner {
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
  scrollbar-width: thin;
  scrollbar-color: rgba(255,255,255,.12) transparent;
  /* Re-clip just the rounded bottom corners                  */
  border-radius: 0 0 var(--radius) var(--radius);
}
.po-items-inner::-webkit-scrollbar       { height: 4px; }
.po-items-inner::-webkit-scrollbar-track { background: transparent; }
.po-items-inner::-webkit-scrollbar-thumb { background: rgba(255,255,255,.12); border-radius: 4px; }

@media (min-width: 768px) {
  #poItemsTable { min-width: 560px; }
}

/* ── 5. Mobile card layout for items table (< 576 px) ────── */
@media (max-width: 575px) {

  .po-items-inner { overflow: visible; border-radius: 0; }

  #poItemsTable { min-width: 0 !important; }
  #poItemsTable thead { display: none; }

  /* 2-column card:
     Col A (1fr)  : Product thumb + name + SKU
     Col B (auto) : Ordered/Received qty + totals             */
  #poItemsTable tbody tr {
    display: grid;
    grid-template-columns: 1fr auto;
    grid-template-rows: auto auto auto;
    column-gap: 12px;
    row-gap: 4px;
    padding: 12px 14px;
    border-top: none;
    border-bottom: 1px solid var(--border);
  }
  #poItemsTable tbody tr:last-child { border-bottom: none; }

  #poItemsTable tbody td {
    display: block !important;
    padding: 0 !important;
    border: none !important;
    background: transparent !important;
    vertical-align: unset !important;
  }

  /* td1: Product (thumb + name) — col A, rows 1-2 */
  #poItemsTable tbody td:nth-child(1) {
    grid-column: 1;
    grid-row: 1 / 3;
    align-self: center;
  }

  /* td2: SKU — col A, row 3 */
  #poItemsTable tbody td:nth-child(2) {
    grid-column: 1;
    grid-row: 3;
    font-size: 10px;
    color: var(--text-muted);
  }

  /* td3: Ordered qty — col B, row 1 */
  #poItemsTable tbody td:nth-child(3) {
    grid-column: 2;
    grid-row: 1;
    text-align: right;
    font-size: 18px !important;
    font-weight: 800 !important;
    line-height: 1;
  }
  #poItemsTable tbody td:nth-child(3)::before {
    content: 'Ordered';
    display: block;
    font-size: 9px;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: .05em;
    margin-bottom: 2px;
  }

  /* td4: Received qty — col B, row 2 */
  #poItemsTable tbody td:nth-child(4) {
    grid-column: 2;
    grid-row: 2;
    text-align: right;
    font-size: 11px;
  }
  #poItemsTable tbody td:nth-child(4)::before {
    content: 'Rcvd: ';
    font-size: 10px;
    color: var(--text-muted);
  }

  /* td5: Unit cost — col B, row 3 */
  #poItemsTable tbody td:nth-child(5) {
    grid-column: 2;
    grid-row: 3;
    text-align: right;
    font-size: 10px;
    color: var(--text-muted);
  }

  /* td6: Line total — hide (grand total shown in tfoot) */
  #poItemsTable tbody td:nth-child(6) { display: none !important; }

  /* tfoot: span full width */
  #poItemsTable tfoot tr {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 14px;
    border-top: 1px solid var(--border);
  }
  #poItemsTable tfoot td { display: block !important; padding: 0 !important; border: none !important; }
  #poItemsTable tfoot td:first-child { flex: 1; }
}

/* ── 6. PO info sidebar rows: prevent overflow on wrap ──── */
.po-info-row {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  padding: 6px 0;
  border-bottom: 1px solid var(--border2);
  gap: 8px;
}
.po-info-row:last-child { border-bottom: none; }
.po-info-label {
  font-size: 12px;
  color: var(--text-muted);
  flex-shrink: 0;
}
.po-info-value {
  font-size: 12px;
  text-align: right;
  word-break: break-word;
  min-width: 0;
}
</style>

<!-- ── Page header ─────────────────────────────────────── -->
<div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-2 po-view-header">
  <div>
    <h4 style="font-family:monospace;"><?= e($po['po_number']) ?></h4>
    <p class="page-subtitle">
      <span class="badge" style="background:<?= $ss['bg'] ?>;color:<?= $ss['color'] ?>;"><?= ucfirst($po['status']) ?></span>
      &nbsp;<?= e($po['company_name'] ?? '') ?>
    </p>
  </div>
  <div class="po-view-actions">
    <?php if ($po['status'] === 'draft'): ?>
    <button onclick="changePOStatus('pending','Submit for approval?')"
            style="background:var(--amber-dim);color:var(--amber);border:1px solid rgba(245,158,11,.2);padding:5px 14px;border-radius:7px;font-size:12px;cursor:pointer;display:inline-flex;align-items:center;gap:6px;white-space:nowrap;">
      <i class="fa fa-paper-plane"></i>Submit
    </button>
    <?php endif; ?>
    <?php if ($po['status'] === 'pending'): ?>
    <button onclick="approvePO()"
            style="background:var(--cyan-dim);color:var(--cyan);border:1px solid rgba(0,212,255,.2);padding:5px 14px;border-radius:7px;font-size:12px;cursor:pointer;display:inline-flex;align-items:center;gap:6px;white-space:nowrap;">
      <i class="fa fa-check"></i>Approve
    </button>
    <?php endif; ?>
    <?php if ($po['status'] === 'approved'): ?>
    <button onclick="receivePO()"
            style="background:var(--green-dim);color:var(--green);border:1px solid rgba(16,185,129,.2);padding:5px 14px;border-radius:7px;font-size:12px;cursor:pointer;display:inline-flex;align-items:center;gap:6px;">
      <i class="fa fa-truck"></i>
      <span class="btn-receive-long">Mark as Received → Update Stock</span>
      <span class="btn-receive-short">Receive &amp; Update Stock</span>
    </button>
    <?php endif; ?>
    <?php if (in_array($po['status'], ['draft','pending'])): ?>
    <button onclick="cancelPO()"
            style="background:var(--red-dim);color:var(--red);border:1px solid rgba(239,68,68,.2);padding:5px 14px;border-radius:7px;font-size:12px;cursor:pointer;display:inline-flex;align-items:center;gap:6px;white-space:nowrap;">
      <i class="fa fa-times"></i>Cancel
    </button>
    <?php endif; ?>
    <a href="<?= url('admin/purchase-orders') ?>"
       class="btn btn-sm"
       style="background:var(--surface2);color:var(--text-dim);border:1px solid var(--border2);white-space:nowrap;">
      <i class="fa fa-arrow-left me-1"></i>Back
    </a>
  </div>
</div>

<div class="row g-3 po-view-row">

  <!-- ── Items table (col A) ─────────────────────────────── -->
  <div class="col-lg-8 po-items-col">
    <div class="card po-items-card">
      <div class="card-header">
        <span class="card-title"><i class="fa fa-list me-2" style="color:var(--green);"></i>Order Items</span>
      </div>
      <div class="po-items-inner">
        <table class="table table-sm align-middle mb-0" id="poItemsTable">
          <thead>
            <tr>
              <th>Product</th>
              <th style="width:70px;">SKU</th>
              <th style="width:80px;text-align:center;">Ordered</th>
              <th style="width:80px;text-align:center;">Received</th>
              <th style="width:110px;text-align:right;">Unit Cost</th>
              <th style="width:120px;text-align:right;">Total</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($items as $item): ?>
          <tr>
            <!-- td1: Product -->
            <td>
              <div style="display:flex;align-items:center;gap:10px;">
                <img src="<?= $item['thumbnail'] ? url('uploads/products/'.$item['thumbnail']) : asset('img/placeholder.webp') ?>"
                     style="width:34px;height:34px;object-fit:cover;border-radius:6px;flex-shrink:0;">
                <div>
                  <div style="font-size:12px;font-weight:600;"><?= e($item['product_name']) ?></div>
                  <?php if (!empty($item['variation_name'])): ?>
                  <div style="font-size:11px;color:var(--text-muted);"><?= e($item['variation_name']) ?></div>
                  <?php endif; ?>
                </div>
              </div>
            </td>
            <!-- td2: SKU -->
            <td style="font-size:11px;color:var(--text-muted);"><?= e($item['sku'] ?? '—') ?></td>
            <!-- td3: Ordered qty -->
            <td style="text-align:center;font-weight:700;font-size:13px;"><?= (int)$item['quantity'] ?></td>
            <!-- td4: Received qty -->
            <td style="text-align:center;">
              <?php if ($po['status'] === 'received'): ?>
              <span class="badge" style="background:var(--green-dim);color:var(--green);"><?= (int)$item['received_qty'] ?></span>
              <?php else: ?>
              <span style="color:var(--text-muted);">—</span>
              <?php endif; ?>
            </td>
            <!-- td5: Unit cost -->
            <td style="text-align:right;font-size:12px;">LKR <?= number_format($item['unit_price'], 2) ?></td>
            <!-- td6: Line total -->
            <td style="text-align:right;font-weight:700;font-size:13px;">LKR <?= number_format($item['total_price'], 2) ?></td>
          </tr>
          <?php endforeach; ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="5" style="text-align:right;font-weight:700;font-size:13px;padding:10px 14px;">Grand Total:</td>
              <td style="text-align:right;font-weight:700;font-size:15px;color:var(--cyan);padding:10px 14px;">
                LKR <?= number_format($po['total_amount'], 2) ?>
              </td>
            </tr>
          </tfoot>
        </table>
      </div><!-- /.po-items-inner -->
    </div><!-- /.card -->
  </div><!-- /.col-lg-8 -->

  <!-- ── PO info sidebar (col B) ────────────────────────── -->
  <div class="col-lg-4 po-info-col">
    <div class="card">
      <div class="card-header">
        <span class="card-title"><i class="fa fa-info-circle me-2" style="color:var(--cyan);"></i>PO Information</span>
      </div>
      <div class="card-body" style="display:flex;flex-direction:column;gap:0;">

        <div class="po-info-row">
          <span class="po-info-label">Status</span>
          <span class="po-info-value">
            <span class="badge" style="background:<?= $ss['bg'] ?>;color:<?= $ss['color'] ?>;"><?= ucfirst($po['status']) ?></span>
          </span>
        </div>

        <div class="po-info-row">
          <span class="po-info-label">Vendor</span>
          <strong class="po-info-value"><?= e($po['company_name'] ?? '—') ?></strong>
        </div>

        <?php if (!empty($po['vendor_email'])): ?>
        <div class="po-info-row">
          <span class="po-info-label">Contact</span>
          <span class="po-info-value"><?= e($po['vendor_email']) ?></span>
        </div>
        <?php endif; ?>

        <div class="po-info-row">
          <span class="po-info-label">Created By</span>
          <span class="po-info-value"><?= e($po['created_by_name'] ?? '—') ?></span>
        </div>

        <div class="po-info-row">
          <span class="po-info-label">Created</span>
          <span class="po-info-value"><?= date('M j, Y H:i', strtotime($po['created_at'])) ?></span>
        </div>

        <?php if ($po['expected_date']): ?>
        <div class="po-info-row">
          <span class="po-info-label">Expected</span>
          <span class="po-info-value"><?= date('M j, Y', strtotime($po['expected_date'])) ?></span>
        </div>
        <?php endif; ?>

        <?php if ($po['received_date']): ?>
        <div class="po-info-row">
          <span class="po-info-label">Received</span>
          <strong class="po-info-value" style="color:var(--green);"><?= date('M j, Y', strtotime($po['received_date'])) ?></strong>
        </div>
        <?php endif; ?>

        <?php if ($po['notes']): ?>
        <div style="padding:8px 0;border-top:1px solid var(--border2);margin-top:4px;">
          <div style="font-size:11px;color:var(--text-muted);margin-bottom:4px;">Notes</div>
          <div style="font-size:12px;color:var(--text-dim);"><?= nl2br(e($po['notes'])) ?></div>
        </div>
        <?php endif; ?>

      </div>
    </div>
  </div><!-- /.col-lg-4 -->

</div><!-- /.row -->

<?php $poId = (int)$po['id']; $extraScript = <<<JS
<script>
function changePOStatus(newStatus, confirmMsg) {
  SwalDark.fire({ title: confirmMsg, icon: 'question', showCancelButton: true, confirmButtonText: 'Yes' })
  .then(r => {
    if (!r.isConfirmed) return;
    ajaxPost('admin/purchase-orders/submit', { po_id: $poId, status: newStatus }, res => {
      if (res.success) SwalDark.fire({icon:'success',title:res.message,timer:1200,showConfirmButton:false}).then(()=>location.reload());
      else SwalDark.fire('Error', res.message, 'error');
    });
  });
}

function approvePO() {
  SwalDark.fire({ title:'Approve this PO?', icon:'question', showCancelButton:true, confirmButtonText:'Approve' })
  .then(r => {
    if (!r.isConfirmed) return;
    ajaxPost('admin/purchase-orders/approve', { po_id: $poId }, res => {
      if (res.success) SwalDark.fire({icon:'success',title:res.message,timer:1200,showConfirmButton:false}).then(()=>location.reload());
      else SwalDark.fire('Error', res.message, 'error');
    });
  });
}

function receivePO() {
  SwalDark.fire({
    title: 'Mark as Received?',
    text: 'This will automatically update stock levels for all items.',
    icon: 'info', showCancelButton: true, confirmButtonText: 'Yes, Update Stock'
  }).then(r => {
    if (!r.isConfirmed) return;
    ajaxPost('admin/purchase-orders/receive', { po_id: $poId }, res => {
      if (res.success) SwalDark.fire({icon:'success',title:'Stock Updated!',text:res.message,timer:2000,showConfirmButton:false}).then(()=>location.reload());
      else SwalDark.fire('Error', res.message, 'error');
    });
  });
}

function cancelPO() {
  SwalDark.fire({ title:'Cancel this PO?', icon:'warning', showCancelButton:true, confirmButtonText:'Cancel PO' })
  .then(r => {
    if (!r.isConfirmed) return;
    ajaxPost('admin/purchase-orders/cancel', { po_id: $poId }, res => {
      if (res.success) SwalDark.fire({icon:'success',title:res.message,timer:1200,showConfirmButton:false}).then(()=>location.reload());
      else SwalDark.fire('Error', res.message, 'error');
    });
  });
}
</script>
JS;
?>
