<?php /* admin/views/purchase_orders/create.php */ ?>
<style>
/* ════════════════════════════════════════════════════════════
   CREATE PURCHASE ORDER — RESPONSIVE
   ════════════════════════════════════════════════════════════ */

/* ── 1. Page header ─────────────────────────────────────── */
@media (max-width: 480px) {
  .po-create-header {
    flex-direction: column;
    align-items: flex-start !important;
  }
  .po-create-header > a { width: 100%; justify-content: center; }
}

/* ── 2. Order Details: vendor + date 2-col grid ────────────
   Stacks to 1 column on phones (< 576 px).                  */
.po-details-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 12px;
}
@media (max-width: 575px) {
  .po-details-grid { grid-template-columns: 1fr; }
}

/* ── 3. Items table scroll ──────────────────────────────────
   .card has overflow:hidden for border-radius.
   Override it on the items card only so the inner scroll
   container works. The ::before shimmer line (position:absolute
   within the card) is unaffected by overflow:visible.        */
.po-items-card   { overflow: visible !important; }

/* Scroll container with thin-bar styling                    */
.po-items-scroll {
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
  scrollbar-width: thin;
  scrollbar-color: rgba(255,255,255,.12) transparent;
  /* Re-clip bottom corners that .card used to clip           */
  border-radius: 0 0 var(--radius) var(--radius);
}
.po-items-scroll::-webkit-scrollbar       { height: 4px; }
.po-items-scroll::-webkit-scrollbar-track { background: transparent; }
.po-items-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,.12); border-radius: 4px; }

/* Table minimum width on tablets                            */
@media (min-width: 576px) {
  #itemsTable { min-width: 560px; }
}

/* ── 4. Item row: column cells on very small phones ─────────
   On narrow screens (< 480px) the 5-column row (select +
   price + qty + total + delete) is too cramped even with
   scroll.  Let each item row instead stack into a mini-card. */
@media (max-width: 479px) {
  /* Release the scroll — we stack instead                   */
  .po-items-scroll { overflow-x: visible; border-radius: 0; }
  #itemsTable      { min-width: 0 !important; }

  /* Hide header row */
  #itemsTable thead { display: none; }

  /* Each <tr> becomes a full-width block card               */
  #itemsTable tbody tr.item-row {
    display: block;
    padding: 12px 14px;
    border-bottom: 1px solid var(--border);
    position: relative;
  }
  #itemsTable tbody tr.item-row:last-child { border-bottom: none; }

  /* Strip td defaults */
  #itemsTable tbody tr.item-row td {
    display: block !important;
    padding: 0 0 8px !important;
    border: none !important;
    background: transparent !important;
  }
  #itemsTable tbody tr.item-row td:last-child { padding-bottom: 0 !important; }

  /* Product select: full width */
  #itemsTable tbody tr.item-row td:nth-child(1) { width: 100%; }

  /* Price + Qty: side by side using flex                    */
  #itemsTable tbody tr.item-row td:nth-child(2),
  #itemsTable tbody tr.item-row td:nth-child(3) {
    display: inline-block !important;
    width: calc(50% - 4px);
    padding-right: 4px !important;
  }

  /* Line total: full width, right-aligned */
  #itemsTable tbody tr.item-row td:nth-child(4) {
    display: block !important;
    text-align: right !important;
    font-size: 13px;
    font-weight: 700;
    padding-top: 4px !important;
  }

  /* Delete button: absolute top-right of card               */
  #itemsTable tbody tr.item-row td:nth-child(5) {
    position: absolute;
    top: 12px;
    right: 14px;
    padding: 0 !important;
    width: auto;
  }

  /* tfoot: simple flex row                                  */
  #itemsTable tfoot tr {
    display: flex;
    justify-content: space-between;
    padding: 10px 14px;
    border-top: 1px solid var(--border);
  }
  #itemsTable tfoot td {
    display: block !important;
    border: none !important;
    padding: 0 !important;
  }
}

/* ── 5. Summary sidebar ─────────────────────────────────────
   On desktop (≥ 992px): sticky sidebar, right of the form.
   On tablet/mobile:      full-width block below the form,
                          no sticky positioning.             */
.po-summary-card {
  position: sticky;
  top: 80px;
}
@media (max-width: 991px) {
  .po-summary-card { position: static; }
}

/* Submit button: always full-width in the summary           */
.po-submit-btn { width: 100%; }

/* ── 6. Row reorder on tablet/mobile ───────────────────────
   Show summary BELOW form on mobile (natural DOM order is
   items first → summary after, which is correct). On desktop
   they sit side by side via col-lg-*.                       */

