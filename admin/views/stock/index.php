<?php /* admin/views/stock/index.php */ ?>
<style>
/* ════════════════════════════════════════════════════════════
   STOCK INDEX — RESPONSIVE
   All selectors scoped to avoid bleed into other pages.
   ════════════════════════════════════════════════════════════ */

/* ── 1. Scroll wrapper for the stock table ──────────────────
   .admin-table globally has overflow:hidden for border-radius.
   A dedicated wrapper handles horizontal scroll instead.     */
.stock-table-wrap {
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
  scrollbar-width: thin;
  scrollbar-color: rgba(255,255,255,.12) transparent;
}
.stock-table-wrap::-webkit-scrollbar       { height: 4px; }
.stock-table-wrap::-webkit-scrollbar-track { background: transparent; }
.stock-table-wrap::-webkit-scrollbar-thumb { background: rgba(255,255,255,.12); border-radius: 4px; }

/* Enforce minimum column width so headers never collapse     */
@media (min-width: 768px) {
  #stockTable { min-width: 780px; }
}

/* ── 2. Quick-action two-card grid ─────────────────────────
   2 columns on ≥ 640px, 1 column on smaller phones          */
.stock-action-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px;
  margin-bottom: 20px;
}
@media (max-width: 639px) {
  .stock-action-grid { grid-template-columns: 1fr; }
}

/* ── 3. Form rows inside the quick-action cards ─────────────
   qty-input + notes-input + submit button:
     ≥ 540px → flex row (current behaviour)
     < 540px → 2-row stack: [qty | notes] then [button]     */
.stock-form-row {
  display: flex;
  gap: 8px;
  align-items: center;
}
.stock-form-row .sfr-qty   { width: 90px; flex-shrink: 0; }
.stock-form-row .sfr-notes { flex: 1; min-width: 0; }
.stock-form-row .sfr-btn   { white-space: nowrap; flex-shrink: 0; }

@media (max-width: 539px) {
  .stock-form-row {
    flex-wrap: wrap;
  }
  .stock-form-row .sfr-qty   { width: calc(35% - 4px); }
  .stock-form-row .sfr-notes { width: calc(65% - 4px); flex: none; }
  .stock-form-row .sfr-btn   { width: 100%; justify-content: center; }
}

/* ── 4. Mobile card layout for stock table (< 768 px) ──────  */
@media (max-width: 767px) {

  /* Let cards breathe — release the clip from .admin-table  */
  .stock-table-wrap .admin-table { overflow: visible !important; }

  /* Zero out the enforced min-width                         */
  #stockTable { min-width: 0 !important; }

  /* Hide the thead column row                               */
  #stockTable thead { display: none; }

  /* Each row = 3-column grid card
     Col A (1fr)   : Product thumb + name
     Col B (auto)  : Status badge + numeric stats
     Col C (auto)  : Action buttons                          */
  #stockTable tbody tr {
    display: grid;
    grid-template-columns: 1fr auto auto;
    grid-template-rows: auto auto auto;
    column-gap: 10px;
    row-gap: 4px;
    padding: 12px 14px;
    border-top: none;
    border-bottom: 1px solid var(--border);
  }
  #stockTable tbody tr:last-child { border-bottom: none; }
  #stockTable tbody tr:hover td   { background: transparent !important; }

  /* Strip all td defaults */
  #stockTable tbody td {
    display: block !important;
    padding: 0 !important;
    border: none !important;
    background: transparent !important;
    vertical-align: unset !important;
  }

  /* td1: Product (thumb + name) — col A, rows 1-2          */
  #stockTable tbody td:nth-child(1) {
    grid-column: 1;
    grid-row: 1 / 3;
    align-self: center;
  }

  /* td2: SKU — col A, row 3 */
  #stockTable tbody td:nth-child(2) {
    grid-column: 1;
    grid-row: 3;
    font-size: 10px;
    color: var(--text-muted);
  }

  /* td3 (Variation) + td4 (Warehouse) — hide on mobile, info available in detail */
  #stockTable tbody td:nth-child(3),
  #stockTable tbody td:nth-child(4) { display: none !important; }

  /* td5: On Hand qty — col B, row 1 */
  #stockTable tbody td:nth-child(5) {
    grid-column: 2;
    grid-row: 1;
    text-align: right;
    font-size: 18px !important;
    font-weight: 800 !important;
    line-height: 1;
  }
  #stockTable tbody td:nth-child(5)::before {
    content: 'On Hand';
    display: block;
    font-size: 9px;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: .05em;
    margin-bottom: 2px;
  }

  /* td6 + td7 (Reserved + Available) — col B, row 2 as small text */
  #stockTable tbody td:nth-child(6) {
    grid-column: 2;
    grid-row: 2;
    text-align: right;
    font-size: 10px;
    color: var(--text-muted);
  }
  #stockTable tbody td:nth-child(6)::before { content: 'Res: '; }

  #stockTable tbody td:nth-child(7) {
    grid-column: 2;
    grid-row: 3;
    text-align: right;
    font-size: 11px;
    font-weight: 600;
  }
  #stockTable tbody td:nth-child(7)::before {
    content: 'Avail: ';
    font-weight: 400;
    color: var(--text-muted);
    font-size: 10px;
  }

  /* td8 (Threshold) — hide (low priority on mobile)         */
  #stockTable tbody td:nth-child(8) { display: none !important; }

  /* td9: Status badge — col C, row 1                        */
  #stockTable tbody td:nth-child(9) {
    grid-column: 3;
    grid-row: 1;
    align-self: start;
  }

  /* td10: Actions — col C, rows 2-3                         */
  #stockTable tbody td:nth-child(10) {
    grid-column: 3;
    grid-row: 2 / 4;
    align-self: center;
    text-align: right;
  }
  #stockTable tbody td:nth-child(10) > div {
    flex-direction: column !important;
    gap: 4px !important;
  }

  /* Empty state row: bypass the grid                        */
  #stockTable tbody tr.stock-empty-row {
    display: block !important;
    border-bottom: none;
  }
  #stockTable tbody tr.stock-empty-row td {
    display: block !important;
    padding: 40px 14px !important;
    text-align: center !important;
    color: var(--text-muted);
  }
}

