<?php /* admin/views/payments/transactions.php */
$statusStyles = [
  'pending'  => ['bg'=>'var(--amber-dim)', 'color'=>'var(--amber)'],
  'success'  => ['bg'=>'var(--green-dim)', 'color'=>'var(--green)'],
  'failed'   => ['bg'=>'var(--red-dim)',   'color'=>'var(--red)'],
  'refunded' => ['bg'=>'var(--surface2)',  'color'=>'var(--text-muted)'],
];
?>

<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
  <div>
    <h4>Payment Transactions</h4>
    <p class="page-subtitle"><?= number_format($total) ?> transaction<?= $total!==1?'s':'' ?> found</p>
  </div>
  <a href="<?= url('admin/payments') ?>"
     style="background:var(--surface2);color:var(--text-dim);border:1px solid var(--border2);padding:6px 14px;border-radius:8px;font-size:13px;text-decoration:none;">
    <i class="fa fa-arrow-left me-1"></i>Back to Methods
  </a>
</div>

<!-- Filters -->
<div class="card mb-3">
  <div class="card-body py-3">
    <form method="GET" action="" class="d-flex gap-2 flex-wrap align-items-end">
      <div>
        <label class="form-label mb-1" style="font-size:12px;font-weight:600;">Status</label>
        <select name="status" class="form-control form-control-sm" style="min-width:130px;" onchange="this.form.submit()">
          <option value="">All Statuses</option>
          <?php foreach (['pending','success','failed','refunded'] as $s): ?>
          <option value="<?= $s ?>" <?= $status===$s?'selected':'' ?>><?= ucfirst($s) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div>
        <label class="form-label mb-1" style="font-size:12px;font-weight:600;">Gateway</label>
        <select name="gateway" class="form-control form-control-sm" style="min-width:130px;" onchange="this.form.submit()">
          <option value="">All Gateways</option>
          <?php foreach ($gateways as $g): ?>
          <option value="<?= e($g['gateway']) ?>" <?= $gateway===$g['gateway']?'selected':'' ?>><?= ucfirst($g['gateway']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <?php if ($status || $gateway): ?>
      <a href="<?= url('admin/payments/transactions') ?>" class="btn btn-sm btn-secondary" style="align-self:flex-end;">
        <i class="fa fa-times me-1"></i>Clear
      </a>
      <?php endif; ?>
    </form>
  </div>
</div>

<!-- Table -->
<div class="card">
  <div class="card-body p-0">
    <div class="admin-table">
      <table class="table table-sm align-middle mb-0" style="min-width:700px;">
        <thead>
          <tr>
            <th style="width:50px;">#</th>
            <th>Order</th>
            <th>Customer</th>
            <th>Gateway</th>
            <th>Transaction ID</th>
            <th style="text-align:right;">Amount</th>
            <th style="text-align:center;width:110px;">Status</th>
            <th style="width:80px;">Date</th>
            <th style="width:60px;"></th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($txns)): ?>
          <tr><td colspan="9" class="text-center py-5" style="color:var(--text-muted);">
            <i class="fa fa-credit-card fa-2x mb-2 d-block"></i>No transactions found.
          </td></tr>
          <?php else: ?>
          <?php foreach ($txns as $t):
            $ss = $statusStyles[$t['status']] ?? $statusStyles['pending'];
          ?>
          <tr>
            <td style="color:var(--text-muted);font-size:12px;"><?= $t['id'] ?></td>
            <td>
              <?php if ($t['order_number']): ?>
              <a href="<?= url('admin/orders/view/'.$t['order_id']) ?>" style="color:var(--cyan);font-size:13px;font-family:monospace;"><?= e($t['order_number']) ?></a>
              <?php else: ?>
              <span style="color:var(--text-muted);font-size:12px;">—</span>
              <?php endif; ?>
            </td>
            <td style="font-size:13px;"><?= $t['shipping_name'] ? e($t['shipping_name']) : '—' ?></td>
            <td>
              <span style="background:var(--surface2);color:var(--text-dim);padding:2px 8px;border-radius:20px;font-size:11px;font-weight:600;text-transform:uppercase;"><?= e($t['gateway']) ?></span>
            </td>
            <td style="font-size:12px;font-family:monospace;color:var(--text-dim);max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="<?= e($t['transaction_id'] ?? '') ?>">
              <?= $t['transaction_id'] ? e($t['transaction_id']) : '—' ?>
            </td>
            <td style="text-align:right;font-weight:600;font-size:13px;">
              LKR <?= number_format((float)$t['amount'], 2) ?>
            </td>
            <td style="text-align:center;">
              <span class="txn-status-badge" style="background:<?= $ss['bg'] ?>;color:<?= $ss['color'] ?>;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;">
                <?= ucfirst($t['status']) ?>
              </span>
            </td>
            <td style="font-size:11px;color:var(--text-muted);">
              <?= date('M j', strtotime($t['created_at'])) ?>
            </td>
            <td>
              <button class="btn btn-sm txn-edit"
                data-id="<?= $t['id'] ?>"
                data-status="<?= e($t['status']) ?>"
                data-order="<?= e($t['order_number'] ?? '') ?>"
                style="background:var(--cyan-dim);color:var(--cyan);border:none;padding:3px 8px;font-size:12px;">
                <i class="fa fa-pencil"></i>
              </button>
            </td>
          </tr>
          <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Pagination -->
