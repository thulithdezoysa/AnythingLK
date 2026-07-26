<?php $pageTitle = 'Auto-Reply Rules'; ?>

<style>
/* ── Auto-Replies Page Styles ── */
.ar-header { margin-bottom: 20px; }
.ar-search { flex: 1; max-width: 300px; }
.ar-search input {
  background: var(--surface2); border: 1px solid var(--border2);
  border-radius: 10px 0 0 10px; color: var(--text); font-size: 13px;
  padding: 8px 12px; outline: none; transition: border-color .2s;
  width: 100%;
}
.ar-search input:focus { border-color: var(--cyan); }
.ar-search input::placeholder { color: var(--text-muted); }
.ar-search-clear {
  background: var(--surface2); border: 1px solid var(--border2); border-left: none;
  border-radius: 0 10px 10px 0; color: var(--text-muted); padding: 0 12px;
  cursor: pointer; font-size: 12px; transition: color .18s;
}
.ar-search-clear:hover { color: var(--red); }

/* Card */
.ar-card-wrap {
  background: var(--surface); border: 1px solid var(--border2);
  border-radius: 14px; overflow: hidden;
}

/* Table */
.ar-table { width: 100%; border-collapse: collapse; }
.ar-table thead th {
  background: rgba(255,255,255,.04);
  border-bottom: 1px solid var(--border2);
  padding: 11px 16px; font-size: .72rem; font-weight: 700;
  text-transform: uppercase; letter-spacing: .07em; color: var(--text-muted);
  white-space: nowrap;
}
.ar-table tbody tr {
  border-bottom: 1px solid var(--border);
  transition: background .15s;
}
.ar-table tbody tr:last-child { border-bottom: none; }
.ar-table tbody tr:hover { background: rgba(255,255,255,.035); }
.ar-table td { padding: 12px 16px; vertical-align: middle; }

/* Keyword badges */
.kw-badge {
  display: inline-block; padding: 3px 9px; border-radius: 20px;
  font-size: .7rem; font-weight: 600; margin: 2px 2px 2px 0;
  background: var(--cyan-dim); color: var(--cyan);
  border: 1px solid rgba(0,212,255,.2);
}

/* Reply text */
.reply-text { font-size: .8rem; color: var(--text-dim); line-height: 1.4; }

/* Toggle switch */
.ts-wrap { display: inline-flex; align-items: center; gap: 8px; }
.ts {
  position: relative; display: inline-block;
  width: 40px; height: 21px; flex-shrink: 0;
}
.ts input { opacity: 0; width: 0; height: 0; }
.ts-track {
  position: absolute; cursor: pointer; inset: 0;
  background: var(--surface2); border: 1px solid var(--border2);
  border-radius: 21px; transition: .25s;
}
.ts-track::before {
  content: ''; position: absolute;
  width: 15px; height: 15px; left: 2px; top: 2px;
  background: var(--text-muted); border-radius: 50%;
  transition: .25s; box-shadow: 0 1px 4px rgba(0,0,0,.4);
}
.ts input:checked + .ts-track { background: rgba(16,185,129,.25); border-color: var(--green); }
.ts input:checked + .ts-track::before { transform: translateX(19px); background: var(--green); }

