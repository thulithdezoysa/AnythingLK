<?php $pageTitle = 'Track Your Order'; ?>
<style>
/* ── OTP digit boxes ─────────────────────────────────────── */
.otp-grid {
  display: flex;
  gap: 10px;
  justify-content: center;
  margin: 28px 0 8px;
}
.otp-digit {
  width: 52px;
  height: 60px;
  text-align: center;
  font-size: 26px;
  font-weight: 800;
  font-family: 'Courier New', monospace;
  border: 2px solid var(--border);
  border-radius: 12px;
  background: var(--bg-soft);
  color: var(--text);
  outline: none;
  transition: border-color .2s, box-shadow .2s;
  caret-color: transparent;
}
.otp-digit:focus {
  border-color: var(--brand);
  box-shadow: 0 0 0 3px rgba(230,57,70,.15);
}
.otp-digit.filled {
  border-color: var(--brand);
  background: var(--surface2);
}

/* ── Step indicator ──────────────────────────────────────── */
.ot-steps {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0;
  margin-bottom: 32px;
}
.ot-step-dot {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 13px;
  font-weight: 700;
  flex-shrink: 0;
  transition: .25s;
}
.ot-step-dot.active   { background: var(--brand); color: #fff; }
.ot-step-dot.done     { background: #10b981; color: #fff; }
.ot-step-dot.inactive { background: var(--border); color: var(--text-muted); }
.ot-step-label {
  font-size: 11px;
  font-weight: 600;
  margin-top: 4px;
  text-align: center;
}
.ot-step-label.active   { color: var(--brand); }
.ot-step-label.done     { color: #10b981; }
.ot-step-label.inactive { color: var(--text-muted); }
.ot-step-line {
  flex: 1;
  height: 2px;
  background: var(--border);
  max-width: 60px;
  margin-bottom: 18px;
  transition: .25s;
}
.ot-step-line.done { background: #10b981; }

/* ── Verified badge ──────────────────────────────────────── */
.ot-verified-chip {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  background: #d1fae5;
  color: #065f46;
  border-radius: 20px;
  padding: 4px 14px;
  font-size: 12px;
  font-weight: 700;
}

/* ── Countdown bar ───────────────────────────────────────── */
.otp-timer-bar {
  height: 3px;
  border-radius: 3px;
  background: var(--border);
  overflow: hidden;
  margin-top: 6px;
}
.otp-timer-fill {
  height: 100%;
  border-radius: 3px;
  background: var(--brand);
  transition: width 1s linear, background .3s;
}

/* ── Resend button ───────────────────────────────────────── */
#resendBtn:disabled {
  opacity: .55;
  cursor: not-allowed;
}

/* ── Mobile tweaks ───────────────────────────────────────── */
@media (max-width: 480px) {
  .otp-digit { width: 42px; height: 52px; font-size: 22px; border-radius: 10px; }
  .otp-grid  { gap: 7px; }
}
@media (max-width: 360px) {
  .otp-digit { width: 36px; height: 48px; font-size: 19px; }
  .otp-grid  { gap: 5px; }
}
</style>

<div class="page-breadcrumb">
  <div class="container"><nav><ol class="breadcrumb mb-0 small">
    <li class="breadcrumb-item"><a href="<?= url('') ?>">Home</a></li>
    <li class="breadcrumb-item active" style="color:var(--text-muted);">Order Tracking</li>
  </ol></nav></div>
</div>

<div class="container py-5">
  <div class="mx-auto" style="max-width:640px;">

    <!-- Page heading -->
    <div class="text-center mb-4">
      <h2 class="fw-bold mb-1">Track Your Order</h2>
      <p style="color:var(--text-muted);margin:0;">
        <?php if ($step === 'form'): ?>
          Enter your order number and email to receive a verification code.
        <?php elseif ($step === 'verify'): ?>
          Enter the 6-digit code sent to your email to verify your identity.
        <?php else: ?>
          Your identity is verified. Here are your order details.
        <?php endif; ?>
      </p>
    </div>

    <!-- Step indicator -->
    <?php
      $s1 = $step === 'form'   ? 'active' : 'done';
      $s2 = $step === 'verify' ? 'active' : ($step === 'details' ? 'done' : 'inactive');
      $s3 = $step === 'details'? 'done'   : 'inactive';
      $l1 = ($s1 === 'done' || $s2 !== 'inactive') ? 'done' : '';
      $l2 = ($s2 === 'done') ? 'done' : '';
    ?>
    <div class="ot-steps mb-2">
      <div class="text-center">
        <div class="ot-step-dot <?= $s1 ?>"><?= $s1 === 'done' ? '✓' : '1' ?></div>
        <div class="ot-step-label <?= $s1 ?>">Order Info</div>
      </div>
      <div class="ot-step-line <?= $l1 ?>"></div>
      <div class="text-center">
        <div class="ot-step-dot <?= $s2 ?>"><?= $s2 === 'done' ? '✓' : '2' ?></div>
        <div class="ot-step-label <?= $s2 ?>">Verify OTP</div>
      </div>
      <div class="ot-step-line <?= $l2 ?>"></div>
      <div class="text-center">
        <div class="ot-step-dot <?= $s3 ?>"><?= $s3 === 'done' ? '✓' : '3' ?></div>
        <div class="ot-step-label <?= $s3 ?>">Tracking</div>
      </div>
    </div>

    <!-- Flash messages -->
    <?php if (!empty($error)): ?>
    <div class="alert alert-danger d-flex align-items-start gap-2 mt-3">
      <i class="fas fa-exclamation-circle mt-1 flex-shrink-0"></i>
      <span><?= e($error) ?></span>
    </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
    <div class="alert alert-success d-flex align-items-start gap-2 mt-3">
      <i class="fas fa-check-circle mt-1 flex-shrink-0"></i>
      <span><?= e($success) ?></span>
    </div>
    <?php endif; ?>


    <?php /* ══════════════════════════════════════════════════════
              STEP 1 — Enter order number + email
           ══════════════════════════════════════════════════════ */ ?>
    <?php if ($step === 'form'): ?>
    <div class="card-base p-4 mt-3">
      <form method="POST" action="<?= url('order-tracking/send-otp') ?>" id="sendOtpForm">
        <?= CSRF::field() ?>

        <div class="mb-3">
          <label class="form-label fw-semibold">
            <i class="fas fa-hashtag me-1" style="color:var(--brand);"></i>Order Number
          </label>
          <input type="text" name="order_number" class="form-control form-control-lg"
                 placeholder="e.g. ORD-20240101-ABC123"
                 value="<?= e($_GET['order'] ?? '') ?>"
                 autocomplete="off" autocapitalize="characters" spellcheck="false"
                 required>
          <div class="form-text">Found in your order confirmation email.</div>
        </div>

        <div class="mb-4">
          <label class="form-label fw-semibold">
            <i class="fas fa-envelope me-1" style="color:var(--brand);"></i>Email Address
          </label>
          <input type="email" name="email" class="form-control form-control-lg"
                 placeholder="The email you used when ordering"
                 autocomplete="email" required>
          <div class="form-text">We'll send a one-time code to this address.</div>
        </div>

        <button type="submit" class="btn-brand w-100 py-3 rounded-3 fw-bold fs-6 text-white" id="sendOtpBtn"
                style="border:none;cursor:pointer;transition:.2s;">
          <i class="fas fa-paper-plane me-2"></i>Send Verification Code
        </button>
      </form>
    </div>

    <div class="text-center mt-3" style="font-size:13px;color:var(--text-muted);">
      <i class="fas fa-shield-alt me-1"></i>
      Your information is kept private and secure.
    </div>
    <?php endif; ?>


    <?php /* ══════════════════════════════════════════════════════
              STEP 2 — Enter OTP
           ══════════════════════════════════════════════════════ */ ?>
    <?php if ($step === 'verify'): ?>
    <?php
      $isLocked = ($lockedUntil > 0 && time() < $lockedUntil);
      $lockMins = $isLocked ? (int)ceil(($lockedUntil - time()) / 60) : 0;
      $otpValid = ($otpExpires > time());
      $otpSecsLeft = max(0, $otpExpires - time());
    ?>
    <div class="card-base p-4 mt-3">

      <?php if ($isLocked): ?>
      <!-- ── Locked out ─────────────────────────────────────── -->
      <div class="text-center py-3">
        <div style="font-size:48px;margin-bottom:12px;">🔒</div>
        <h5 class="fw-bold mb-2">Temporarily Locked</h5>
        <p style="color:var(--text-muted);">
          Too many incorrect attempts. Please try again in
          <strong><?= $lockMins ?> minute<?= $lockMins !== 1 ? 's' : '' ?></strong>.
        </p>
        <a href="<?= url('order-tracking/reset') ?>" class="btn btn-secondary btn-sm mt-2">
          <i class="fas fa-arrow-left me-1"></i>Start Over
        </a>
      </div>

      <?php else: ?>
      <!-- ── OTP entry ──────────────────────────────────────── -->
      <div class="text-center mb-1">
        <i class="fas fa-shield-alt fa-2x mb-3" style="color:var(--brand);"></i>
        <h5 class="fw-bold mb-1">Enter Verification Code</h5>
        <p style="color:var(--text-muted);font-size:14px;margin:0;">
          We sent a 6-digit code to
          <strong><?= e($maskedEmail) ?></strong>.
        </p>
        <?php if ($otpValid): ?>
        <div style="margin-top:10px;">
          <small id="otpTimerText" style="color:var(--text-muted);font-size:12px;">
            Code expires in <strong id="otpCountdown"><?= $otpSecsLeft ?></strong>s
          </small>
          <div class="otp-timer-bar mx-auto mt-1" style="max-width:220px;">
            <div class="otp-timer-fill" id="otpTimerFill"
                 style="width:<?= min(100, round($otpSecsLeft / 600 * 100)) ?>%;"></div>
          </div>
        </div>
        <?php else: ?>
        <div class="mt-2">
          <span class="badge bg-warning text-dark"><i class="fas fa-exclamation-triangle me-1"></i>Code expired — please resend</span>
        </div>
        <?php endif; ?>
      </div>

      <form method="POST" action="<?= url('order-tracking/verify-otp') ?>" id="verifyForm"
            <?= !$otpValid ? 'style="opacity:.45;pointer-events:none;"' : '' ?>>
        <?= CSRF::field() ?>
        <input type="hidden" name="otp" id="otpHidden">

        <div class="otp-grid" id="otpGrid">
          <?php for ($i = 0; $i < 6; $i++): ?>
          <input type="text" class="otp-digit"
                 maxlength="1" inputmode="numeric" pattern="[0-9]"
                 autocomplete="one-time-code"
                 data-idx="<?= $i ?>"
                 <?= $i === 0 ? 'autofocus' : '' ?>>
          <?php endfor; ?>
        </div>

        <?php if ($otpValid): ?>
        <button type="submit" class="btn-brand w-100 py-3 rounded-3 fw-bold text-white mt-3" id="verifyBtn"
                style="border:none;cursor:pointer;font-size:15px;" disabled>
          <i class="fas fa-check-circle me-2"></i>Verify &amp; View Order
        </button>
        <?php endif; ?>
      </form>

      <!-- Resend + back row -->
      <div class="d-flex align-items-center justify-content-between mt-4 flex-wrap gap-2">
        <a href="<?= url('order-tracking/reset') ?>"
           style="font-size:13px;color:var(--text-muted);text-decoration:none;">
          <i class="fas fa-arrow-left me-1"></i>Change order / email
        </a>

        <form method="POST" action="<?= url('order-tracking/resend-otp') ?>" id="resendForm">
          <?= CSRF::field() ?>
          <button type="submit" id="resendBtn" class="btn btn-outline-secondary btn-sm"
                  <?= $resendIn > 0 ? 'disabled' : '' ?>>
            <i class="fas fa-redo me-1"></i>
            Resend Code<?= $resendIn > 0 ? ' (<span id="resendCountdown">' . $resendIn . '</span>s)' : '' ?>
          </button>
        </form>
      </div>
      <?php endif; ?>

    </div>

    <div class="text-center mt-3" style="font-size:12px;color:var(--text-muted);">
      <i class="fas fa-lock me-1"></i>
      Check your spam folder if you don't see the email within a minute.
    </div>
    <?php endif; ?>


    <?php /* ══════════════════════════════════════════════════════
              STEP 3 — Tracking Details
           ══════════════════════════════════════════════════════ */ ?>
    <?php if ($step === 'details' && $order): ?>
    <?php
      $allStatuses  = ['pending','confirmed','processing','shipped','delivered'];
      $currentIdx   = array_search($order['status'], $allStatuses);
      $statusColors = ['pending'=>'#f59e0b','confirmed'=>'#3b82f6','processing'=>'#8b5cf6','shipped'=>'#06b6d4','delivered'=>'#10b981','cancelled'=>'#ef4444'];
      $statusIcons  = ['pending'=>'🕐','confirmed'=>'✅','processing'=>'⚙️','shipped'=>'🚚','delivered'=>'🏠','cancelled'=>'❌'];
      $verifiedMins = (int)floor((time() - (int)$_SESSION['ot_verified_at']) / 60);
      $sessionExpiresIn = max(0, 30 - $verifiedMins);
    ?>

    <!-- Verified chip + session info -->
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-3 mb-3">
      <span class="ot-verified-chip">
        <i class="fas fa-shield-alt"></i> Identity Verified
      </span>
      <div style="font-size:12px;color:var(--text-muted);">
        <i class="fas fa-clock me-1"></i>Session expires in ~<?= $sessionExpiresIn ?> min
        &nbsp;·&nbsp;
        <a href="<?= url('order-tracking/reset') ?>" style="color:var(--text-muted);text-decoration:underline;font-size:12px;">
          Track another order
        </a>
      </div>
    </div>

    <!-- Order header card -->
    <div class="card-base p-4 mb-3">
      <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-4">
        <div>
          <h5 class="fw-bold mb-1"><?= e($order['order_number']) ?></h5>
          <small style="color:var(--text-muted);">
            Placed on <?= date('M j, Y \a\t g:i A', strtotime($order['created_at'])) ?>
          </small>
        </div>
        <span class="badge rounded-pill px-3 py-2"
              style="background:<?= $statusColors[$order['status']] ?? '#6b7280' ?>;font-size:12px;font-weight:700;">
          <?= $statusIcons[$order['status']] ?? '📦' ?> <?= ucfirst($order['status']) ?>
        </span>
      </div>

      <?php if ($order['status'] !== 'cancelled'): ?>
      <!-- Progress bar -->
      <div class="position-relative mb-5 pb-2">
        <div style="position:absolute;top:20px;left:10%;width:80%;height:3px;background:var(--border);z-index:0;border-radius:3px;">
          <div style="width:<?= $currentIdx >= 0 ? ($currentIdx / (count($allStatuses) - 1)) * 100 : 0 ?>%;
                       height:100%;background:var(--brand);border-radius:3px;transition:width .5s;"></div>
        </div>
        <div class="d-flex justify-content-between position-relative" style="z-index:1;">
          <?php $labels = ['Ordered','Confirmed','Processing','Shipped','Delivered']; ?>
          <?php foreach ($allStatuses as $i => $st): ?>
          <div class="text-center" style="flex:1;">
            <div class="mx-auto d-flex align-items-center justify-content-center rounded-circle fw-bold mb-2"
                 style="width:40px;height:40px;
                        background:<?= $i <= $currentIdx ? 'var(--brand)' : 'var(--border)' ?>;
                        color:<?= $i <= $currentIdx ? '#fff' : 'var(--text-muted)' ?>;
                        font-size:15px;transition:.3s;">
              <?= $i <= $currentIdx ? '✓' : ($i + 1) ?>
            </div>
            <div style="font-size:11px;
                        font-weight:<?= $i === $currentIdx ? '700' : '400' ?>;
                        color:<?= $i <= $currentIdx ? 'var(--brand)' : 'var(--text-muted)' ?>;">
              <?= $labels[$i] ?>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <!-- Delivery + payment info -->
      <div class="row g-3 mb-4">
        <div class="col-sm-6">
          <div style="background:var(--bg-soft);border-radius:10px;padding:14px;">
            <div class="small fw-bold mb-2" style="color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em;font-size:10px;">
              <i class="fas fa-map-marker-alt me-1"></i>Delivery Address
            </div>
            <div style="font-size:13px;line-height:1.6;">
              <strong><?= e($order['shipping_name']) ?></strong><br>
              <?= e($order['shipping_address']) ?><br>
              <?= e($order['shipping_city']) ?>
              <?php if ($order['shipping_state']): ?>, <?= e($order['shipping_state']) ?><?php endif; ?>
              <?php if ($order['shipping_postal']): ?> <?= e($order['shipping_postal']) ?><?php endif; ?>
              <br><?= e($order['shipping_country'] ?? 'Sri Lanka') ?>
              <?php if (!empty($order['shipping_phone'])): ?>
              <br><small style="color:var(--text-muted);"><i class="fas fa-phone me-1"></i><?= e($order['shipping_phone']) ?></small>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <div class="col-sm-6">
          <div style="background:var(--bg-soft);border-radius:10px;padding:14px;">
            <div class="small fw-bold mb-2" style="color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em;font-size:10px;">
              <i class="fas fa-credit-card me-1"></i>Payment
            </div>
            <div style="font-size:13px;">
              <?= ucfirst(str_replace(['_','-'], ' ', $order['payment_method'] ?? '')) ?>
            </div>
            <span class="badge mt-1 <?= $order['payment_status'] === 'paid' ? 'bg-success' : 'bg-warning text-dark' ?>">
              <?= ucfirst($order['payment_status']) ?>
            </span>
          </div>
        </div>

        <?php if (!empty($order['tracking_number'])): ?>
        <div class="col-12">
          <div style="background:#eff6ff;border-radius:10px;padding:14px;border:1px solid #bfdbfe;">
            <div class="small fw-bold mb-1" style="color:#1d4ed8;text-transform:uppercase;font-size:10px;letter-spacing:.05em;">
              <i class="fas fa-truck me-1"></i>Tracking Number
            </div>
            <div class="fw-bold" style="font-size:16px;letter-spacing:.05em;"><?= e($order['tracking_number']) ?></div>
          </div>
        </div>
        <?php endif; ?>
      </div>

      <!-- Items summary -->
      <div class="d-flex justify-content-between align-items-center mb-1">
        <span class="fw-semibold" style="font-size:14px;">
          <i class="fas fa-box me-1" style="color:var(--brand);"></i>
          <?= (int)$order['item_count'] ?> item<?= (int)$order['item_count'] !== 1 ? 's' : '' ?> ordered
        </span>
        <span class="fw-bold" style="font-size:15px;color:var(--brand);">
          LKR <?= number_format((float)$order['total_amount'], 2) ?>
        </span>
      </div>

      <?php if (!empty($order['items'])): ?>
      <div style="border-top:1px solid var(--border);margin-top:12px;padding-top:12px;">
        <?php foreach ($order['items'] as $item): ?>
        <div class="d-flex justify-content-between align-items-start py-2"
             style="border-bottom:1px solid var(--border);">
          <div style="font-size:13px;flex:1;padding-right:12px;">
            <div class="fw-semibold"><?= e($item['product_name']) ?></div>
            <?php if (!empty($item['variation_name'])): ?>
            <small style="color:var(--text-muted);"><?= e($item['variation_name']) ?></small>
            <?php endif; ?>
            <small style="color:var(--text-muted);display:block;">Qty: <?= (int)$item['quantity'] ?></small>
          </div>
          <div style="font-size:13px;font-weight:600;white-space:nowrap;">
            LKR <?= number_format((float)$item['total_price'], 2) ?>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>

    <?php if (!empty($order['history'])): ?>
    <!-- Status history -->
    <div class="card-base p-4">
      <h6 class="fw-bold mb-3"><i class="fas fa-history me-2" style="color:var(--brand);"></i>Order History</h6>
      <div style="position:relative;padding-left:20px;">
        <?php foreach (array_reverse($order['history']) as $h): ?>
        <div style="position:relative;margin-bottom:18px;">
          <div style="position:absolute;left:-20px;top:4px;width:10px;height:10px;
                      border-radius:50%;background:<?= $statusColors[$h['status']] ?? '#6b7280' ?>;
                      box-shadow:0 0 0 3px var(--surface2);"></div>
          <div style="font-size:13px;font-weight:700;">
            <?= $statusIcons[$h['status']] ?? '📦' ?> <?= ucfirst($h['status']) ?>
          </div>
          <?php if (!empty($h['notes'])): ?>
          <div style="font-size:12px;color:var(--text-muted);margin-top:2px;"><?= e($h['notes']) ?></div>
          <?php endif; ?>
          <div style="font-size:11px;color:var(--text-muted);margin-top:2px;">
            <?= date('M j, Y — g:i A', strtotime($h['created_at'])) ?>
          </div>
        </div>
        <?php endforeach; ?>
        <div style="position:absolute;left:-15px;top:8px;bottom:24px;width:2px;background:var(--border);border-radius:2px;"></div>
      </div>
    </div>
    <?php endif; ?>
    <?php endif; ?>

  </div><!-- /.mx-auto -->
</div><!-- /.container -->

<?php /* ══════════════════════════════════════════════════════
          JavaScript (OTP boxes + countdown timers)
       ══════════════════════════════════════════════════════ */ ?>
<?php if ($step === 'verify'): ?>
<script>
(function () {
  'use strict';

  /* ── OTP digit-box logic ──────────────────────────────── */
  var digits  = document.querySelectorAll('.otp-digit');
  var hidden  = document.getElementById('otpHidden');
  var verBtn  = document.getElementById('verifyBtn');

  function syncHidden() {
    var val = '';
    digits.forEach(function (d) { val += d.value; });
    if (hidden) hidden.value = val;
    if (verBtn) verBtn.disabled = (val.length < 6 || !/^\d{6}$/.test(val));
  }

  digits.forEach(function (el, idx) {
    el.addEventListener('input', function () {
      // Allow only digits
      el.value = el.value.replace(/\D/g, '').slice(0, 1);
      el.classList.toggle('filled', el.value !== '');
      if (el.value && idx < digits.length - 1) digits[idx + 1].focus();
      syncHidden();
    });

    el.addEventListener('keydown', function (e) {
      if (e.key === 'Backspace' && !el.value && idx > 0) {
        digits[idx - 1].value = '';
        digits[idx - 1].classList.remove('filled');
        digits[idx - 1].focus();
        syncHidden();
      }
      // Allow only number keys, backspace, delete, tab, arrows
      if (!['Backspace','Delete','Tab','ArrowLeft','ArrowRight'].includes(e.key) && !/^\d$/.test(e.key)) {
        e.preventDefault();
      }
    });

    el.addEventListener('focus', function () { el.select(); });
  });

  // Paste support: paste 6 digits into all boxes at once
  (digits[0] || document.body).addEventListener('paste', function (e) {
    var paste = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '');
    if (paste.length >= 6) {
      e.preventDefault();
      for (var i = 0; i < 6; i++) {
        digits[i].value = paste[i] || '';
        digits[i].classList.toggle('filled', !!digits[i].value);
      }
      syncHidden();
      if (digits[5]) digits[5].focus();
    }
  });

  /* ── OTP expiry countdown ─────────────────────────────── */
  var otpSecsLeft = <?= (int)max(0, $otpExpires - time()) ?>;
  var otpTotalSecs = 600; // 10 minutes total
  var countdownEl  = document.getElementById('otpCountdown');
  var fillEl       = document.getElementById('otpTimerFill');

  if (countdownEl && otpSecsLeft > 0) {
    var otpTimer = setInterval(function () {
      otpSecsLeft--;
      if (otpSecsLeft <= 0) {
        clearInterval(otpTimer);
        countdownEl.textContent = '0';
        if (fillEl) { fillEl.style.width = '0%'; fillEl.style.background = '#ef4444'; }
        var form = document.getElementById('verifyForm');
        if (form) { form.style.opacity = '.45'; form.style.pointerEvents = 'none'; }
        var timerText = document.getElementById('otpTimerText');
        if (timerText) timerText.innerHTML = '<span style="color:#ef4444;font-weight:700;">Code expired — use Resend to get a new one.</span>';
        return;
      }
      if (countdownEl) countdownEl.textContent = otpSecsLeft;
      if (fillEl) {
        var pct = Math.round((otpSecsLeft / otpTotalSecs) * 100);
        fillEl.style.width = pct + '%';
        fillEl.style.background = otpSecsLeft < 60 ? '#ef4444' : (otpSecsLeft < 180 ? '#f59e0b' : 'var(--brand)');
      }
    }, 1000);
  }

  /* ── Resend countdown ─────────────────────────────────── */
  var resendBtn      = document.getElementById('resendBtn');
  var resendCdEl     = document.getElementById('resendCountdown');
  var resendSecsLeft = <?= (int)$resendIn ?>;

  if (resendBtn && resendSecsLeft > 0) {
    resendBtn.disabled = true;
    var resendTimer = setInterval(function () {
      resendSecsLeft--;
      if (resendCdEl) resendCdEl.textContent = resendSecsLeft;
      if (resendSecsLeft <= 0) {
        clearInterval(resendTimer);
        resendBtn.disabled = false;
        resendBtn.innerHTML = '<i class="fas fa-redo me-1"></i>Resend Code';
      }
    }, 1000);
  }

  /* ── Submit spinner ───────────────────────────────────── */
  var verifyForm = document.getElementById('verifyForm');
  if (verifyForm) {
    verifyForm.addEventListener('submit', function () {
      if (verBtn) {
        verBtn.disabled = true;
        verBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Verifying…';
      }
    });
  }

  /* ── Send-OTP form spinner ────────────────────────────── */
  var sendForm = document.getElementById('sendOtpForm');
  var sendBtn  = document.getElementById('sendOtpBtn');
  if (sendForm && sendBtn) {
    sendForm.addEventListener('submit', function () {
      sendBtn.disabled = true;
      sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending Code…';
    });
  }
}());
</script>
<?php endif; ?>
<?php if ($step === 'form'): ?>
<script>
(function () {
  var f = document.getElementById('sendOtpForm');
  var b = document.getElementById('sendOtpBtn');
  if (f && b) {
    f.addEventListener('submit', function () {
      b.disabled = true;
      b.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending Code…';
    });
  }
}());
</script>
<?php endif; ?>