/* ── 5. Very small phones (< 380 px): tighten              */
@media (max-width: 379px) {
  #stockTable tbody tr {
    grid-template-columns: 1fr auto 52px;
    column-gap: 7px;
    padding: 10px 10px;
  }
  .stock-action-grid { gap: 12px; }
}

/* ── 6. Content-area padding relief on phones              */
@media (max-width: 575px) {
  /* Filter bar: warehouse select goes full-width             */
  .afb-select { min-width: 100%; }
}
</style>

<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
  <div>
    <h4>Stock Management <span class="badge ms-1" style="background:var(--surface2);color:var(--text-muted);font-size:12px;font-weight:600;border-radius:8px;padding:3px 9px;"><?= number_format($total) ?></span></h4>
    <p class="page-subtitle">Monitor inventory levels, add stock and adjust quantities.</p>
  </div>
</div>

<?php
ob_start(); ?>
<div class="afb-select">
  <select name="warehouse" class="form-select form-select-sm afb-input">
    <option value="Main" <?= $wh === 'Main' ? 'selected' : '' ?>>Main Warehouse</option>
    <?php foreach ($warehouses as $_wh): if ($_wh['warehouse'] === 'Main') continue; ?>
    <option value="<?= e($_wh['warehouse']) ?>" <?= $wh === $_wh['warehouse'] ? 'selected' : '' ?>><?= e($_wh['warehouse']) ?></option>
    <?php endforeach; ?>
  </select>
</div>
<?php
$_whHtml = ob_get_clean();

$searchFields = [['name'=>'search','value'=>$search,'placeholder'=>'Search name or SKU…']];
$filterFields = [];
$extraButtons = $_whHtml
  . '<a href="' . url('admin/stock/low-stock') . '" class="btn btn-sm" style="background:var(--red-dim);color:var(--red);border:1px solid rgba(239,68,68,.2);"><i class="fa fa-exclamation-triangle me-1"></i>Low Stock</a>'
  . '<a href="' . url('admin/stock/movements') . '" class="btn btn-sm" style="background:var(--surface2);color:var(--text-dim);border:1px solid var(--border2);"><i class="fa fa-history me-1"></i>History</a>';
$itemLabel = 'stock entries';
include __DIR__ . '/../layouts/_pagination.php';
?>

