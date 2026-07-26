<?php /* admin/views/newsletter/index.php */ ?>
<style>
/* ══ NEWSLETTER — RESPONSIVE ══════════════════════════════════════
   Scoped to .nl-* / #nlTable to avoid bleed.                   */

/* ── 1. Unsub button helper (CSS class, not inline) ──────────── */
.nl-unsub-btn {
    background: var(--red-dim); color: var(--red);
    border: none; font-size: 11px; padding: 4px 10px;
    border-radius: var(--radius-sm); cursor: pointer;
    transition: var(--transition); white-space: nowrap;
    display: inline-flex; align-items: center; justify-content: center;
    min-height: 28px;
}
.nl-unsub-btn:hover { background: var(--red); color: #fff; }

/* ── 2. Table scroll wrapper (tablet / desktop) ───────────────── */
.nl-table-wrap {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: thin;
    scrollbar-color: rgba(255,255,255,.12) transparent;
}
.nl-table-wrap::-webkit-scrollbar       { height: 4px; }
.nl-table-wrap::-webkit-scrollbar-track { background: transparent; }
.nl-table-wrap::-webkit-scrollbar-thumb { background: rgba(255,255,255,.14); border-radius: 4px; }
@media (min-width: 576px) {
    #nlTable { min-width: 480px; }
}

/* ══ MOBILE CARD LAYOUT (≤ 575px) ════════════════════════════════
   5 DOM columns:
     (1) #   (2) Email   (3) Subscribed At   (4) IP   (5) Action

   Card grid — 2 columns: 1fr auto
     Row 1 : Email (A)          | Action (B, spans rows 1-3)
     Row 2 : Subscribed At (A)
     Row 3 : IP address (A)
     (1) # hidden on mobile (not needed at this density)          */
@media (max-width: 575px) {
    .nl-table-wrap { overflow-x: visible; }
    .nl-table-wrap .admin-table { overflow: visible !important; }

    #nlTable thead { display: none; }
    #nlTable        { min-width: unset; }

    #nlTable tbody tr {
        display: grid;
        grid-template-columns: 1fr auto;
        grid-template-rows: auto auto auto;
        column-gap: 12px;
        row-gap: 3px;
        padding: 12px 16px;
        border-bottom: 1px solid var(--border);
        align-items: center;
    }
    #nlTable tbody tr:last-child { border-bottom: none; }

    #nlTable tbody td {
        display: block !important;
        padding: 0 !important;
        border: none !important;
        background: transparent !important;
        vertical-align: unset !important;
    }

    /* ── Cell placement ─────────────────────────────────────── */

    /* (1) Row # — hide on mobile */
    #nlTable tbody td:nth-child(1) { display: none !important; }

    /* (2) Email — A1 */
    #nlTable tbody td:nth-child(2) {
        grid-column: 1; grid-row: 1;
        font-size: 13px !important; font-weight: 500; align-self: center;
        overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
    }

    /* (3) Subscribed At — A2 */
    #nlTable tbody td:nth-child(3) {
        grid-column: 1; grid-row: 2;
        font-size: 10px !important; color: var(--text-muted); align-self: center;
    }
    #nlTable tbody td:nth-child(3)::before {
        content: 'Subscribed: '; opacity: .7;
    }

    /* (4) IP — A3 */
    #nlTable tbody td:nth-child(4) {
        grid-column: 1; grid-row: 3;
        font-size: 10px !important; color: var(--text-muted); align-self: center;
    }
    #nlTable tbody td:nth-child(4)::before {
        content: 'IP: '; opacity: .7;
    }

    /* (5) Action — B, spans all three rows */
    #nlTable tbody td:nth-child(5) {
        grid-column: 2; grid-row: 1 / 4;
        text-align: right; align-self: center;
    }

    /* Bigger touch target for unsub button on mobile */
    .nl-unsub-btn {
        min-height: 34px; padding: 5px 12px; font-size: 12px;
    }
}

