<?php
/**
 * Payment Logos Component
 *
 * Usage:
 *   $paymentCodes = ['visa','mastercard','cod','koko','mintpay','payzy'];
 *   $paymentSize  = 'md';   // 'sm' | 'md' | 'lg'
 *   $paymentLabel = true;   // show "We accept:" label
 *   include view('components/payment_logos');
 *
 * Or pass $paymentMethods as array of rows from payment_methods table.
 * Falls back to all active methods from DB if neither variable set.
 */

// Resolve which codes to show
if (!isset($paymentCodes)) {
    if (isset($paymentMethods) && is_array($paymentMethods)) {
        $paymentCodes = array_column($paymentMethods, 'code');
    } else {
        // Load all active from DB (lazy fallback)
        global $db;
        if (isset($db)) {
            $rows = $db->select("SELECT code FROM payment_methods WHERE is_active=1 ORDER BY sort_order");
            $paymentCodes = $rows ? array_column($rows, 'code') : ['visa','mastercard','cod'];
        } else {
            $paymentCodes = ['visa','mastercard','cod'];
        }
    }
}

$_size  = $paymentSize  ?? 'md';
$_label = $paymentLabel ?? false;

$_heights = ['sm' => 20, 'md' => 28, 'lg' => 36];
$_h       = $_heights[$_size] ?? 28;

$_svgDir  = url('assets/payments/');

$_knownLogos = ['visa','mastercard','cod','koko','mintpay','payzy'];
?>
<div class="pm-logos pm-logos--<?= $_size ?>">
  <?php if ($_label): ?>
    <span class="pm-logos__label">We accept:</span>
  <?php endif; ?>
  <div class="pm-logos__strip">
    <?php foreach ($paymentCodes as $_code):
        $_code = strtolower(trim($_code));
        if (!in_array($_code, $_knownLogos, true)) continue;
    ?>
      <span class="pm-logo-wrap" title="<?= ucfirst($_code) ?>">
        <img
          src="<?= $_svgDir . e($_code) ?>.svg"
          alt="<?= ucfirst($_code) ?>"
          class="pm-logo"
          height="<?= $_h ?>"
          loading="lazy"
          decoding="async"
        >
      </span>
    <?php endforeach; ?>
  </div>
</div>