<!-- ── Quick actions ─────────────────────────────────────── -->
<div class="stock-action-grid">

  <div class="card">
    <div class="card-header">
      <span class="card-title"><i class="fa fa-plus-circle me-2" style="color:var(--green);"></i>Quick Stock In</span>
    </div>
    <div class="card-body">
      <form id="stockInForm" style="display:flex;flex-direction:column;gap:8px;">
        <select name="product_id" class="form-control form-control-sm" required>
          <option value="">Select product…</option>
          <?php foreach ($products as $pr): ?>
          <option value="<?= $pr['id'] ?>"><?= e(Helper::truncate($pr['name'], 45)) ?></option>
          <?php endforeach; ?>
        </select>
        <div class="stock-form-row">
          <input type="number" name="quantity" class="form-control form-control-sm sfr-qty"
                 placeholder="Qty" min="1" required>
          <input type="text" name="notes" class="form-control form-control-sm sfr-notes"
                 placeholder="Notes (optional)">
          <button class="btn btn-sm sfr-btn"
                  style="background:var(--green-dim);color:var(--green);border:1px solid rgba(16,185,129,.2);">
            <i class="fa fa-plus me-1"></i>Add Stock
          </button>
        </div>
      </form>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <span class="card-title"><i class="fa fa-edit me-2" style="color:var(--amber);"></i>Adjust Stock</span>
    </div>
    <div class="card-body">
      <form id="adjustForm" style="display:flex;flex-direction:column;gap:8px;">
        <select name="product_id" class="form-control form-control-sm" required>
          <option value="">Select product…</option>
          <?php foreach ($products as $pr): ?>
          <option value="<?= $pr['id'] ?>"><?= e(Helper::truncate($pr['name'], 45)) ?></option>
          <?php endforeach; ?>
        </select>
        <div class="stock-form-row">
          <input type="number" name="quantity" class="form-control form-control-sm sfr-qty"
                 placeholder="New qty" min="0" required>
          <input type="text" name="notes" class="form-control form-control-sm sfr-notes"
                 placeholder="Reason…">
          <button class="btn btn-sm sfr-btn"
                  style="background:var(--amber-dim);color:var(--amber);border:1px solid rgba(245,158,11,.2);">
            <i class="fa fa-sliders me-1"></i>Adjust
          </button>
        </div>
      </form>
    </div>
  </div>

</div>

<!-- ── Stock table ───────────────────────────────────────── -->
<div class="stock-table-wrap">
<div class="admin-table">
  <table class="table table-sm align-middle mb-0" id="stockTable">
    <thead>
      <tr>
        <th>Product</th>
        <th style="width:100px;">SKU</th>
        <th style="width:110px;">Variation</th>
        <th style="width:90px;">Warehouse</th>
        <th style="width:75px;text-align:right;">On Hand</th>
        <th style="width:75px;text-align:right;">Reserved</th>
        <th style="width:75px;text-align:right;">Available</th>
        <th style="width:75px;text-align:right;">Threshold</th>
        <th style="width:100px;">Status</th>
        <th style="width:80px;text-align:center;">Actions</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($stock as $s):
      $available = (int)$s['quantity'] - (int)$s['reserved_qty'];
      $isOut     = $s['quantity'] == 0;
      $isLow     = !$isOut && $s['quantity'] <= $s['low_stock_threshold'];
    ?>
    <tr>
      <!-- td1: Product thumb + name -->
      <td>
        <div style="display:flex;align-items:center;gap:10px;">
          <img src="<?= $s['thumbnail'] ? url('uploads/products/'.$s['thumbnail']) : asset('img/placeholder.webp') ?>"
               style="width:32px;height:32px;object-fit:cover;border-radius:6px;flex-shrink:0;">
          <a href="<?= url('admin/products/edit/'.$s['pid']) ?>"
             style="font-size:12px;font-weight:600;color:var(--text);text-decoration:none;"
             class="line-clamp-1">
            <?= e(Helper::truncate($s['product_name'], 38)) ?>
          </a>
        </div>
      </td>
      <!-- td2: SKU -->
      <td style="font-size:11px;color:var(--text-muted);"><?= e($s['sku'] ?? '—') ?></td>
      <!-- td3: Variation -->
      <td style="font-size:12px;color:var(--text-dim);"><?= e($s['variation_name'] ?? '—') ?></td>
      <!-- td4: Warehouse -->
      <td style="font-size:12px;color:var(--text-dim);"><?= e($s['warehouse']) ?></td>
      <!-- td5: On Hand -->
      <td style="text-align:right;font-weight:700;font-size:13px;color:<?= $isOut ? 'var(--red)' : ($isLow ? 'var(--amber)' : 'var(--green)') ?>;">
        <?= (int)$s['quantity'] ?>
      </td>
      <!-- td6: Reserved -->
      <td style="text-align:right;font-size:12px;color:var(--text-muted);"><?= (int)$s['reserved_qty'] ?></td>
      <!-- td7: Available -->
      <td style="text-align:right;font-weight:600;font-size:13px;"><?= $available ?></td>
      <!-- td8: Threshold -->
      <td style="text-align:right;font-size:12px;color:var(--text-muted);"><?= (int)$s['low_stock_threshold'] ?></td>
      <!-- td9: Status badge -->
      <td>
        <?php if ($isOut): ?>
        <span class="badge" style="background:var(--red-dim);color:var(--red);">Out of Stock</span>
        <?php elseif ($isLow): ?>
        <span class="badge" style="background:var(--amber-dim);color:var(--amber);">Low Stock</span>
        <?php else: ?>
        <span class="badge" style="background:var(--green-dim);color:var(--green);">In Stock</span>
        <?php endif; ?>
      </td>
      <!-- td10: Actions -->
      <td style="text-align:center;">
        <div style="display:inline-flex;gap:4px;">
          <button class="quick-stock-in"
                  data-pid="<?= $s['pid'] ?>" data-vid="<?= $s['variation_id'] ?? '' ?>"
                  data-name="<?= e(addslashes($s['product_name'])) ?>"
                  title="Stock In"
                  style="background:var(--green-dim);color:var(--green);border:none;padding:3px 9px;border-radius:5px;font-size:13px;cursor:pointer;font-weight:700;">+</button>
          <button class="view-history" data-pid="<?= $s['pid'] ?>" title="Movement history"
                  style="background:var(--surface2);color:var(--text-dim);border:none;padding:3px 8px;border-radius:5px;font-size:11px;cursor:pointer;">
            <i class="fa fa-history"></i>
          </button>
        </div>
      </td>
    </tr>
    <?php endforeach; ?>
    <?php if (empty($stock)): ?>
    <tr class="stock-empty-row">
      <td colspan="10" class="text-center py-5" style="color:var(--text-muted);">No stock records found.</td>
    </tr>
    <?php endif; ?>
    </tbody>
  </table>