/* ── 7. Add Product button: full-width on small phones ───── */
@media (max-width: 479px) {
  .po-add-item-btn { width: 100%; justify-content: center; }
  .po-items-card-header { flex-wrap: wrap; gap: 8px; }
}
</style>

<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2 po-create-header">
  <div>
    <h4>Create Purchase Order</h4>
    <p class="page-subtitle">Select a vendor, add products and set quantities.</p>
  </div>
  <a href="<?= url('admin/purchase-orders') ?>"
     class="btn btn-sm"
     style="background:var(--surface2);color:var(--text-dim);border:1px solid var(--border2);">
    <i class="fa fa-arrow-left me-1"></i>Back
  </a>
</div>

<form id="poForm">
  <?= CSRF::field() ?>
  <div class="row g-3">

    <!-- ── Left column: form ─────────────────────────────── -->
    <div class="col-lg-8">

      <!-- Order details card -->
      <div class="card mb-3">
        <div class="card-header">
          <span class="card-title"><i class="fa fa-file-text-o me-2" style="color:var(--cyan);"></i>Order Details</span>
        </div>
        <div class="card-body" style="display:flex;flex-direction:column;gap:14px;">

          <!-- Vendor + Date: responsive 2-col grid -->
          <div class="po-details-grid">
            <div>
              <label class="form-label">Vendor / Supplier *</label>
              <select name="vendor_id" class="form-control" required>
                <option value="">Select vendor…</option>
                <?php foreach ($vendors as $v): ?>
                <option value="<?= $v['id'] ?>"><?= e($v['company_name']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div>
              <label class="form-label">Expected Delivery Date</label>
              <input type="date" name="expected_date" class="form-control" min="<?= date('Y-m-d') ?>">
            </div>
          </div>

          <!-- Notes -->
          <div>
            <label class="form-label">Notes <small style="color:var(--text-muted);font-weight:400;">(optional)</small></label>
            <textarea name="notes" class="form-control" rows="2" placeholder="Internal notes…"></textarea>
          </div>

        </div>
      </div><!-- /.card -->

      <!-- Items card -->
      <div class="card po-items-card">
        <div class="card-header po-items-card-header" style="display:flex;justify-content:space-between;align-items:center;">
          <span class="card-title"><i class="fa fa-list me-2" style="color:var(--green);"></i>Order Items</span>
          <button type="button" id="addItem" class="po-add-item-btn"
                  style="background:var(--green-dim);color:var(--green);border:1px solid rgba(16,185,129,.2);padding:4px 12px;border-radius:6px;font-size:12px;cursor:pointer;display:inline-flex;align-items:center;gap:4px;">
            <i class="fa fa-plus"></i>Add Product
          </button>
        </div>
        <div class="card-body" style="padding:0;">
          <div class="po-items-scroll">
            <table class="table table-sm align-middle mb-0" id="itemsTable">
              <thead>
                <tr>
                  <th>Product</th>
                  <th style="width:130px;">Unit Cost (LKR)</th>
                  <th style="width:90px;">Qty</th>
                  <th style="width:110px;text-align:right;">Line Total</th>
                  <th style="width:40px;"></th>
                </tr>
              </thead>
              <tbody id="itemsBody"></tbody>
              <tfoot>
                <tr>
                  <td colspan="3" style="text-align:right;font-weight:700;font-size:13px;padding:10px 14px;">Grand Total:</td>
                  <td style="text-align:right;font-weight:700;font-size:14px;color:var(--cyan);padding:10px 14px;" id="grandTotal">LKR 0.00</td>
                  <td></td>
                </tr>
              </tfoot>
            </table>
          </div><!-- /.po-items-scroll -->
        </div>
      </div><!-- /.card -->

    </div><!-- /.col-lg-8 -->

    <!-- ── Right column: summary ─────────────────────────── -->
    <div class="col-lg-4">
      <div class="card po-summary-card">
        <div class="card-header">
          <span class="card-title"><i class="fa fa-calculator me-2" style="color:var(--amber);"></i>Summary</span>
        </div>
        <div class="card-body" style="display:flex;flex-direction:column;gap:12px;">
          <p style="font-size:12px;color:var(--text-muted);margin:0;">Add products, set quantities and unit costs.</p>
          <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid var(--border2);">
            <span style="font-size:13px;color:var(--text-dim);">Items:</span>
            <strong id="summaryItems" style="font-size:13px;">0</strong>
          </div>
          <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;">
            <span style="font-size:13px;color:var(--text-dim);">Total:</span>
            <strong id="summaryTotal" style="font-size:15px;color:var(--cyan);">LKR 0.00</strong>
          </div>
          <button type="submit" class="btn btn-primary po-submit-btn" id="submitPO" style="margin-top:4px;">
            <i class="fa fa-save me-2"></i>Create Purchase Order
          </button>
        </div>
      </div>
    </div><!-- /.col-lg-4 -->

  </div><!-- /.row -->
</form>

<!-- Item row template -->
<template id="itemRowTemplate">
  <tr class="item-row">
    <td>
      <select name="items[__IDX__][product_id]" class="form-control form-control-sm product-select" required>
        <option value="">Select product…</option>
        <?php foreach ($products as $pr): ?>
        <option value="<?= $pr['id'] ?>" data-cost="<?= (float)$pr['cost_price'] ?>">
          <?= e(Helper::truncate($pr['name'], 40)) ?> (<?= (int)$pr['stock_qty'] ?> in stock)
        </option>
        <?php endforeach; ?>
      </select>
    </td>
    <td>
      <input type="number" name="items[__IDX__][unit_price]" class="form-control form-control-sm unit-price"
             placeholder="0.00" min="0" step="0.01" required>
    </td>
    <td>
      <input type="number" name="items[__IDX__][quantity]" class="form-control form-control-sm item-qty"
             placeholder="1" min="1" required>
    </td>
    <td style="text-align:right;font-weight:700;font-size:13px;" class="item-total">LKR 0.00</td>
    <td style="text-align:center;">
      <button type="button" class="remove-item"
              style="background:var(--red-dim);color:var(--red);border:none;padding:3px 8px;border-radius:5px;cursor:pointer;font-size:12px;">
        <i class="fa fa-trash"></i>
      </button>
    </td>
  </tr>
</template>

<?php $extraScript = <<<'JS'
<script>
let itemIdx = 0;

function recalc() {
  let total = 0, count = 0;
  document.querySelectorAll('.item-row').forEach(function(row) {
    const price = parseFloat(row.querySelector('.unit-price').value) || 0;
    const qty   = parseFloat(row.querySelector('.item-qty').value)   || 0;
    const line  = price * qty;
    row.querySelector('.item-total').textContent = 'LKR ' + line.toLocaleString('en-US',{minimumFractionDigits:2});
    total += line;
    if (qty > 0) count++;
  });
  const fmt = total.toLocaleString('en-US',{minimumFractionDigits:2});
  document.getElementById('grandTotal').textContent   = 'LKR ' + fmt;
  document.getElementById('summaryTotal').textContent = 'LKR ' + fmt;
  document.getElementById('summaryItems').textContent = count;
}

document.getElementById('addItem').addEventListener('click', function() {
  const tpl = document.getElementById('itemRowTemplate').innerHTML.replace(/__IDX__/g, itemIdx++);
  const tmp = document.createElement('tbody');
  tmp.innerHTML = tpl;
  document.getElementById('itemsBody').appendChild(tmp.firstElementChild);
});

document.addEventListener('change', function(e) {
  if (!e.target.classList.contains('product-select')) return;
  const cost = parseFloat(e.target.options[e.target.selectedIndex].dataset.cost) || 0;
  const row  = e.target.closest('tr');
  if (cost > 0) row.querySelector('.unit-price').value = cost;
  recalc();
});

document.addEventListener('input', function(e) {
  if (e.target.classList.contains('unit-price') || e.target.classList.contains('item-qty')) recalc();
});

document.addEventListener('click', function(e) {
  const btn = e.target.closest('.remove-item');
  if (!btn) return;
  btn.closest('tr').remove();
  recalc();
});

$('#poForm').on('submit', function(e) {
  e.preventDefault();
  if ($('.item-row').length === 0) {
    SwalDark.fire('Validation', 'Add at least one product.', 'error'); return;
  }
  const btn = document.getElementById('submitPO');
  btn.disabled = true;
  btn.innerHTML = '<i class="fa fa-spinner fa-spin me-2"></i>Creating…';
  $.ajax({
    url: SITE_URL + '/admin/purchase-orders/store',
    type: 'POST',
    data: $(this).serialize(),
    dataType: 'json',
    success: function(res) {
      btn.disabled = false;
      btn.innerHTML = '<i class="fa fa-save me-2"></i>Create Purchase Order';
      if (res.success) {
        SwalDark.fire({icon:'success',title:res.message,timer:1500,showConfirmButton:false})
          .then(() => window.location.href = res.redirect);
      } else {
        SwalDark.fire('Error', res.message, 'error');
      }
    },
    error: function() {
      btn.disabled = false;
      btn.innerHTML = '<i class="fa fa-save me-2"></i>Create Purchase Order';
      SwalDark.fire('Error', 'Request failed.', 'error');
    }
  });
});

// Add first row on load
document.getElementById('addItem').click();
</script>
JS;
?>
