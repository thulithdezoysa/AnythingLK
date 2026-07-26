<?php $pageTitle = 'My Orders'; ?>

<style>
.acct-card{background:var(--bg-card,#fff);border-radius:14px;box-shadow:0 1px 6px rgba(0,0,0,.07);border:1px solid var(--border,#f0f0f0);}
/* Override Bootstrap's per-cell bg/color vars so dark mode works correctly */
.acct-table {
  --bs-table-bg:           var(--bg-card, #fff);
  --bs-table-color:        var(--text, #111);
  --bs-table-border-color: var(--border, #e9ecef);
}
.acct-table thead th {
  background: var(--bg-soft, #f8f9fc) !important;
  color: var(--text-muted, #6c757d) !important;
  border-bottom: 2px solid var(--border, #e9ecef) !important;
  font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .05em;
}
.acct-table td, .acct-table th { border-color: var(--border, #e9ecef) !important; }
.acct-table tbody tr:hover > td,
.acct-table tbody tr:hover > th {
  background: var(--bg-soft, #f8f9fc) !important;
  color: var(--text, #111);
}
.acct-divider{border-color:var(--border,#e9ecef) !important;}
.page-link{
  background:var(--bg-card,#fff) !important;
  border-color:var(--border,#dee2e6) !important;
  color:var(--text,#333) !important;
}
.page-link:hover{background:var(--bg-soft,#f8f9fc) !important;color:var(--primary,#E63946) !important;}
.page-item.active .page-link{background:var(--primary,#E63946) !important;border-color:var(--primary,#E63946) !important;color:#fff !important;}
.page-item.disabled .page-link{background:var(--bg-soft,#f8f9fc) !important;color:var(--text-muted,#aaa) !important;}
.acct-card .text-muted{color:var(--text-muted) !important;}
.acct-card .fw-semibold,.acct-card .fw-bold{color:var(--text);}
</style>

<div class="container py-4 py-lg-5">
  <div class="row g-4">

    <div class="col-lg-3">
      <?php include __DIR__ . '/_sidebar.php'; ?>
    </div>

    <div class="col-lg-9">
      <div class="acct-card overflow-hidden">
        <div class="px-4 py-3 d-flex justify-content-between align-items-center" style="border-bottom:1px solid var(--border,#e9ecef);">
          <h5 class="fw-bold mb-0">My Orders</h5>
          <span class="badge rounded-pill px-3" style="background:var(--primary,#E63946);">
            <?= number_format((int)($pagData['total'] ?? count($orders))) ?> total
          </span>
        </div>

        <?php if (empty($orders)): ?>
        <div class="p-5 text-center text-muted">
          <i class="fa fa-bag-shopping fa-3x mb-3 d-block" style="color:var(--border,#ddd);"></i>
          <h6>No orders yet</h6>
          <p class="small">When you place orders, they'll appear here.</p>
          <a href="<?= url('products') ?>" class="btn btn-primary btn-sm mt-1">Start Shopping</a>
        </div>
        <?php else: ?>
        <div class="table-responsive">
          <table class="table acct-table align-middle mb-0">
            <thead>
              <tr>
                <th class="ps-4">Order #</th>
                <th>Date</th>
                <th class="d-none d-md-table-cell">Items</th>
                <th>Total</th>
                <th class="d-none d-sm-table-cell">Payment</th>
                <th>Status</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
            <?php foreach ($orders as $o):
              $sc = ['pending'=>'warning','confirmed'=>'info','processing'=>'info',
                     'shipped'=>'primary','delivered'=>'success',
                     'cancelled'=>'danger','refunded'=>'secondary'][$o['status']] ?? 'secondary';
            ?>
            <tr>
              <td class="ps-4 fw-bold small"><?= e($o['order_number']) ?></td>
              <td class="small text-muted" style="white-space:nowrap;"><?= date('M j, Y', strtotime($o['created_at'])) ?></td>
              <td class="small d-none d-md-table-cell"><?= (int)($o['item_count'] ?? 0) ?> item<?= ($o['item_count']??0)!=1?'s':'' ?></td>
              <td class="fw-bold small" style="white-space:nowrap;">LKR <?= number_format($o['total_amount'],2) ?></td>
              <td class="d-none d-sm-table-cell">
                <?php $ps = $o['payment_status'] ?? 'unpaid'; ?>
                <span class="badge <?= $ps==='paid'?'bg-success':'bg-warning text-dark' ?>">
                  <?= ucfirst($ps) ?>
                </span>
              </td>
              <td><span class="badge bg-<?= $sc ?>"><?= ucfirst($o['status']) ?></span></td>
              <td>
                <div class="d-flex gap-1">
                  <a href="<?= url('account/order/'.$o['id']) ?>" class="btn btn-sm btn-outline-primary py-0 px-2">View</a>
                  <a href="<?= url('invoice/'.$o['id']) ?>" target="_blank"
                     class="btn btn-sm btn-outline-secondary py-0 px-2 d-none d-md-inline-flex" title="Preview Invoice">
                    <i class="fa fa-file-pdf"></i>
                  </a>
                  <a href="<?= url('invoice/'.$o['id'].'/download') ?>" target="_blank"
                     class="btn btn-sm btn-outline-success py-0 px-2 d-none d-md-inline-flex" title="Download PDF">
                    <i class="fa fa-download"></i>
                  </a>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <?php if (($pagData['total_pages'] ?? 1) > 1): ?>
        <div class="p-3" style="border-top:1px solid var(--border,#e9ecef);">
          <nav><ul class="pagination justify-content-center mb-0 pagination-sm">
            <?php if ($pagData['has_prev']): ?>
            <li class="page-item">
              <a class="page-link" href="?page=<?= $pagData['current_page']-1 ?>">‹</a>
            </li>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $pagData['total_pages']; $i++): ?>
            <li class="page-item <?= $i===$pagData['current_page']?'active':'' ?>">
              <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
            </li>
            <?php endfor; ?>
            <?php if ($pagData['has_next']): ?>
            <li class="page-item">
              <a class="page-link" href="?page=<?= $pagData['current_page']+1 ?>">›</a>
            </li>
            <?php endif; ?>
          </ul></nav>
        </div>
        <?php endif; ?>
        <?php endif; ?>
      </div>
    </div>

  </div>
</div>
