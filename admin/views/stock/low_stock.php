<?php /* admin/views/stock/low_stock.php */ $pageTitle = 'Low Stock Alerts'; ?>
<style>
/* ════════════════════════════════════════════════════════════
   LOW STOCK PAGE — RESPONSIVE
   All selectors scoped to .low-stock-* to avoid bleed.
   ════════════════════════════════════════════════════════════ */

/* ── 1. Page header ─────────────────────────────────────────
   Stack the action buttons below the title on small phones  */
.low-stock-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  flex-wrap: wrap;
  gap: 12px;
  margin-bottom: 24px;
}
.low-stock-header-actions {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
}
@media (max-width: 480px) {
  .low-stock-header-actions { width: 100%; }
  .low-stock-header-actions .btn { flex: 1; justify-content: center; }
}

/* ── 2. Out-of-stock card grid ──────────────────────────────
   The admin layout doesn't include Bootstrap 5 row-cols-*
   utilities, so we implement this with CSS grid directly.

   Phone  (< 480px) : 1 column
   Tablet (480–767) : 2 columns
   Desktop (≥ 768)  : 4 columns                             */
.oos-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 12px;
  margin-bottom: 0;
}
@media (max-width: 767px) { .oos-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 479px) { .oos-grid { grid-template-columns: 1fr; } }

.oos-card {
  background: var(--surface);
  border: 1px solid rgba(239,68,68,.3);
  border-radius: var(--radius);
  padding: 16px 14px;
  text-align: center;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 8px;
  transition: border-color .2s;
}
.oos-card:hover { border-color: rgba(239,68,68,.6); }
.oos-card .oos-name {
  font-size: 12px;
  font-weight: 600;
  color: var(--text);
  line-height: 1.4;
}
.oos-card .btn { width: 100%; justify-content: center; font-size: 12px; }

/* ── 3. Low-stock table scroll wrapper ──────────────────── */
.low-stock-table-wrap {
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
  scrollbar-width: thin;
  scrollbar-color: rgba(255,255,255,.12) transparent;
}
.low-stock-table-wrap::-webkit-scrollbar       { height: 4px; }
.low-stock-table-wrap::-webkit-scrollbar-track { background: transparent; }
.low-stock-table-wrap::-webkit-scrollbar-thumb { background: rgba(255,255,255,.12); border-radius: 4px; }

/* Min-width on tablet so columns stay readable              */
@media (min-width: 768px) {
  #lowStockTable { min-width: 600px; }
}

/* ── 4. Mobile card layout for low-stock table (< 768px) ── */
@media (max-width: 767px) {

  .low-stock-table-wrap .admin-table { overflow: visible !important; }

  #lowStockTable { min-width: 0 !important; }

  #lowStockTable thead { display: none; }

  /* 2-column card:
     Col A (1fr)  : Product name + variation + warehouse
     Col B (auto) : Qty badge + threshold                    */
  #lowStockTable tbody tr {
    display: grid;
    grid-template-columns: 1fr auto;
    grid-template-rows: auto auto auto auto;
    column-gap: 12px;
    row-gap: 3px;
    padding: 12px 14px;
    border-top: none;
    border-bottom: 1px solid var(--border);
  }
  #lowStockTable tbody tr:last-child { border-bottom: none; }
  #lowStockTable tbody tr:hover td  { background: transparent !important; }

  #lowStockTable tbody td {
    display: block !important;
    padding: 0 !important;
    border: none !important;
    background: transparent !important;
    vertical-align: unset !important;
  }

  /* td1: Product name — col A, row 1 */
  #lowStockTable tbody td:nth-child(1) {
    grid-column: 1;
    grid-row: 1;
    font-size: 13px;
    font-weight: 600;
  }

  /* td2: Variation — col A, row 2 */
  #lowStockTable tbody td:nth-child(2) {
    grid-column: 1;
    grid-row: 2;
    font-size: 11px;
    color: var(--text-muted);
  }

  /* td3: Warehouse — col A, row 3 */
  #lowStockTable tbody td:nth-child(3) {
    grid-column: 1;
    grid-row: 3;
    font-size: 11px;
    color: var(--text-dim);
  }
  #lowStockTable tbody td:nth-child(3)::before { content: '📦 '; font-size: 10px; }

  /* td4: Current qty badge — col B, row 1 */
  #lowStockTable tbody td:nth-child(4) {
    grid-column: 2;
    grid-row: 1;
    align-self: start;
    text-align: right;
  }

  /* td5: Threshold — col B, row 2 */
  #lowStockTable tbody td:nth-child(5) {
    grid-column: 2;
    grid-row: 2;
    text-align: right;
    font-size: 10px;
    color: var(--text-muted);
  }
  #lowStockTable tbody td:nth-child(5)::before { content: 'min: '; }

  /* td6: Actions — col A-B, row 4 — full-width button row   */
  #lowStockTable tbody td:nth-child(6) {
    grid-column: 1 / 3;
    grid-row: 4;
    margin-top: 8px;
    display: flex !important;
    gap: 8px;
    flex-wrap: wrap;
  }
  #lowStockTable tbody td:nth-child(6) .btn {
    flex: 1;
    justify-content: center;
    font-size: 11px;
    padding: 5px 10px;
    min-width: 100px;
  }
}

