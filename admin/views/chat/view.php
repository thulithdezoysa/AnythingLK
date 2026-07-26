<?php
$pageTitle = 'Chat #' . $session['id'];
$isClosed  = $session['status'] === 'closed';

$_sid     = (int)$session['id'];
$_lastId  = !empty($messages) ? (int)end($messages)['id'] : 0;
$_status  = ucfirst($session['status']);

$isGuest  = empty($session['user_name']);
$uName    = $isGuest ? 'Guest' : $session['user_name'];
$uEmail   = $session['user_email'] ?? '';
$uInitial = strtoupper(substr($uName, 0, 1));
$msgCount = count($messages);
$startedAt = date('M j, Y · g:i A', strtotime($session['created_at']));
?>
<style>
/* ══ CHAT VIEW — RESPONSIVE ══════════════════════════════════════
   Scoped to .cv-* to avoid bleed.                              */

/* ── 1. Layout grid ─────────────────────────────────────────── */
.cv-wrap {
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: 1rem;
    height: calc(100vh - 120px);
    min-height: 480px;
}

/* ── 2. Topbar ──────────────────────────────────────────────── */
.cv-topbar {
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: .5rem; margin-bottom: 1rem; min-width: 0;
}
.cv-heading {
    display: flex; align-items: center; gap: .5rem; min-width: 0; flex: 1;
}
.cv-heading h4 {
    font-size: 1.1rem; font-weight: 700; color: var(--text); margin: 0;
    letter-spacing: -.02em; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.cv-back {
    display: inline-flex; align-items: center; gap: .375rem;
    padding: 6px 12px; background: var(--surface2); border: 1px solid var(--border);
    border-radius: var(--radius-sm); color: var(--text-dim); font-size: .8rem;
    font-weight: 500; text-decoration: none; transition: var(--transition); flex-shrink: 0;
}
.cv-back:hover { border-color: var(--cyan); color: var(--cyan); }
.cv-status-pill {
    display: inline-flex; align-items: center; gap: 5px; padding: 3px 10px;
    border-radius: 20px; font-size: .7rem; font-weight: 600; flex-shrink: 0;
}
.cv-status-pill.open   { background: var(--green-dim); color: var(--green); }
.cv-status-pill.open i { animation: cv-pulse-dot 1.5s infinite; }
.cv-status-pill.closed { background: var(--surface2);  color: var(--text-muted); }
@keyframes cv-pulse-dot { 0%,100%{opacity:1;} 50%{opacity:.3;} }

/* Mobile-only close button in topbar — hidden by default */
.cv-topbar-close {
    display: none; align-items: center; gap: .35rem;
    padding: 6px 12px; background: var(--red-dim);
    border: 1px solid rgba(239,68,68,.25); border-radius: var(--radius-sm);
    color: var(--red); font-size: .78rem; font-weight: 600;
    cursor: pointer; transition: var(--transition); flex-shrink: 0;
}
.cv-topbar-close:hover { background: var(--red); color: #fff; }

/* ── 3. Chat window ─────────────────────────────────────────── */
.cv-window {
    display: flex; flex-direction: column;
    background: var(--surface); border: 1px solid var(--border);
    border-radius: var(--radius); overflow: hidden;
}
@media (min-width: 992px) { .cv-window { height: 100%; } }

.cv-messages {
    flex: 1; overflow-y: auto; padding: 1.25rem;
    display: flex; flex-direction: column; gap: .75rem; scroll-behavior: smooth;
}
.cv-messages::-webkit-scrollbar { width: 4px; }
.cv-messages::-webkit-scrollbar-thumb { background: var(--border2); border-radius: 4px; }

/* ── 4. Bubbles ─────────────────────────────────────────────── */
.cv-msg-row { display: flex; align-items: flex-end; gap: 8px; }
.cv-msg-row.from-user { flex-direction: row-reverse; }
.cv-msg-avatar {
    width: 28px; height: 28px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: .7rem; font-weight: 700; flex-shrink: 0; margin-bottom: 18px;
}
.cv-bubble {
    max-width: 72%; padding: 10px 14px; border-radius: 16px;
    font-size: .84rem; line-height: 1.6; word-break: break-word; position: relative;
}
.cv-bubble-time { font-size: .65rem; opacity: .55; margin-top: 4px; text-align: right; }

.from-user .cv-bubble  { background: linear-gradient(135deg,rgba(0,212,255,.22),rgba(0,212,255,.1)); border: 1px solid rgba(0,212,255,.25); color: var(--text); border-bottom-right-radius: 4px; }
.from-user .cv-msg-avatar { background: rgba(0,212,255,.15); color: var(--cyan); }
.from-admin .cv-bubble { background: linear-gradient(135deg,rgba(16,185,129,.22),rgba(16,185,129,.1)); border: 1px solid rgba(16,185,129,.25); color: var(--text); border-bottom-left-radius: 4px; }
.from-admin .cv-msg-avatar { background: rgba(16,185,129,.15); color: var(--green); }
.from-bot .cv-bubble   { background: var(--surface2); border: 1px solid var(--border); color: var(--text); border-bottom-left-radius: 4px; }
.from-bot .cv-msg-avatar { background: rgba(59,130,246,.15); color: var(--blue); }

/* ── 5. Date separator ──────────────────────────────────────── */
.cv-sep { text-align: center; position: relative; margin: .25rem 0; }
.cv-sep::before { content: ''; position: absolute; left: 0; right: 0; top: 50%; height: 1px; background: var(--border); }
.cv-sep span { position: relative; background: var(--surface); padding: 0 .75rem; font-size: .7rem; color: var(--text-muted); font-weight: 500; }

/* ── 6. Reply box ───────────────────────────────────────────── */
.cv-reply { border-top: 1px solid var(--border); padding: .875rem 1rem; flex-shrink: 0; background: var(--bg2); }
.cv-input-row { display: flex; gap: .5rem; align-items: center; }
.cv-input {
    flex: 1; background: var(--surface2); border: 1px solid var(--border);
    color: var(--text); border-radius: var(--radius-sm); padding: 9px 14px;
    font-size: .84rem; outline: none; transition: var(--transition); resize: none;
}
.cv-input:focus { border-color: var(--cyan); box-shadow: 0 0 0 3px var(--cyan-dim); }
.cv-input::placeholder { color: var(--text-muted); }
.cv-send {
    width: 38px; height: 38px; border-radius: var(--radius-sm);
    background: linear-gradient(135deg,var(--cyan),#0098d4);
    border: none; color: #000; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    font-size: .9rem; flex-shrink: 0; transition: var(--transition);
}
.cv-send:hover { transform: scale(1.08); box-shadow: var(--glow-cyan); }
.cv-quick-btns { display: flex; flex-wrap: wrap; gap: 5px; margin-top: .625rem; }
.cv-quick {
    padding: 4px 10px; font-size: .7rem; background: var(--surface2);
    border: 1px solid var(--border); border-radius: 20px; color: var(--text-dim);
    cursor: pointer; transition: var(--transition);
}
.cv-quick:hover { border-color: var(--cyan); color: var(--cyan); }
.cv-closed-note { padding: .875rem; text-align: center; font-size: .8rem; color: var(--text-muted); }

/* ── 7. Sidebar ─────────────────────────────────────────────── */
.cv-sidebar { display: flex; flex-direction: column; gap: .75rem; }
.cv-info-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); padding: 1.125rem; }
.cv-info-card-title { font-size: .65rem; font-weight: 700; text-transform: uppercase; letter-spacing: .1em; color: var(--text-muted); margin-bottom: .875rem; }
.cv-user-block { display: flex; align-items: center; gap: .75rem; margin-bottom: .875rem; }
.cv-user-avatar {
    width: 46px; height: 46px; border-radius: 50%;
    background: linear-gradient(135deg,var(--cyan-dim),rgba(124,58,237,.2));
    border: 2px solid var(--border2); display: flex; align-items: center;
    justify-content: center; font-weight: 700; font-size: 1.1rem; color: var(--cyan); flex-shrink: 0;
}
.cv-user-name  { font-weight: 600; font-size: .9rem; color: var(--text); }
.cv-user-email { font-size: .72rem; color: var(--text-muted); margin-top: 2px; }
.cv-stat-row   { display: flex; justify-content: space-between; align-items: center; padding: 6px 0; border-bottom: 1px solid var(--border); }
.cv-stat-row:last-child { border-bottom: none; }
.cv-stat-label { font-size: .75rem; color: var(--text-muted); }
.cv-stat-value { font-size: .78rem; color: var(--text); font-weight: 500; }
.cv-close-btn {
    width: 100%; padding: 8px; background: var(--red-dim);
    border: 1px solid rgba(239,68,68,.25); border-radius: var(--radius-sm);
    color: var(--red); font-size: .8rem; font-weight: 600; cursor: pointer;
    transition: var(--transition); display: flex; align-items: center; justify-content: center; gap: .4rem;
}
.cv-close-btn:hover { background: var(--red); color: #fff; }

/* ── 8. Tablet (≤ 991px): single column, sidebar above chat ─── */
@media (max-width: 991px) {
    .cv-wrap    { grid-template-columns: 1fr; height: auto; }
    .cv-sidebar { order: -1; }
    .cv-window  { height: 65vh; }
}

/* ── 9. Phone (≤ 767px): chat above, sidebar below ──────────── */
@media (max-width: 767px) {
    .cv-sidebar { order: 2; }           /* sidebar pushed BELOW the chat window */
    .cv-window  { height: 58vh; min-height: 300px; }

    /* Bigger send button for touch */
    .cv-send    { width: 44px; height: 44px; font-size: 1rem; }

    /* Quick replies scroll horizontally — no wrapping */
    .cv-quick-btns { flex-wrap: nowrap; overflow-x: auto; padding-bottom: 4px; scrollbar-width: none; }
    .cv-quick-btns::-webkit-scrollbar { display: none; }
    .cv-quick   { flex-shrink: 0; min-height: 30px; }

    /* Wider bubbles on small screens */
    .cv-bubble  { max-width: 84%; }

    /* Show mobile close button in topbar */
    .cv-topbar-close { display: flex; }

    /* Compact sidebar cards */
    .cv-info-card   { padding: .875rem; }
    .cv-user-avatar { width: 38px; height: 38px; font-size: .95rem; }
}

/* ── 10. Very small phones (< 380px) ────────────────────────── */
@media (max-width: 379px) {
    .cv-back span  { display: none; }   /* icon-only back button */
    .cv-back       { padding: 6px 9px; }
    .cv-heading h4 { font-size: .88rem; }
    .cv-window     { height: 52vh; }
    .cv-reply      { padding: .625rem .75rem; }
    .cv-messages   { padding: .875rem .75rem; }
    .cv-bubble     { max-width: 88%; }
}
</style>

<div class="cv-topbar">
  <div class="cv-heading">
    <a href="<?= Helper::url('admin/chat') ?>" class="cv-back"><i class="fa fa-arrow-left"></i> <span>Back</span></a>
    <h4>Conversation #<?= $_sid ?></h4>
    <span class="cv-status-pill <?= $isClosed ? 'closed' : 'open' ?>">
      <i class="fa fa-circle" style="font-size:6px;"></i> <?= $_status ?>
    </span>
  </div>
  <?php if (!$isClosed): ?>
  <button class="cv-topbar-close" id="topbarCloseBtn">
    <i class="fa fa-times-circle"></i> Close
  </button>
  <?php endif; ?>
</div>

<div class="cv-wrap">

  <!-- ── Chat Window ── -->
  <div class="cv-window">

    <div id="msgArea" class="cv-messages">
      <?php
      $lastDate = null;
      foreach ($messages as $m):
        $msgDate = date('Y-m-d', strtotime($m['created_at']));
        if ($msgDate !== $lastDate):
          $lastDate = $msgDate;
          $label = (date('Y-m-d') === $msgDate) ? 'Today' : date('M j, Y', strtotime($m['created_at']));
      ?>
        <div class="cv-sep"><span><?= $label ?></span></div>
      <?php endif;
        $sender = $m['sender'];
        $isUser = $sender === 'user';
        $rowCls = $isUser ? 'from-user' : 'from-' . $sender;
        $avatarIcon = $isUser ? 'U' : ($sender === 'admin' ? 'A' : '<i class="fa fa-robot" style="font-size:.6rem;"></i>');
      ?>
        <div class="cv-msg-row <?= $rowCls ?>">
          <div class="cv-msg-avatar"><?= $avatarIcon ?></div>
          <div class="cv-bubble">
            <?= nl2br(e($m['message'])) ?>
            <div class="cv-bubble-time"><?= date('h:i A', strtotime($m['created_at'])) ?></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <?php if (!$isClosed): ?>
    <div class="cv-reply">
      <div class="cv-input-row">
        <input type="text" id="adminMsg" class="cv-input" placeholder="Type a reply…" autocomplete="off">
        <button class="cv-send" id="sendBtn"><i class="fa fa-paper-plane"></i></button>
      </div>
      <div class="cv-quick-btns">
        <?php foreach (['Order received!','Processing your request.','Allow 1-2 business days.','Thank you for contacting us!'] as $q): ?>
        <button class="cv-quick" data-text="<?= e($q) ?>"><?= e($q) ?></button>
        <?php endforeach; ?>
      </div>
    </div>
    <?php else: ?>
    <div class="cv-closed-note"><i class="fa fa-lock me-2" style="color:var(--text-muted);"></i>This conversation is closed.</div>
    <?php endif; ?>

  </div>

  <!-- ── Sidebar ── -->
  <div class="cv-sidebar">

    <!-- User info -->
    <div class="cv-info-card">
      <div class="cv-user-block">
        <div class="cv-user-avatar"><?= $uInitial ?></div>
        <div>
          <div class="cv-user-name"><?= e($uName) ?></div>
          <?php if ($uEmail): ?>
          <div class="cv-user-email"><?= e($uEmail) ?></div>
          <?php else: ?>
          <div class="cv-user-email">Guest session</div>
          <?php endif; ?>
        </div>
      </div>
      <div>
        <div class="cv-stat-row">
          <span class="cv-stat-label">Session ID</span>
          <span class="cv-stat-value">#<?= $_sid ?></span>
        </div>
        <div class="cv-stat-row">
          <span class="cv-stat-label">Status</span>
          <span class="cv-stat-value">
            <span class="cv-status-pill <?= $isClosed ? 'closed' : 'open' ?>" style="padding:2px 8px;">
              <i class="fa fa-circle" style="font-size:5px;"></i> <?= $_status ?>
            </span>
          </span>
        </div>
        <div class="cv-stat-row">
          <span class="cv-stat-label">Started</span>
          <span class="cv-stat-value" style="font-size:.72rem;"><?= $startedAt ?></span>
        </div>
        <div class="cv-stat-row">
          <span class="cv-stat-label">Messages</span>
          <span class="cv-stat-value"><?= $msgCount ?></span>
        </div>
        <?php if ($session['user_id']): ?>
        <div class="cv-stat-row">
          <span class="cv-stat-label">User ID</span>
          <span class="cv-stat-value">#<?= (int)$session['user_id'] ?></span>
        </div>
        <?php endif; ?>
      </div>
    </div>

    <?php if (!$isClosed): ?>
    <!-- Actions -->
    <div class="cv-info-card">
      <div class="cv-info-card-title">Actions</div>
      <button class="cv-close-btn" id="closeBtn">
        <i class="fa fa-times-circle"></i> Close Conversation
      </button>
    </div>
    <?php endif; ?>

  </div>

</div>

<?php
$_pollJs = '';
if (!$isClosed) {
    $_pollJs = <<<JS

/* IDs of messages we sent ourselves and already appended optimistically.
   Guards against the race where pollNow fetches a message before the
   sendMsg response arrives, causing it to be appended a second time. */
var pendingIds = new Set();
var sending    = false;

function sendMsg() {
  var input = document.getElementById('adminMsg');
  var msg   = input.value.trim();
  if (!msg || sending) return;
  sending = true;
  input.value = '';
  ajaxPost('admin/chat/reply', {session_id: SID, message: msg}, function(res) {
    sending = false;
    if (res.success) {
      /* Advance lastId immediately so the next poll won't re-fetch this message. */
      if (res.id) {
        lastId = Math.max(lastId, res.id);
        pendingIds.add(res.id);
      }
      appendBubble({sender:'admin', message: msg.replace(/\\n/g,'<br>'), time: res.time || ''});
      scrollBottom();
    } else {
      /* Restore message so admin can retry */
      input.value = msg;
    }
  });
}

document.getElementById('sendBtn').addEventListener('click', sendMsg);
document.getElementById('adminMsg').addEventListener('keydown', function(e) {
  if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMsg(); }
});

document.querySelectorAll('.cv-quick').forEach(function(b) {
  b.addEventListener('click', function() {
    document.getElementById('adminMsg').value = this.dataset.text;
    document.getElementById('adminMsg').focus();
  });
});

function doCloseConversation() {
  if (!confirm('Close this conversation?')) return;
  ajaxPost('admin/chat/close', {session_id: SID}, function(res) { if (res.success) location.reload(); });
}

document.getElementById('closeBtn')?.addEventListener('click', doCloseConversation);
document.getElementById('topbarCloseBtn')?.addEventListener('click', doCloseConversation);

function pollNow() {
  fetch(SITE_URL + '/admin/chat/messages?session_id=' + SID + '&last_id=' + lastId)
    .then(function(r){ return r.json(); })
    .then(function(res) {
      if (!res.messages || !res.messages.length) return;
      var appended = false;
      res.messages.forEach(function(m) {
        lastId = Math.max(lastId, m.id);
        if (pendingIds.has(m.id)) {
          /* We already appended this message optimistically — skip it. */
          pendingIds.delete(m.id);
          return;
        }
        appendBubble(m);
        appended = true;
      });
      if (appended) scrollBottom();
    })
    .catch(function() { /* silent — next poll will retry */ });
}
setInterval(pollNow, 5000);
JS;
}

$extraScript = <<<JS
<script>
var SID     = $_sid;
var lastId  = $_lastId;
var area    = document.getElementById('msgArea');

function scrollBottom() { area.scrollTop = area.scrollHeight; }
scrollBottom();

function appendBubble(m) {
  var isUser  = m.sender === 'user';
  var rowCls  = isUser ? 'from-user' : 'from-' + m.sender;
  var avatarHtml = isUser ? 'U' : (m.sender === 'admin' ? 'A' : '<i class="fa fa-robot" style="font-size:.6rem;"></i>');
  var wrap = document.createElement('div');
  wrap.className = 'cv-msg-row ' + rowCls;
  wrap.innerHTML = '<div class="cv-msg-avatar">' + avatarHtml + '</div>'
    + '<div class="cv-bubble">' + m.message
    + '<div class="cv-bubble-time">' + (m.time || '') + '</div></div>';
  area.appendChild(wrap);
}

$_pollJs
</script>
JS;
?>