/* ── Very small phones (< 380px) ─────────────────────────────── */
@media (max-width: 379px) {
    #nlTable tbody tr { padding: 10px 12px; column-gap: 8px; }
    .nl-unsub-btn { padding: 5px 8px; font-size: 11px; }
}
</style>

<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
  <div>
    <h4>Newsletter Subscribers <span class="badge ms-1" style="background:var(--surface2);color:var(--text-muted);font-size:12px;border-radius:8px;padding:3px 9px;"><?= number_format($total) ?></span></h4>
    <p class="page-subtitle">
      <span style="color:var(--green);"><?= number_format((int)($counts['active']??0)) ?> active</span>
      &nbsp;·&nbsp;
      <span style="color:var(--text-muted);"><?= number_format((int)($counts['inactive']??0)) ?> unsubscribed</span>
    </p>
  </div>
</div>

<?php
$searchFields = [['name'=>'search','value'=>$search,'placeholder'=>'Search email…']];
$filterFields = [['name'=>'status','value'=>$status,'options'=>['active'=>'Active','unsubscribed'=>'Unsubscribed']]];
$extraButtons = '<a href="' . url('admin/newsletter/export') . '" class="btn btn-sm" style="background:var(--green);color:#fff;white-space:nowrap;"><i class="fa fa-download me-1"></i>Export CSV</a>';
include __DIR__ . '/../layouts/_pagination.php';
?>

<?php if (empty($subs)): ?>
<div class="card"><div class="card-body text-center py-5" style="color:var(--text-muted);">
  <i class="fa fa-envelope fa-3x mb-3 d-block opacity-25"></i>
  <p>No <?= $status ?> subscribers yet.</p>
</div></div>
<?php else: ?>
<div class="card">
  <div class="card-body p-0">
    <div class="nl-table-wrap">
    <div class="admin-table">
      <table class="table table-sm align-middle mb-0" id="nlTable">
        <thead>
          <tr>
            <th style="width:40px;">#</th>
            <th>Email</th>
            <th style="width:160px;">Subscribed At</th>
            <th style="width:120px;">IP</th>
            <th style="text-align:center;width:80px;">Action</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($subs as $i => $s): ?>
        <tr id="sub-<?= $s['id'] ?>">
          <td style="font-size:12px;color:var(--text-muted);"><?= ($pagData['offset'] ?? 0) + $i + 1 ?></td>
          <td style="font-size:13px;font-weight:500;"><?= e($s['email']) ?></td>
          <td style="font-size:12px;color:var(--text-muted);"><?= date('M j, Y g:i A', strtotime($s['subscribed_at'])) ?></td>
          <td style="font-size:11px;color:var(--text-muted);"><?= e($s['ip_address'] ?? '—') ?></td>
          <td style="text-align:center;">
            <?php if ($s['status'] === 'active'): ?>
            <button class="nl-unsub-btn unsub-btn" data-id="<?= $s['id'] ?>">Unsub</button>
            <?php else: ?>
            <span style="font-size:11px;color:var(--text-muted);">Removed</span>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    </div>
  </div>
</div>
<?php $itemLabel = 'subscribers'; include __DIR__ . '/../layouts/_pagnav.php'; ?>
<?php endif; ?>

<?php $extraScript = <<<'JS'
<script>
$(document).on('click', '.unsub-btn', function() {
  var id  = $(this).data('id');
  var row = $(this).closest('tr');
  SwalDark.fire({
    title: 'Unsubscribe?',
    text: 'This will mark the subscriber as unsubscribed.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes, unsubscribe',
  }).then(function(res) {
    if (!res.isConfirmed) return;
    ajaxPost('admin/newsletter/delete', { id: id }, function(r) {
      if (r.success) {
        row.find('.nl-unsub-btn').replaceWith('<span style="font-size:11px;color:var(--text-muted);">Removed</span>');
        SwalDark.fire({ icon: 'success', title: r.message, timer: 1200, showConfirmButton: false });
      }
    });
  });
});
</script>
JS;
?>
