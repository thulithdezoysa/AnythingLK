<?php $pageTitle = 'Payment Gateway Settings'; ?>
<style>
.pgw-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(340px,1fr));gap:1.25rem;}
.pgw-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden;transition:var(--transition);}
.pgw-card:hover{border-color:var(--border2);}
.pgw-head{display:flex;align-items:center;justify-content:space-between;padding:14px 18px;border-bottom:1px solid var(--border);background:var(--surface2);}
.pgw-head-left{display:flex;align-items:center;gap:10px;}
.pgw-code{font-family:monospace;font-size:11px;background:var(--surface2);color:var(--text-muted);padding:2px 8px;border-radius:4px;text-transform:uppercase;}
.pgw-title{font-size:14px;font-weight:700;color:var(--text);}
.pgw-driver{font-size:11px;color:var(--text-muted);margin-top:1px;}
.pgw-body{padding:16px 18px;}
.pgw-field{margin-bottom:12px;}
.pgw-label{display:block;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-muted);margin-bottom:5px;}
.pgw-input{width:100%;background:var(--bg2);border:1px solid var(--border);color:var(--text);border-radius:var(--radius-sm);padding:8px 11px;font-size:13px;outline:none;transition:var(--transition);font-family:monospace;}
.pgw-input:focus{border-color:var(--cyan);box-shadow:0 0 0 3px var(--cyan-dim);}
.pgw-foot{display:flex;align-items:center;justify-content:space-between;padding:12px 18px;border-top:1px solid var(--border);}
.pgw-toggles{display:flex;gap:.75rem;align-items:center;}
.pgw-toggle-label{display:flex;align-items:center;gap:6px;font-size:12px;color:var(--text-muted);cursor:pointer;user-select:none;}
.pgw-toggle{position:relative;width:36px;height:20px;flex-shrink:0;}
.pgw-toggle input{opacity:0;width:0;height:0;}
.pgw-slider{position:absolute;inset:0;background:var(--surface2);border-radius:10px;transition:var(--transition);cursor:pointer;}
.pgw-slider::before{content:'';position:absolute;width:14px;height:14px;left:3px;top:3px;background:var(--text-muted);border-radius:50%;transition:var(--transition);}
.pgw-toggle input:checked+.pgw-slider{background:var(--green);}
.pgw-toggle input:checked+.pgw-slider::before{transform:translateX(16px);background:#fff;}
.pgw-status{display:inline-flex;align-items:center;gap:5px;font-size:11px;font-weight:700;padding:3px 9px;border-radius:20px;}
.pgw-status.live{background:var(--green-dim);color:var(--green);}
.pgw-status.sandbox{background:var(--amber-dim);color:var(--amber);}
.pgw-status.internal{background:var(--cyan-dim);color:var(--cyan);}
.pgw-save-btn{display:inline-flex;align-items:center;gap:5px;padding:7px 15px;background:linear-gradient(135deg,var(--cyan),#0098d4);color:#000;font-weight:700;font-size:12px;border:none;border-radius:var(--radius-sm);cursor:pointer;transition:var(--transition);}
.pgw-save-btn:hover{transform:translateY(-1px);box-shadow:var(--glow-cyan);}
</style>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
  <div>
    <h4 style="margin:0;font-size:1.15rem;font-weight:700;">Payment Gateway Settings</h4>
    <div style="font-size:.75rem;color:var(--text-muted);margin-top:3px;">Configure API keys, sandbox mode and webhook URLs per gateway</div>
  </div>
  <div style="font-size:12px;color:var(--text-muted);">
    <i class="fa fa-lock me-1" style="color:var(--cyan);"></i>Keys are stored encrypted in the database
  </div>
</div>

<?php
$driverBadge = [
  'internal' => ['label'=>'Internal','cls'=>'internal'],
  'manual'   => ['label'=>'Manual','cls'=>'internal'],
  'payhere'  => ['label'=>'PayHere','cls'=>'live'],
  'koko'     => ['label'=>'KOKO API','cls'=>'live'],
  'mintpay'  => ['label'=>'MintPay API','cls'=>'live'],
  'payzy'    => ['label'=>'Payzy API','cls'=>'live'],
  'unknown'  => ['label'=>'Unknown','cls'=>'sandbox'],
];
$noKeysNeeded = ['internal','manual'];
?>

<div class="pgw-grid">
<?php foreach ($methods as $m):
  $code    = $m['code'];
  $s       = $settings[$code] ?? [];
  $driver  = PaymentGatewayFactory::driver($code);
  $db_info = $driverBadge[$driver] ?? $driverBadge['unknown'];
  $noKeys  = in_array($driver, $noKeysNeeded);
?>
<div class="pgw-card">
  <div class="pgw-head">
    <div class="pgw-head-left">
      <span class="pgw-code"><?= e($code) ?></span>
      <div>
        <div class="pgw-title"><?= e($m['display_name']) ?></div>
        <div class="pgw-driver">Driver: <?= $db_info['label'] ?></div>
      </div>
    </div>
    <span class="pgw-status <?= $db_info['cls'] ?>">
      <?php if (!empty($s['is_active'])): ?>
        <?= $s['is_sandbox'] ? 'Sandbox' : 'Live' ?>
      <?php else: ?>
        Disabled
      <?php endif; ?>
    </span>
  </div>

  <form class="pgw-form" data-code="<?= e($code) ?>">
    <input type="hidden" name="_csrf" value="<?= $csrfToken ?>">
    <input type="hidden" name="method_code" value="<?= e($code) ?>">
    <input type="hidden" name="display_name" value="<?= e($m['display_name']) ?>">

    <div class="pgw-body">
      <?php if ($noKeys): ?>
      <div style="text-align:center;padding:1.5rem 0;color:var(--text-muted);font-size:13px;">
        <i class="fa fa-circle-check" style="color:var(--green);font-size:1.5rem;display:block;margin-bottom:8px;"></i>
        No API keys required for this payment method.
      </div>
      <?php else: ?>
      <div class="pgw-field">
        <label class="pgw-label">API Key / Merchant ID</label>
        <input type="text" name="api_key" class="pgw-input" value="<?= e($s['api_key'] ?? '') ?>" placeholder="Enter API key…" autocomplete="off">
      </div>
      <div class="pgw-field">
        <label class="pgw-label">API Secret</label>
        <input type="password" name="api_secret" class="pgw-input" value="<?= e($s['api_secret'] ?? '') ?>" placeholder="Enter secret…" autocomplete="new-password">
      </div>
      <div class="pgw-field">
        <label class="pgw-label">Webhook / IPN URL</label>
        <input type="text" name="webhook_url" class="pgw-input" value="<?= e($s['webhook_url'] ?? '') ?>" placeholder="https://yourdomain.com/webhook/<?= e($code) ?>">
      </div>
      <div class="pgw-field" style="margin-bottom:0;">
        <label class="pgw-label">Extra Config <span style="font-weight:400;text-transform:none;">(JSON, optional)</span></label>
        <input type="text" name="extra_config" class="pgw-input" value="<?= e($s['extra_config'] ?? '') ?>" placeholder='{"merchant_id":"…"}'>
      </div>
      <?php endif; ?>
    </div>

    <div class="pgw-foot">
      <div class="pgw-toggles">
        <label class="pgw-toggle-label">
          <span class="pgw-toggle">
            <input type="checkbox" name="is_active" value="1" <?= !empty($s['is_active']) ? 'checked' : '' ?>>
            <span class="pgw-slider"></span>
          </span>
          Enabled
        </label>
        <?php if (!$noKeys): ?>
        <label class="pgw-toggle-label">
          <span class="pgw-toggle">
            <input type="checkbox" name="is_sandbox" value="1" <?= !isset($s['is_sandbox']) || $s['is_sandbox'] ? 'checked' : '' ?>>
            <span class="pgw-slider"></span>
          </span>
          Sandbox
        </label>
        <?php endif; ?>
      </div>
      <button type="submit" class="pgw-save-btn">
        <i class="fa fa-floppy-disk"></i> Save
      </button>
    </div>
  </form>
</div>
<?php endforeach; ?>
</div>

<?php $extraScript = <<<'JS'
<script>
document.querySelectorAll('.pgw-form').forEach(function(form) {
  form.addEventListener('submit', function(e) {
    e.preventDefault();
    var fd = new FormData(this);
    // Ensure unchecked checkboxes send 0
    if (!this.querySelector('[name="is_active"]').checked)  fd.set('is_active', '0');
    if (this.querySelector('[name="is_sandbox"]') && !this.querySelector('[name="is_sandbox"]').checked) fd.set('is_sandbox', '0');
    fetch(SITE_URL + '/admin/payment-gateway/save', {method:'POST', body: fd})
      .then(r => r.json()).then(function(res) {
        if (res.success) Swal.fire({icon:'success',title:res.message,timer:1400,showConfirmButton:false});
        else Swal.fire({icon:'error',title:res.message||'Error'});
      });
  });
});
</script>
JS;
?>
