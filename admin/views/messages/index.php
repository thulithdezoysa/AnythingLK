<?php /* admin/views/messages/index.php */ ?>
<style>
/* ══ MESSAGES — RESPONSIVE ════════════════════════════════════════
   Scoped to .msg-* / #msgTable to avoid bleed.                  */

/* ── 1. Unread row highlight ──────────────────────────────────── */
.msg-row-unread    { font-weight: 600; }
.msg-row-unread td { background: rgba(0,212,255,.07) !important; }

/* ── 2. Badge + action button helpers (CSS classes, no inline) ── */
.msg-badge {
    display: inline-block; font-size: 10px; font-weight: 700;
    padding: 2px 8px; border-radius: 20px; white-space: nowrap;
}
.msg-badge-new  { background: var(--cyan-dim);  color: var(--cyan); }
.msg-badge-read { background: var(--surface2);  color: var(--text-muted); }

.msg-act-btn {
    background: var(--green-dim); color: var(--green);
    border: none; padding: 4px 8px; font-size: 12px;
    border-radius: var(--radius-sm); cursor: pointer;
    transition: var(--transition);
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 30px; min-height: 28px;
}
.msg-act-btn:hover { background: var(--green); color: #fff; }

/* ── 3. Table scroll wrapper (tablet / desktop) ───────────────── */
.msg-table-wrap {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: thin;
    scrollbar-color: rgba(255,255,255,.12) transparent;
}
.msg-table-wrap::-webkit-scrollbar       { height: 4px; }
.msg-table-wrap::-webkit-scrollbar-track { background: transparent; }
.msg-table-wrap::-webkit-scrollbar-thumb { background: rgba(255,255,255,.14); border-radius: 4px; }
@media (min-width: 768px) {
    #msgTable { min-width: 620px; }
}

/* ══ MOBILE CARD LAYOUT (≤ 767px) ════════════════════════════════
   7 DOM columns:
     (1) Name  (2) Email  (3) Subject  (4) Message
     (5) Date  (6) Status  (7) Action

   Card grid — 2 columns: 1fr auto
     Row 1 : Name (A)         | Status badge (B)
     Row 2 : Email (A)        | Date (B)
     Row 3 : Subject (A)      | Action btn (B)
     Row 4 : Message preview  (full width)          */
@media (max-width: 767px) {
    .msg-table-wrap { overflow-x: visible; }
    .msg-table-wrap .admin-table { overflow: visible !important; }

    #msgTable thead  { display: none; }
    #msgTable        { min-width: unset; }

    #msgTable tbody tr {
        display: grid;
        grid-template-columns: 1fr auto;
        grid-template-rows: auto auto auto auto;
        column-gap: 10px;
        row-gap: 3px;
        padding: 12px 16px;
        border-bottom: 1px solid var(--border);
    }
    #msgTable tbody tr:last-child { border-bottom: none; }

    /* Unread: left accent stripe instead of td background tint */
    #msgTable tbody tr.msg-row-unread {
        border-left: 3px solid var(--cyan);
        padding-left: 13px;
        background: rgba(0,212,255,.04);
    }
    #msgTable tbody tr.msg-row-unread td { background: transparent !important; }

    #msgTable tbody td {
        display: block !important;
        padding: 0 !important;
        border: none !important;
        background: transparent !important;
        vertical-align: unset !important;
    }

    /* ── Cell placement ─────────────────────────────────────── */

    /* (1) Name — A1 */
    #msgTable tbody td:nth-child(1) {
        grid-column: 1; grid-row: 1;
        font-size: 13px !important; font-weight: 600; align-self: center;
    }

    /* (2) Email — A2 */
    #msgTable tbody td:nth-child(2) {
        grid-column: 1; grid-row: 2;
        font-size: 11px !important; align-self: center;
        overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
    }

    /* (3) Subject — A3 */
    #msgTable tbody td:nth-child(3) {
        grid-column: 1; grid-row: 3;
        font-size: 11px !important; color: var(--text-dim); align-self: center;
    }
    #msgTable tbody td:nth-child(3)::before {
        content: 'Subj: '; font-size: 10px; opacity: .55;
    }

    /* (4) Message — full width, row 4 */
    #msgTable tbody td:nth-child(4) {
        grid-column: 1 / 3; grid-row: 4;
        font-size: 11px !important; color: var(--text-muted);
        margin-top: 4px; padding-top: 5px !important;
        border-top: 1px dashed var(--border) !important;
    }

    /* (5) Date — B2 */
    #msgTable tbody td:nth-child(5) {
        grid-column: 2; grid-row: 2;
        font-size: 10px !important; color: var(--text-muted);
        text-align: right; white-space: nowrap; align-self: center;
    }

    /* (6) Status — B1 */
    #msgTable tbody td:nth-child(6) {
        grid-column: 2; grid-row: 1;
        text-align: right; align-self: center;
    }

    /* (7) Action — B3 */
    #msgTable tbody td:nth-child(7) {
        grid-column: 2; grid-row: 3;
        text-align: right; align-self: center;
    }
}