/* ── 5. Very small phones (< 380px) ────────────────────── */
@media (max-width: 379px) {
  #lowStockTable tbody tr { padding: 10px 10px; }
  .oos-grid { gap: 8px; }
}
</style>

<!-- ── Page header ─────────────────────────────────────── -->
<div class="low-stock-header">
  <h4 class="text-danger mb-0">
    <i class="fa fa-exclamation-triangle me-2"></i>Low Stock Alerts
  </h4>
  <div class="low-stock-header-actions">
    <button class="btn btn-warning btn-sm" id="btnEmailAlert">
      <i class="fa fa-envelope me-1"></i>Send Alert Email
    </button>
    <a href="<?= url('admin/stock') ?>" class="btn btn-sm"
       style="background:var(--surface2);color:var(--text-dim);border:1px solid var(--border2);">
      <i class="fa fa-arrow-left me-1"></i>Back to Stock
    </a>
  </div>
</div>

<!-- ── Out of Stock cards ──────────────────────────────── -->
<?php if (!empty($outOfStock)): ?>
<div class="mb-4">
  <h6 class="fw-bold text-danger mb-3">
    Out of Stock
    <span class="badge ms-1" style="background:var(--red-dim);color:var(--red);"><?= count($outOfStock) ?></span>
  </h6>
  <div class="oos-grid">
    <?php foreach ($outOfStock as $item): ?>
    <div class="oos-card">
      <span class="badge" style="background:var(--red-dim);color:var(--red);">OUT OF STOCK</span>
      <div class="oos-name"><?= e(Helper::truncate($item['product_name'], 32)) ?></div>
      <a href="<?= url('admin/stock?product='.$item['product_id']) ?>"
         class="btn btn-sm btn-danger mt-1">
        <i class="fa fa-plus me-1"></i>Restock Now
      </a>
    </div>
    <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>

<!-- ── Low Stock table ─────────────────────────────────── -->
<h6 class="fw-bold text-warning mb-3">
  Low Stock
  <span class="badge ms-1" style="background:var(--amber-dim);color:var(--amber);"><?= count($lowStockItems) ?></span>
</h6>

<div class="low-stock-table-wrap">
<div class="admin-table">
  <table class="table table-sm align-middle mb-0" id="lowStockTable">
    <thead>
      <tr>
        <th>Product</th>
        <th style="width:120px;">Variation</th>
        <th style="width:110px;">Warehouse</th>
        <th style="width:90px;text-align:center;">Current Qty</th>
        <th style="width:90px;text-align:center;">Threshold</th>
        <th style="width:200px;">Actions</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($lowStockItems as $item): ?>
    <tr>
      <!-- td1: Product name -->
      <td class="fw-semibold small"><?= e($item['product_name']) ?></td>
      <!-- td2: Variation -->
      <td class="small text-muted"><?= e($item['variation_name'] ?? '—') ?></td>
      <!-- td3: Warehouse -->
      <td class="small"><?= e($item['warehouse']) ?></td>
      <!-- td4: Current qty -->
      <td class="text-center">
        <span class="badge" style="background:var(--amber-dim);color:var(--amber);"><?= $item['quantity'] ?></span>
      </td>
      <!-- td5: Threshold -->
      <td class="text-muted small text-center"><?= $item['low_stock_threshold'] ?></td>
      <!-- td6: Actions -->
      <td>
        <div style="display:flex;gap:6px;flex-wrap:wrap;">
          <a href="<?= url('admin/stock?product='.$item['product_id']) ?>"
             class="btn btn-sm btn-outline-warning py-0">
            <i class="fa fa-cubes me-1"></i>Manage Stock
          </a>
          <a href="<?= url('admin/purchase-orders/create') ?>"
             class="btn btn-sm btn-outline-primary py-0">
            <i class="fa fa-file-text me-1"></i>Create PO
          </a>
        </div>
      </td>
    </tr>
    <?php endforeach; ?>
    <?php if (empty($lowStockItems)): ?>
    <tr>
      <td colspan="6" class="text-center py-5" style="color:var(--text-muted);">All products are adequately stocked.</td>
    </tr>
    <?php endif; ?>
    </tbody>
  </table>
</div><!-- /.admin-table -->
</div><!-- /.low-stock-table-wrap -->

<?php $extraScript = <<<'JS'
<script>
document.getElementById('btnEmailAlert').addEventListener('click', function() {
    this.disabled = true;
    this.textContent = 'Sending...';
    const btn = this;
    ajaxPost('admin/stock/alert', {}, function(res) {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa fa-envelope me-1"></i>Send Alert Email';
        Swal.fire({ icon: res.success ? 'success' : 'error', title: res.message, timer: 2500, showConfirmButton: false });
    });
});
</script>
JS;
?>