/* Action buttons */
.ar-btn {
  width: 30px; height: 30px; border-radius: 8px;
  display: inline-flex; align-items: center; justify-content: center;
  font-size: 12px; border: 1px solid transparent; cursor: pointer;
  transition: all .18s; background: none;
}
.ar-btn-edit { color: var(--blue); border-color: var(--blue-dim); background: var(--blue-dim); }
.ar-btn-edit:hover { background: var(--blue); color: #fff; border-color: var(--blue); }
.ar-btn-del  { color: var(--red);  border-color: var(--red-dim);  background: var(--red-dim); }
.ar-btn-del:hover  { background: var(--red);  color: #fff; border-color: var(--red); }

/* Empty state */
.ar-empty { padding: 60px 20px; text-align: center; color: var(--text-muted); }
.ar-empty i { font-size: 40px; opacity: .25; display: block; margin-bottom: 12px; }

/* Mobile cards */
.ar-mob-card {
  background: var(--surface2); border: 1px solid var(--border2);
  border-radius: 12px; padding: 14px 16px; margin-bottom: 10px;
  transition: border-color .18s;
}
.ar-mob-card:hover { border-color: var(--border2); }
.ar-mob-card-kw { margin-bottom: 8px; }
.ar-mob-card-reply { font-size: .78rem; color: var(--text-dim); margin-bottom: 12px; line-height: 1.45; }
.ar-mob-card-footer { display: flex; align-items: center; justify-content: space-between; }

/* FAB */
.ar-fab {
  position: fixed; bottom: 26px; right: 22px; z-index: 99;
  width: 50px; height: 50px; border-radius: 50%;
  background: linear-gradient(135deg, var(--cyan), #0099cc);
  border: none; color: #000; font-size: 18px;
  box-shadow: var(--glow-cyan); cursor: pointer;
  display: none; align-items: center; justify-content: center;
  transition: transform .22s cubic-bezier(.34,1.5,.64,1);
}
.ar-fab:hover { transform: scale(1.1); }
@media (max-width: 640px) {
  .ar-fab { display: flex; }
  .ar-desktop { display: none !important; }
  .ar-mobile  { display: block !important; }
}
@media (min-width: 641px) {
  .ar-mobile  { display: none !important; }
  .ar-desktop { display: block !important; }
}

/* Modal form */
.ar-form-label {
  font-size: .72rem; font-weight: 700; text-transform: uppercase;
  letter-spacing: .06em; color: var(--text-muted); margin-bottom: 6px; display: block;
}
.ar-input, .ar-textarea {
  width: 100%; background: var(--surface2); border: 1px solid var(--border2);
  border-radius: 10px; color: var(--text); font-size: .85rem; padding: 9px 12px;
  outline: none; transition: border-color .2s; font-family: inherit;
}
.ar-input:focus, .ar-textarea:focus { border-color: var(--cyan); box-shadow: 0 0 0 3px rgba(0,212,255,.1); }
.ar-input::placeholder, .ar-textarea::placeholder { color: var(--text-muted); }
.ar-input.is-invalid, .ar-textarea.is-invalid { border-color: var(--red); }
.ar-textarea { resize: vertical; min-height: 100px; }
.ar-hint { font-size: .72rem; color: var(--text-muted); margin-top: 5px; }

/* Radio status */
.ar-radio-group { display: flex; gap: 16px; margin-top: 4px; }
.ar-radio-label {
  display: flex; align-items: center; gap: 7px; cursor: pointer;
  font-size: .82rem; font-weight: 600;
}
.ar-radio-label input[type=radio] { accent-color: var(--cyan); width: 14px; height: 14px; }

/* ── Header toolbar: full-width search row on very small phones ── */
@media (max-width: 479px) {
    .ar-header > div:last-child {
        width: 100%;
    }
    .ar-search {
        flex: 1 1 100%;
        max-width: 100%;
    }
}
</style>

<!-- Page Header -->
<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 ar-header">
  <div class="d-flex align-items-center gap-2">
    <a href="<?= Helper::url('admin/chat') ?>" class="btn btn-secondary btn-sm"><i class="fa fa-arrow-left"></i></a>
    <h4 style="margin:0;font-size:16px;font-weight:700;">Auto-Reply Rules</h4>
    <span style="background:var(--surface2);border:1px solid var(--border2);color:var(--text-dim);padding:2px 10px;border-radius:20px;font-size:.72rem;font-weight:700;" id="ruleCount"><?= count($replies) ?></span>
  </div>
  <div class="d-flex align-items-center gap-2 flex-wrap">
    <div class="d-flex ar-search">
      <input type="text" id="arSearch" placeholder="Search keywords or replies…">
      <button class="ar-search-clear" id="arClear" title="Clear"><i class="fa fa-times"></i></button>
    </div>
    <button class="btn btn-primary btn-sm ar-desktop" id="addBtnTop"><i class="fa fa-plus" style="margin-right:6px;"></i>Add Rule</button>
  </div>
</div>

<!-- Desktop Table -->
<div class="ar-card-wrap ar-desktop" id="desktopWrap">
  <?php if (empty($replies)): ?>
  <div class="ar-empty" id="emptyState">
    <i class="fa fa-comments-o"></i>
    No auto-reply rules yet.<br>
    <small>Add your first rule to automate responses.</small>
  </div>
  <?php else: ?>
  <table class="ar-table" id="rulesTable">
    <thead>
      <tr>
        <th style="width:26%">Keywords</th>
        <th>Reply</th>
        <th style="width:90px;text-align:center;">Status</th>
        <th style="width:80px;text-align:center;">Actions</th>
      </tr>
    </thead>
    <tbody id="tableBody">
      <?php foreach ($replies as $r): ?>
      <tr id="row-<?= $r['id'] ?>"
          data-kw="<?= e($r['keyword']) ?>"
          data-reply="<?= e($r['reply']) ?>">
        <td>
          <?php foreach (array_map('trim', explode(',', $r['keyword'])) as $kw): ?>
            <span class="kw-badge"><?= e($kw) ?></span>
          <?php endforeach; ?>
        </td>
        <td>
          <span class="reply-text"><?= e(mb_strimwidth($r['reply'], 0, 110, '…')) ?></span>
        </td>
        <td style="text-align:center;">
          <label class="ts" title="Toggle status">
            <input type="checkbox" class="status-chk" data-id="<?= $r['id'] ?>" <?= $r['status'] ? 'checked' : '' ?>>
            <span class="ts-track"></span>
          </label>
        </td>
        <td style="text-align:center;">
          <button class="ar-btn ar-btn-edit edit-btn me-1"
            data-id="<?= $r['id'] ?>"
            data-kw="<?= e($r['keyword']) ?>"
            data-reply="<?= e($r['reply']) ?>"
            data-status="<?= (int)$r['status'] ?>"
            title="Edit"><i class="fa fa-pencil"></i></button>
          <button class="ar-btn ar-btn-del del-btn" data-id="<?= $r['id'] ?>" title="Delete">
            <i class="fa fa-trash"></i>
          </button>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>
</div>

<!-- Mobile Cards -->
<div class="ar-mobile" id="mobileWrap">
  <?php foreach ($replies as $r): ?>
  <div class="ar-mob-card" id="mcard-<?= $r['id'] ?>"
       data-kw="<?= e($r['keyword']) ?>"
       data-reply="<?= e($r['reply']) ?>">
    <div class="ar-mob-card-kw">
      <?php foreach (array_map('trim', explode(',', $r['keyword'])) as $kw): ?>
        <span class="kw-badge"><?= e($kw) ?></span>
      <?php endforeach; ?>
    </div>
    <div class="ar-mob-card-reply"><?= e(mb_strimwidth($r['reply'], 0, 100, '…')) ?></div>
    <div class="ar-mob-card-footer">
      <label class="ts">
        <input type="checkbox" class="status-chk" data-id="<?= $r['id'] ?>" <?= $r['status'] ? 'checked' : '' ?>>
        <span class="ts-track"></span>
      </label>
      <div style="display:flex;gap:6px;">
        <button class="ar-btn ar-btn-edit edit-btn"
          data-id="<?= $r['id'] ?>"
          data-kw="<?= e($r['keyword']) ?>"
          data-reply="<?= e($r['reply']) ?>"
          data-status="<?= (int)$r['status'] ?>"><i class="fa fa-pencil"></i></button>
        <button class="ar-btn ar-btn-del del-btn" data-id="<?= $r['id'] ?>"><i class="fa fa-trash"></i></button>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
  <?php if (empty($replies)): ?>
  <div class="ar-empty"><i class="fa fa-comments-o"></i>No rules yet.</div>
  <?php endif; ?>
</div>

<!-- Mobile FAB -->
<button class="ar-fab" id="fabBtn" title="Add Rule"><i class="fa fa-plus"></i></button>

<!-- Add/Edit Modal -->
<div class="modal" id="ruleModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <span class="modal-title" id="modalTitle">Add Auto-Reply Rule</span>
        <button type="button" class="modal-close btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="editId" value="0">
        <div style="margin-bottom:18px;">
          <label class="ar-form-label" for="kwInput">Keywords <span style="color:var(--text-muted);text-transform:none;font-weight:400;">(comma-separated)</span></label>
          <input type="text" class="ar-input" id="kwInput" placeholder="e.g. hello, hi, hey">
          <p class="ar-hint">Bot replies when <em>any</em> keyword is found in the user's message</p>
        </div>
        <div style="margin-bottom:18px;">
          <label class="ar-form-label" for="replyInput">Reply Message</label>
          <textarea class="ar-textarea" id="replyInput" placeholder="Enter the bot's reply text…" rows="4"></textarea>
        </div>
        <div>
          <label class="ar-form-label">Status</label>
          <div class="ar-radio-group">
            <label class="ar-radio-label" style="color:var(--green);">
              <input type="radio" name="arStatus" id="statusOn" value="1" checked> Active
            </label>
            <label class="ar-radio-label" style="color:var(--text-muted);">
              <input type="radio" name="arStatus" id="statusOff" value="0"> Disabled
            </label>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary btn-sm" id="saveBtn" style="min-width:100px;">
          <i class="fa fa-save" style="margin-right:5px;"></i>Save Rule
        </button>
      </div>
    </div>
  </div>
</div>

<?php $extraScript = <<<'JS'
<script>
(function () {

const modalEl = document.getElementById('ruleModal');
const bsModal = new bootstrap.Modal(modalEl);

function notify(type, msg) {
  SwalDark.fire({ icon: type, title: msg, timer: 1800, showConfirmButton: false, toast: true, position: 'top-end' });
}

function openAdd() {
  document.getElementById('editId').value = '0';
  document.getElementById('modalTitle').textContent = 'Add Auto-Reply Rule';
  document.getElementById('kwInput').value = '';
  document.getElementById('replyInput').value = '';
  document.getElementById('statusOn').checked = true;
  document.getElementById('kwInput').classList.remove('is-invalid');
  document.getElementById('replyInput').classList.remove('is-invalid');
  bsModal.show();
  modalEl.addEventListener('shown.bs.modal', () => document.getElementById('kwInput').focus(), { once: true });
}

['addBtnTop', 'fabBtn'].forEach(id => {
  document.getElementById(id)?.addEventListener('click', openAdd);
});

// Edit
document.addEventListener('click', function (e) {
  const btn = e.target.closest('.edit-btn');
  if (!btn) return;
  document.getElementById('editId').value = btn.dataset.id;
  document.getElementById('modalTitle').textContent = 'Edit Rule';
  document.getElementById('kwInput').value = btn.dataset.kw;
  document.getElementById('replyInput').value = btn.dataset.reply;
  document.getElementById(btn.dataset.status === '1' ? 'statusOn' : 'statusOff').checked = true;
  document.getElementById('kwInput').classList.remove('is-invalid');
  document.getElementById('replyInput').classList.remove('is-invalid');
  bsModal.show();
  modalEl.addEventListener('shown.bs.modal', () => document.getElementById('kwInput').focus(), { once: true });
});

// Save
document.getElementById('saveBtn').addEventListener('click', function () {
  const id     = document.getElementById('editId').value;
  const kw     = document.getElementById('kwInput').value.trim();
  const reply  = document.getElementById('replyInput').value.trim();
  const status = document.querySelector('input[name="arStatus"]:checked').value;

  let ok = true;
  if (!kw)    { document.getElementById('kwInput').classList.add('is-invalid'); ok = false; }
  if (!reply) { document.getElementById('replyInput').classList.add('is-invalid'); ok = false; }
  if (!ok) return;

  this.disabled = true;
  this.innerHTML = '<i class="fa fa-spinner fa-spin" style="margin-right:5px;"></i>Saving…';

  ajaxPost('admin/chat/auto-replies/save', { id, keyword: kw, reply, status, _csrf: CSRF_TOKEN }, res => {
    this.disabled = false;
    this.innerHTML = '<i class="fa fa-save" style="margin-right:5px;"></i>Save Rule';
    if (res.success) {
      bsModal.hide();
      notify('success', res.message || 'Saved!');
      setTimeout(() => location.reload(), 1000);
    } else {
      notify('error', res.message || 'Could not save.');
    }
  });
});

// Delete
document.addEventListener('click', function (e) {
  const btn = e.target.closest('.del-btn');
  if (!btn) return;
  const id = btn.dataset.id;
  SwalDark.fire({
    title: 'Delete this rule?',
    text: 'This cannot be undone.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Delete',
    confirmButtonColor: '#ef4444'
  }).then(r => {
    if (!r.isConfirmed) return;
    ajaxPost('admin/chat/auto-replies/delete', { id, _csrf: CSRF_TOKEN }, res => {
      if (res.success) {
        document.getElementById('row-' + id)?.remove();
        document.getElementById('mcard-' + id)?.remove();
        const cnt = document.getElementById('ruleCount');
        if (cnt) cnt.textContent = Math.max(0, parseInt(cnt.textContent || '1') - 1);
        notify('success', 'Rule deleted.');
      }
    });
  });
});

// Toggle status
document.addEventListener('change', function (e) {
  const chk = e.target.closest('.status-chk');
  if (!chk) return;
  ajaxPost('admin/chat/auto-replies/toggle', { id: chk.dataset.id, _csrf: CSRF_TOKEN }, res => {
    if (!res.success) {
      chk.checked = !chk.checked;
      notify('error', 'Toggle failed.');
    } else {
      notify('success', chk.checked ? 'Rule enabled.' : 'Rule disabled.');
    }
  });
});

// Live search filter
const searchEl = document.getElementById('arSearch');
const clearEl  = document.getElementById('arClear');

function doFilter(q) {
  q = q.toLowerCase();
  document.querySelectorAll('#tableBody tr').forEach(row => {
    const hay = (row.dataset.kw + ' ' + row.dataset.reply).toLowerCase();
    row.style.display = (!q || hay.includes(q)) ? '' : 'none';
  });
  document.querySelectorAll('#mobileWrap .ar-mob-card').forEach(card => {
    const hay = (card.dataset.kw + ' ' + card.dataset.reply).toLowerCase();
    card.style.display = (!q || hay.includes(q)) ? '' : 'none';
  });
}

searchEl?.addEventListener('input', function () { doFilter(this.value); });
clearEl?.addEventListener('click', () => { searchEl.value = ''; doFilter(''); searchEl.focus(); });

})();
</script>
JS;
?>