/* ── Very small phones (< 380px) ─────────────────────────────── */
@media (max-width: 379px) {
    #msgTable tbody tr { padding: 10px 12px; column-gap: 8px; }
}
</style>

<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
  <div>
    <h4>Contact Messages <span class="badge ms-1" style="background:var(--surface2);color:var(--text-muted);font-size:12px;border-radius:8px;padding:3px 9px;"><?= number_format($total) ?></span></h4>
    <p class="page-subtitle">
      <?= number_format($total) ?> total
      <?= $unreadCount ? " — <strong style='color:var(--cyan);'>{$unreadCount} unread</strong>" : '' ?>
    </p>
  </div>
  <?php if ($unreadCount): ?>
  <button id="markAllRead" class="btn btn-sm" style="background:var(--cyan-dim);color:var(--cyan);border:1px solid rgba(6,182,212,.2);">
    <i class="fa fa-check-double me-1"></i>Mark All Read
  </button>
  <?php endif; ?>
</div>

<?php
$searchFields = [['name'=>'search','value'=>$search,'placeholder'=>'Search name, email, subject…']];
$filterFields = [['name'=>'filter','value'=>$filter,'options'=>['all'=>'All Messages','unread'=>'Unread','read'=>'Read']]];
$extraButtons = '';
include __DIR__ . '/../layouts/_pagination.php';
?>

<?php if (empty($messages)): ?>
<div class="card"><div class="card-body text-center py-5" style="color:var(--text-muted);">
  <i class="fa fa-envelope-o fa-3x mb-3 d-block"></i>
  <p>No messages yet.</p>
</div></div>
<?php else: ?>
<div class="card">
  <div class="card-body p-0">
    <div class="msg-table-wrap">
    <div class="admin-table">
      <table class="table table-sm align-middle mb-0" id="msgTable">
        <thead>
          <tr>
            <th style="width:13%;">Name</th>
            <th style="width:18%;">Email</th>
            <th style="width:20%;">Subject</th>
            <th>Message</th>
            <th style="width:90px;">Date</th>
            <th style="width:70px;text-align:center;">Status</th>
            <th style="width:48px;"></th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($messages as $m): ?>
        <tr id="msg-<?= $m['id'] ?>" class="<?= $m['is_read'] ? '' : 'msg-row-unread' ?>">
          <td style="font-size:13px;"><?= e($m['name']) ?></td>
          <td style="font-size:12px;">
            <a href="mailto:<?= e($m['email']) ?>" style="color:var(--cyan);text-decoration:none;" title="<?= e($m['email']) ?>">
              <?= e(Helper::truncate($m['email'], 24)) ?>
            </a>
          </td>
          <td style="font-size:12px;color:var(--text-dim);"><?= e(Helper::truncate($m['subject'] ?? '—', 30)) ?></td>
          <td style="font-size:12px;color:var(--text-muted);"><?= e(Helper::truncate($m['message'], 70)) ?></td>
          <td style="font-size:11px;color:var(--text-muted);white-space:nowrap;"><?= date('M j, Y', strtotime($m['created_at'])) ?></td>
          <td style="text-align:center;">
            <?php if ($m['is_read']): ?>
            <span class="msg-badge msg-badge-read">Read</span>
            <?php else: ?>
            <span class="msg-badge msg-badge-new">New</span>
            <?php endif; ?>
          </td>
          <td>
            <?php if (!$m['is_read']): ?>
            <button class="msg-act-btn mark-read" data-id="<?= $m['id'] ?>" title="Mark as read">
              <i class="fa fa-check"></i>
            </button>
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
<?php $itemLabel = 'messages'; include __DIR__ . '/../layouts/_pagnav.php'; ?>
<?php endif; ?>

<?php $extraScript = <<<'JS'
<script>
function markRead(id, row) {
  ajaxPost('admin/messages/mark-read', { id: id }, function(res) {
    if (!res.success) return;
    var $row = row || $('#msg-' + id);
    $row.removeClass('msg-row-unread')
        .css({ 'border-left': '', 'padding-left': '', background: '' });
    $row.find('.msg-act-btn').remove();
    $row.find('.msg-badge-new')
        .attr('class', 'msg-badge msg-badge-read')
        .text('Read');
  });
}

$(document).on('click', '.mark-read', function() {
  markRead($(this).data('id'), $(this).closest('tr'));
});

$('#markAllRead').on('click', function() {
  var btn = $(this);
  btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i>Marking…');
  var ids = [];
  $('.mark-read').each(function() { ids.push($(this).data('id')); });
  if (!ids.length) { btn.prop('disabled', false); return; }
  var done = 0;
  ids.forEach(function(id) {
    ajaxPost('admin/messages/mark-read', { id: id }, function() {
      if (++done === ids.length) location.reload();
    });
  });
});
</script>
JS;
?>