</div><!-- /.admin-table -->
</div><!-- /.stock-table-wrap -->

<?php $itemLabel = 'stock entries'; include __DIR__ . '/../layouts/_pagnav.php'; ?>

<?php $extraScript = <<<'JS'
<script>
document.getElementById('stockInForm').addEventListener('submit', function(e) {
  e.preventDefault();
  const data = Object.fromEntries(new FormData(this));
  ajaxPost('admin/stock/stock-in', data, res => {
    if (res.success) Swal.fire({icon:'success',title:res.message,timer:1400,showConfirmButton:false}).then(()=>location.reload());
    else Swal.fire('Error', res.message, 'error');
  });
});

document.getElementById('adjustForm').addEventListener('submit', function(e) {
  e.preventDefault();
  const data = Object.fromEntries(new FormData(this));
  Swal.fire({ title:'Adjust stock?', text:`Set stock to ${data.quantity} units?`,
    icon:'warning', showCancelButton:true, confirmButtonText:'Adjust' })
  .then(r => {
    if (!r.isConfirmed) return;
    ajaxPost('admin/stock/adjust', data, res => {
      if (res.success) Swal.fire({icon:'success',title:res.message,timer:1400,showConfirmButton:false}).then(()=>location.reload());
      else Swal.fire('Error', res.message, 'error');
    });
  });
});

document.addEventListener('click', function(e) {
  const btn = e.target.closest('.quick-stock-in');
  if (btn) {
    const pid  = btn.dataset.pid;
    const vid  = btn.dataset.vid;
    const name = btn.dataset.name;
    Swal.fire({
      title: `Stock In: ${name}`,
      html: `<input type="number" id="sqty" class="swal2-input" placeholder="Quantity" min="1">
             <input type="text"   id="snotes" class="swal2-input" placeholder="Notes (optional)">`,
      confirmButtonText: 'Add Stock', showCancelButton: true,
      preConfirm: () => {
        const qty = parseInt(document.getElementById('sqty').value);
        if (!qty || qty < 1) { Swal.showValidationMessage('Enter a valid quantity'); return false; }
        return { qty, notes: document.getElementById('snotes').value };
      }
    }).then(r => {
      if (!r.isConfirmed) return;
      ajaxPost('admin/stock/stock-in', { product_id:pid, variation_id:vid, quantity:r.value.qty, notes:r.value.notes }, res => {
        if (res.success) Swal.fire({icon:'success',title:res.message,timer:1200,showConfirmButton:false}).then(()=>location.reload());
        else Swal.fire('Error', res.message, 'error');
      });
    });
    return;
  }
  const hbtn = e.target.closest('.view-history');
  if (hbtn) window.location.href = SITE_URL + '/admin/stock/movements?product_id=' + hbtn.dataset.pid;
});
</script>
JS;
?>