<?php
$_totalPages  = (int)($pagData['total_pages']  ?? 0);
$_currentPage = (int)($pagData['current_page'] ?? 1);
if ($_totalPages > 1):
?>
<nav class="mt-3 d-flex justify-content-center gap-1 flex-wrap">
  <?php for ($i = 1; $i <= $_totalPages; $i++):
    $active = $i === $_currentPage;
    $qs = http_build_query(array_merge($_GET, ['page'=>$i]));
  ?>
  <a href="?<?= $qs ?>"
     style="display:inline-flex;align-items:center;justify-content:center;min-width:32px;height:32px;padding:0 8px;border-radius:6px;font-size:13px;text-decoration:none;
            <?= $active ? 'background:var(--cyan);color:#fff;font-weight:700;' : 'background:var(--surface2);color:var(--text-dim);' ?>">
    <?= $i ?>
  </a>
  <?php endfor; ?>
</nav>
<?php endif; ?>

<!-- ═══ Edit Transaction Status Modal ═══ -->
<div class="modal fade" id="txnModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width:360px;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Update Transaction Status</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="txnForm">
        <?= CSRF::field() ?>
        <input type="hidden" name="id" id="txnId">
        <div class="modal-body">
          <p style="font-size:13px;color:var(--text-muted);margin-bottom:16px;">
            Order: <strong id="txnOrderNum" style="color:var(--text-dim);font-family:monospace;"></strong>
          </p>
          <label class="form-label fw-semibold">Status</label>
          <select name="status" id="txnStatus" class="form-control">
            <option value="pending">Pending</option>
            <option value="success">Success</option>
            <option value="failed">Failed</option>
            <option value="refunded">Refunded</option>
          </select>
          <p class="form-text mt-2">Changing to Success/Failed will also update the order's payment status.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" id="txnSubmitBtn" class="btn btn-primary"><i class="fa fa-save me-1"></i>Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php $extraScript = <<<'JS'
<script>
// ── Open txn edit modal ───────────────────────────────────────
$(document).on('click', '.txn-edit', function() {
  var btn = $(this);
  $('#txnId').val(btn.data('id'));
  $('#txnStatus').val(btn.data('status'));
  $('#txnOrderNum').text(btn.data('order') || '—');
  new bootstrap.Modal(document.getElementById('txnModal')).show();
});

// ── Txn form submit ───────────────────────────────────────────
$('#txnForm').on('submit', function(e) {
  e.preventDefault();
  var btn = $('#txnSubmitBtn');
  btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i>Saving…');
  $.ajax({
    url: SITE_URL + '/admin/payments/update-txn', type: 'POST',
    data: $(this).serialize(), dataType: 'json',
    success: function(res) {
      btn.prop('disabled', false).html('<i class="fa fa-save me-1"></i>Save');
      if (res.success) {
        SwalDark.fire({ icon:'success', title: res.message, timer:1200, showConfirmButton:false })
          .then(function() { location.reload(); });
      } else {
        SwalDark.fire('Error', res.message, 'error');
      }
    },
    error: function() {
      btn.prop('disabled', false).html('<i class="fa fa-save me-1"></i>Save');
      SwalDark.fire('Error', 'Request failed.', 'error');
    }
  });
});
</script>
JS;
?>
