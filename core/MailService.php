<?php
// ============================================================
// MAIL SERVICE — All email notifications, DB-driven branding
// ============================================================
require_once ROOT . '/vendor/phpmailer/PHPMailer.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailService
{
    // ── SMTP mailer, from-address pulled from settings table ─────────
    private static function mailer(): PHPMailer
    {
        $config = parse_ini_file(ROOT . '/config.ini');
        $m = new PHPMailer();

        if (!empty($config['mail_host'])) {
            $m->isSMTP();
            $m->Host       = $config['mail_host'];
            $m->Port       = (int)($config['mail_port'] ?? 587);
            $m->SMTPAuth   = true;
            $m->Username   = $config['mail_user'] ?? '';
            $m->Password   = $config['mail_pass'] ?? '';
            $m->SMTPSecure = $config['mail_encryption'] ?? 'tls';
        } else {
            $m->isMail();
        }

        $siteName    = Helper::setting('site_name', 'Anything.lk');
        $fromAddress = $config['mail_from_address'] ?? Helper::setting('support_email', 'noreply@anything.lk');
        $fromName    = $config['mail_from_name']    ?? $siteName;

        $m->setFrom($fromAddress, $fromName);
        $m->isHTML(true);
        $m->CharSet = 'UTF-8';
        return $m;
    }

    // ── Branded HTML wrapper ─────────────────────────────────────────
    private static function wrap(string $title, string $bodyHtml): string
    {
        $siteName     = htmlspecialchars(Helper::setting('site_name',    'Anything.lk'));
        $siteUrl      = Helper::url('');
        $brandColor   = Helper::setting('brand_color', '#E63946');
        $logoFile     = Helper::setting('site_logo', '');
        $logoUrl      = $logoFile ? Helper::url('uploads/logo/' . $logoFile) : '';
        $supportEmail = htmlspecialchars(Helper::setting('support_email', ''));
        $supportPhone = htmlspecialchars(Helper::setting('support_phone', ''));
        $siteAddress  = htmlspecialchars(Helper::setting('site_address',  ''));
        $year         = date('Y');

        // Derive a darker shade for gradients
        $brandDark = self::darkenColor($brandColor, 20);

        $contactParts = [];
        if ($supportEmail) {
            $contactParts[] = '<a href="mailto:' . $supportEmail . '" style="color:#a0aec0;text-decoration:none;transition:color .2s;">'
                            . '<img src="https://img.icons8.com/ios-filled/14/a0aec0/new-post.png" style="vertical-align:middle;margin-right:5px;" alt=""/>'
                            . $supportEmail . '</a>';
        }
        if ($supportPhone) {
            $contactParts[] = '<a href="tel:' . preg_replace('/[^+\d]/', '', $supportPhone) . '" style="color:#a0aec0;text-decoration:none;">'
                            . '<img src="https://img.icons8.com/ios-filled/14/a0aec0/phone.png" style="vertical-align:middle;margin-right:5px;" alt=""/>'
                            . $supportPhone . '</a>';
        }
        $contactLine = $contactParts
            ? '<p style="margin:0 0 8px;">' . implode(' &nbsp;&bull;&nbsp; ', $contactParts) . '</p>'
            : '';

        $addrLine = $siteAddress
            ? '<p style="margin:0 0 8px;color:#718096;font-size:12px;">'
              . '<img src="https://img.icons8.com/ios-filled/12/718096/marker.png" style="vertical-align:middle;margin-right:4px;" alt=""/>'
              . $siteAddress . '</p>'
            : '';

        if ($logoUrl) {
            $logoHtml = '<img src="' . htmlspecialchars($logoUrl) . '" alt="' . $siteName . '"'
                      . ' style="max-height:52px;max-width:200px;object-fit:contain;display:block;margin:0 auto;">';
        } else {
            $logoHtml = '<span style="color:#ffffff;font-size:26px;font-weight:800;letter-spacing:.8px;'
                      . 'font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif;'
                      . 'text-shadow:0 2px 8px rgba(0,0,0,0.15);">' . $siteName . '</span>';
        }

        return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>{$title}</title>
<!--[if mso]>
<noscript>
  <xml>
    <o:OfficeDocumentSettings>
      <o:PixelsPerInch>96</o:PixelsPerInch>
    </o:OfficeDocumentSettings>
  </xml>
</noscript>
<![endif]-->
<style>
  @media only screen and (max-width:600px) {
    .email-outer  { padding: 0 !important; }
    .email-card   { border-radius: 0 !important; width: 100% !important; }
    .email-header { padding: 28px 20px !important; }
    .email-body   { padding: 28px 20px !important; }
    .email-footer { padding: 24px 20px !important; }
    .cta-btn      { display:block !important; width:88% !important; text-align:center !important; }
    .hide-mobile  { display:none !important; }
  }
</style>
</head>
<body style="margin:0;padding:0;background:#f0f2f5;-webkit-font-smoothing:antialiased;">

<!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#f0f2f5"><tr><td><![endif]-->

<table class="email-outer" role="presentation" width="100%" cellpadding="0" cellspacing="0"
  style="background:#f0f2f5;padding:40px 16px;">
<tr>
<td align="center">

  <!-- Email Card -->
  <table class="email-card" role="presentation" width="600" cellpadding="0" cellspacing="0"
    style="max-width:600px;width:100%;border-radius:20px;overflow:hidden;
           box-shadow:0 20px 60px rgba(0,0,0,0.12);background:#ffffff;">

    <!-- ═══ HEADER ═══ -->
    <tr>
      <td class="email-header"
        style="background:linear-gradient(135deg, {$brandColor} 0%, {$brandDark} 100%);
               padding:36px 40px;text-align:center;position:relative;">

        <!-- Decorative circle -->
        <div style="position:absolute;top:-30px;right:-30px;width:120px;height:120px;
             border-radius:50%;background:rgba(255,255,255,0.08);pointer-events:none;"></div>

        <a href="{$siteUrl}" style="text-decoration:none;display:inline-block;">
          {$logoHtml}
        </a>

        <p style="margin:8px 0 0;color:rgba(255,255,255,0.78);font-size:13px;letter-spacing:.4px;
                  font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif;">
          {$title}
        </p>

      </td>
    </tr>

    <!-- ═══ BODY ═══ -->
    <tr>
      <td class="email-body"
        style="padding:40px 44px;color:#2d3748;font-size:15px;line-height:1.75;
               font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif;">
        {$bodyHtml}
      </td>
    </tr>

    <!-- ═══ DIVIDER ═══ -->
    <tr>
      <td style="padding:0 44px;">
        <div style="height:1px;background:linear-gradient(to right,transparent,#e2e8f0,transparent);"></div>
      </td>
    </tr>

    <!-- ═══ FOOTER ═══ -->
    <tr>
      <td class="email-footer"
        style="background:#1a202c;padding:32px 40px;text-align:center;
               font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif;">

        <p style="margin:0 0 4px;color:#e2e8f0;font-size:16px;font-weight:700;
                  letter-spacing:.5px;">{$siteName}</p>

        <div style="margin:14px 0 10px;font-size:13px;color:#a0aec0;line-height:2;">
          {$contactLine}
          {$addrLine}
        </div>

        <!-- Social links -->
        <div style="margin:16px 0 20px;">
          <a href="#" style="display:inline-block;margin:0 5px;width:34px;height:34px;
             line-height:34px;border-radius:50%;background:#2d3748;color:#a0aec0;
             text-decoration:none;font-size:15px;text-align:center;">🌐</a>
          <a href="#" style="display:inline-block;margin:0 5px;width:34px;height:34px;
             line-height:34px;border-radius:50%;background:#2d3748;color:#a0aec0;
             text-decoration:none;font-size:15px;text-align:center;">📘</a>
          <a href="#" style="display:inline-block;margin:0 5px;width:34px;height:34px;
             line-height:34px;border-radius:50%;background:#2d3748;color:#a0aec0;
             text-decoration:none;font-size:15px;text-align:center;">📸</a>
        </div>

        <p style="margin:0;font-size:11px;color:#4a5568;line-height:1.8;">
          &copy; {$year} {$siteName}. All rights reserved.<br>
          You received this email because you registered or placed an order with us.
        </p>

      </td>
    </tr>

  </table>
  <!-- /Email Card -->

</td>
</tr>
</table>

<!--[if mso | IE]></td></tr></table><![endif]-->

</body>
</html>
HTML;
    }

    // ── Accent CTA button ────────────────────────────────────────────
    private static function btn(string $url, string $label, string $color = '', string $icon = ''): string
    {
        $c    = $color ?: Helper::setting('brand_color', '#E63946');
        $dark = self::darkenColor($c, 15);
        $ico  = $icon ? "<span style='margin-right:8px;'>{$icon}</span>" : '';
        return "
<div style='text-align:center;margin:32px 0;'>
  <a href='{$url}' class='cta-btn'
     style='background:linear-gradient(135deg,{$c} 0%,{$dark} 100%);
            color:#ffffff;text-decoration:none;padding:15px 40px;
            border-radius:50px;font-weight:700;font-size:15px;
            display:inline-block;letter-spacing:.4px;
            box-shadow:0 8px 24px rgba(0,0,0,0.18);
            font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Arial,sans-serif;'>
    {$ico}{$label}
  </a>
</div>";
    }

    // ── Styled info box ──────────────────────────────────────────────
    private static function infoBox(array $rows, string $title = ''): string
    {
        $brandColor = Helper::setting('brand_color', '#E63946');
        $titleHtml  = $title
            ? "<p style='margin:0 0 14px;font-size:11px;font-weight:700;letter-spacing:1.2px;
                          color:{$brandColor};text-transform:uppercase;'>{$title}</p>"
            : '';
        $html = "<div style='background:#f7fafc;border-radius:14px;padding:22px 24px;
                              margin:24px 0;border:1px solid #e2e8f0;'>
                  {$titleHtml}
                  <table width='100%' cellpadding='0' cellspacing='0'>";
        foreach ($rows as [$label, $value]) {
            $html .= "
<tr>
  <td style='padding:9px 0;color:#718096;font-size:13px;border-bottom:1px solid #edf2f7;
             font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Arial,sans-serif;
             vertical-align:middle;'>
    {$label}
  </td>
  <td style='padding:9px 0;text-align:right;font-size:14px;font-weight:600;
             color:#1a202c;border-bottom:1px solid #edf2f7;
             font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Arial,sans-serif;
             vertical-align:middle;'>
    {$value}
  </td>
</tr>";
        }
        return $html . "</table></div>";
    }

    // ── Alert / highlight block ──────────────────────────────────────
    private static function alertBox(string $message, string $type = 'info'): string
    {
        $styles = [
            'info'    => ['bg' => '#ebf8ff', 'border' => '#3182ce', 'color' => '#2b6cb0', 'icon' => 'ℹ️'],
            'success' => ['bg' => '#f0fff4', 'border' => '#38a169', 'color' => '#276749', 'icon' => '✅'],
            'warning' => ['bg' => '#fffbeb', 'border' => '#d69e2e', 'color' => '#975a16', 'icon' => '⚠️'],
            'danger'  => ['bg' => '#fff5f5', 'border' => '#e53e3e', 'color' => '#c53030', 'icon' => '🚨'],
        ];
        $s = $styles[$type] ?? $styles['info'];
        return "<div style='background:{$s['bg']};border-left:4px solid {$s['border']};
                             border-radius:0 10px 10px 0;padding:14px 18px;
                             margin:20px 0;font-size:14px;color:{$s['color']};
                             font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Arial,sans-serif;'>
                  <strong>{$s['icon']} </strong>{$message}
                </div>";
    }

    // ── Badge pill ───────────────────────────────────────────────────
    private static function badge(string $label, string $bg, string $color = '#fff'): string
    {
        return "<span style='background:{$bg};color:{$color};padding:4px 14px;
                             border-radius:50px;font-size:12px;font-weight:700;
                             letter-spacing:.4px;display:inline-block;
                             font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Arial,sans-serif;'>
                  {$label}
                </span>";
    }

    // ── Darken a hex color by a percentage ───────────────────────────
    private static function darkenColor(string $hex, int $pct): string
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }
        $r = max(0, hexdec(substr($hex,0,2)) - round(255 * $pct / 100));
        $g = max(0, hexdec(substr($hex,2,2)) - round(255 * $pct / 100));
        $b = max(0, hexdec(substr($hex,4,2)) - round(255 * $pct / 100));
        return sprintf('#%02x%02x%02x', $r, $g, $b);
    }


    // ════════════════════════════════════════════════════════════════
    //  ORDER TRACKING OTP
    // ════════════════════════════════════════════════════════════════
    public static function sendTrackingOtp(string $toEmail, string $toName, string $orderNumber, string $otp): bool
    {
        try {
            $m          = self::mailer();
            $siteName   = htmlspecialchars(Helper::setting('site_name', 'Anything.lk'));
            $brandColor = Helper::setting('brand_color', '#E63946');
            $siteUrl    = Helper::url('');
            $m->WordWrap = 998;

            $m->addAddress($toEmail, $toName);
            $m->Subject = "&#128274; Your Order Tracking Code — {$orderNumber} | {$siteName}";

            // OTP digit boxes
            $digits = '';
            foreach (str_split($otp) as $d) {
                $digits .= "
<td style='padding:0 5px;'>
  <div style='width:52px;height:60px;line-height:60px;
       background:#ffffff;border:2px solid #e2e8f0;
       border-radius:12px;font-size:28px;font-weight:800;
       text-align:center;color:#1a202c;
       font-family:\"Courier New\",Courier,monospace;
       box-shadow:0 4px 12px rgba(0,0,0,0.06);'>{$d}</div>
</td>";
            }

            $bodyHtml = "
<!-- Hero icon -->
<div style='text-align:center;margin-bottom:20px;'>
  <div style='display:inline-block;width:72px;height:72px;line-height:72px;
       background:linear-gradient(135deg,{$brandColor},".self::darkenColor($brandColor,20).");
       border-radius:20px;font-size:32px;
       box-shadow:0 10px 30px rgba(0,0,0,0.15);'>🔐</div>
</div>

<h2 style='margin:0 0 8px;font-size:24px;font-weight:800;color:#1a202c;text-align:center;
           font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Arial,sans-serif;'>
  Verify Your Identity
</h2>
<p style='margin:0 0 24px;text-align:center;color:#718096;font-size:15px;
           font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Arial,sans-serif;'>
  Hi <strong style='color:#2d3748;'>" . htmlspecialchars($toName) . "</strong>,
  use this code to track order
  <strong style='color:{$brandColor};'>{$orderNumber}</strong>.
</p>

<!-- OTP box strip -->
<div style='background:linear-gradient(135deg,#f7fafc,#edf2f7);
     border-radius:16px;padding:28px 20px;text-align:center;
     margin:0 0 24px;border:1px solid #e2e8f0;'>
  <p style='margin:0 0 18px;font-size:12px;font-weight:700;letter-spacing:1.5px;
             color:#a0aec0;text-transform:uppercase;
             font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Arial,sans-serif;'>
    Your One-Time Code
  </p>
  <table role='presentation' cellpadding='0' cellspacing='0' style='margin:0 auto;'>
    <tr>{$digits}</tr>
  </table>
  <p style='margin:16px 0 0;font-size:12px;color:#a0aec0;
             font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Arial,sans-serif;'>
    &#9201; Expires in <strong style='color:#718096;'>10 minutes</strong>
  </p>
</div>

" . self::alertBox("If you didn't request this code, you can safely ignore this email. Your account is secure.", 'info') . "

" . self::btn($siteUrl . '/order-tracking', 'Track My Order', $brandColor, '📦') . "
";

            $m->Body    = wordwrap(self::wrap("Order Tracking Verification", $bodyHtml), 998, "\r\n", true);
            $m->AltBody = "Your {$siteName} tracking code for order {$orderNumber}: {$otp} (expires in 10 min).";

            return $m->send();

        } catch (\Exception $e) {
            error_log('Mail error (tracking OTP): ' . $e->getMessage());
            return false;
        }
    }


    // ════════════════════════════════════════════════════════════════
    //  ORDER CONFIRMATION
    // ════════════════════════════════════════════════════════════════
    public static function sendOrderConfirmation(array $order, array $items): bool
    {
        try {
            $m = self::mailer();
            $m->WordWrap = 998;

            $email = $order['guest_email'] ?? '';
            if (!$email && !empty($order['user_id'])) {
                $db   = new Db();
                $user = $db->selectOne("SELECT email,full_name FROM users WHERE id={$order['user_id']}");
                $email = $user['email'] ?? '';
            }
            if (!$email) return false;

            $siteName   = Helper::setting('site_name', 'Anything.lk');
            $brandColor = Helper::setting('brand_color', '#E63946');
            $currency   = Helper::setting('currency', 'LKR');
            $siteUrl    = Helper::url('');

            $m->addAddress($email);
            $m->Subject = "&#127881; Order Confirmed — {$order['order_number']} | {$siteName}";

            // ── Items rows ──────────────────────────────────────────
            $rows = '';
            foreach ($items as $i => $it) {
                $bg      = ($i % 2 === 0) ? '#ffffff' : '#f7fafc';
                $varNote = $it['variation_name']
                    ? "<br><span style='color:#a0aec0;font-size:12px;font-style:italic;'>"
                      . htmlspecialchars($it['variation_name']) . "</span>"
                    : '';
                $rows .= "
<tr style='background:{$bg};'>
  <td style='padding:13px 14px;font-size:14px;color:#2d3748;
             font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Arial,sans-serif;'>
    🛍️ " . htmlspecialchars($it['product_name']) . $varNote . "
  </td>
  <td style='padding:13px 10px;text-align:center;font-size:14px;color:#4a5568;
             font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Arial,sans-serif;'>
    <span style='background:#edf2f7;padding:3px 10px;border-radius:20px;font-weight:600;'>
      {$it['quantity']}
    </span>
  </td>
  <td style='padding:13px 14px;text-align:right;font-size:14px;font-weight:700;
             color:#1a202c;
             font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Arial,sans-serif;'>
    {$currency} " . number_format($it['total_price'], 2) . "
  </td>
</tr>";
            }

            $total    = number_format($order['total_amount'], 2);
            $shipping = number_format($order['shipping_amount'], 2);
            $discount = $order['discount_amount'] > 0 ? number_format($order['discount_amount'], 2) : null;
            $paymentDisplay = ucwords(str_replace(['_','-'], ' ', $order['payment_method']));

            $bodyHtml = "
<!-- Hero -->
<div style='text-align:center;margin-bottom:28px;'>
  <div style='display:inline-block;width:80px;height:80px;line-height:80px;
       background:linear-gradient(135deg,#48bb78,#276749);
       border-radius:50%;font-size:36px;
       box-shadow:0 12px 32px rgba(72,187,120,0.3);'>✅</div>
  <h2 style='margin:16px 0 6px;font-size:26px;font-weight:800;color:#1a202c;
             font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Arial,sans-serif;'>
    Order Confirmed!
  </h2>
  <p style='margin:0;color:#718096;font-size:15px;
             font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Arial,sans-serif;'>
    Hi <strong style='color:#2d3748;'>" . htmlspecialchars($order['shipping_name']) . "</strong>,
    your order is being prepared right now.
  </p>
</div>

" . self::infoBox([
    ['📋 &nbsp;Order Number', "<span style='color:{$brandColor};font-weight:800;'>{$order['order_number']}</span>"],
    ['💳 &nbsp;Payment',      $paymentDisplay],
    ['📍 &nbsp;Ship To',      htmlspecialchars($order['shipping_address'] . ', ' . $order['shipping_city'])],
], 'Order Summary') . "

<!-- Items table -->
<div style='border-radius:14px;overflow:hidden;border:1px solid #e2e8f0;margin:24px 0;'>
  <table width='100%' cellpadding='0' cellspacing='0'>
    <tr style='background:{$brandColor};'>
      <th align='left'
        style='padding:12px 14px;color:#fff;font-size:12px;font-weight:700;
               letter-spacing:.8px;text-transform:uppercase;
               font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Arial,sans-serif;'>
        Product
      </th>
      <th align='center'
        style='padding:12px 10px;color:#fff;font-size:12px;font-weight:700;
               letter-spacing:.8px;text-transform:uppercase;
               font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Arial,sans-serif;'>
        Qty
      </th>
      <th align='right'
        style='padding:12px 14px;color:#fff;font-size:12px;font-weight:700;
               letter-spacing:.8px;text-transform:uppercase;
               font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Arial,sans-serif;'>
        Total
      </th>
    </tr>
    {$rows}
  </table>
</div>

<!-- Totals -->
<div style='background:#f7fafc;border-radius:14px;padding:18px 22px;
     border:1px solid #e2e8f0;'>
  <table width='100%' cellpadding='0' cellspacing='0'>
" . ($discount ? "
    <tr>
      <td style='padding:8px 0;font-size:14px;color:#718096;
                 font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Arial,sans-serif;'>
        🏷️ &nbsp;Discount
      </td>
      <td style='padding:8px 0;text-align:right;font-size:14px;font-weight:700;
                 color:#38a169;
                 font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Arial,sans-serif;'>
        −{$currency} {$discount}
      </td>
    </tr>" : '') . "
    <tr>
      <td style='padding:8px 0;font-size:14px;color:#718096;
                 font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Arial,sans-serif;'>
        🚚 &nbsp;Shipping
      </td>
      <td style='padding:8px 0;text-align:right;font-size:14px;color:#4a5568;
                 font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Arial,sans-serif;'>
        {$currency} {$shipping}
      </td>
    </tr>
    <tr>
      <td style='padding:12px 0 4px;border-top:2px solid #e2e8f0;
                 font-size:16px;font-weight:800;color:#1a202c;
                 font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Arial,sans-serif;'>
        💰 &nbsp;Grand Total
      </td>
      <td style='padding:12px 0 4px;border-top:2px solid #e2e8f0;text-align:right;
                 font-size:18px;font-weight:800;color:{$brandColor};
                 font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Arial,sans-serif;'>
        {$currency} {$total}
      </td>
    </tr>
  </table>
</div>

<p style='text-align:center;margin:24px 0 6px;font-size:14px;color:#718096;
           font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Arial,sans-serif;'>
  We'll send you a notification when your order ships. 📬
</p>

" . self::btn($siteUrl . '/account/orders', 'Track My Order', $brandColor, '📦') . "
";

            $m->Body    = wordwrap(self::wrap("Order Confirmed 🎉", $bodyHtml), 998, "\r\n", true);
            $m->AltBody = "Order {$order['order_number']} confirmed. Total: {$currency} {$total}.";
            return $m->send();

        } catch (\Exception $e) {
            error_log("Mail error (order confirm): " . $e->getMessage());
            return false;
        }
    }


    // ════════════════════════════════════════════════════════════════
    //  ORDER STATUS UPDATE
    // ════════════════════════════════════════════════════════════════
    public static function sendOrderStatusUpdate(array $order, string $newStatus, string $notes = ''): bool
    {
        try {
            $m  = self::mailer();
            $db = new Db();

            $user  = $order['user_id']
                ? $db->selectOne("SELECT email,full_name FROM users WHERE id={$order['user_id']}")
                : null;
            $email = $user['email'] ?? $order['guest_email'] ?? '';
            if (!$email) return false;

            $siteName   = Helper::setting('site_name', 'Anything.lk');
            $brandColor = Helper::setting('brand_color', '#E63946');
            $siteUrl    = Helper::url('');

            $statusConfig = [
                'confirmed'  => [
                    'msg'   => 'Your order has been confirmed and is now being prepared.',
                    'icon'  => '✅', 'color' => '#3182ce', 'alert' => 'success',
                    'badge' => 'Confirmed',
                ],
                'processing' => [
                    'msg'   => 'Our team is actively working on packing your order.',
                    'icon'  => '⚙️', 'color' => '#d69e2e', 'alert' => 'info',
                    'badge' => 'Processing',
                ],
                'shipped'    => [
                    'msg'   => 'Great news — your order is on its way to you!',
                    'icon'  => '🚚', 'color' => '#805ad5', 'alert' => 'info',
                    'badge' => 'Shipped',
                ],
                'delivered'  => [
                    'msg'   => 'Your order has been delivered. We hope you love it!',
                    'icon'  => '🏠', 'color' => '#38a169', 'alert' => 'success',
                    'badge' => 'Delivered',
                ],
                'cancelled'  => [
                    'msg'   => 'Your order has been cancelled. Contact us if you have questions.',
                    'icon'  => '❌', 'color' => '#e53e3e', 'alert' => 'danger',
                    'badge' => 'Cancelled',
                ],
                'refunded'   => [
                    'msg'   => 'Your refund has been processed and is on its way.',
                    'icon'  => '💰', 'color' => '#0891b2', 'alert' => 'info',
                    'badge' => 'Refunded',
                ],
            ];

            $cfg    = $statusConfig[$newStatus] ?? [
                'msg'   => 'Your order status has been updated to: ' . ucfirst($newStatus),
                'icon'  => '📦', 'color' => $brandColor, 'alert' => 'info',
                'badge' => ucfirst($newStatus),
            ];

            $m->addAddress($email);
            $m->Subject = "{$cfg['icon']} Order {$order['order_number']} — {$cfg['badge']} | {$siteName}";

            $noteBlock = $notes
                ? self::alertBox('<strong>Note:</strong> ' . htmlspecialchars($notes), 'warning')
                : '';

            $trackBlock = $order['tracking_number']
                ? "<div style='background:#f7fafc;border-radius:12px;padding:14px 18px;
                               margin:16px 0;border:1px solid #e2e8f0;text-align:center;'>
                     <p style='margin:0;font-size:13px;color:#718096;
                                font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Arial,sans-serif;'>
                       📦 Tracking Number
                     </p>
                     <p style='margin:4px 0 0;font-size:17px;font-weight:800;color:#1a202c;
                                letter-spacing:1px;
                                font-family:\"Courier New\",Courier,monospace;'>
                       " . htmlspecialchars($order['tracking_number']) . "
                     </p>
                   </div>"
                : '';

            $bodyHtml = "
<!-- Hero icon -->
<div style='text-align:center;margin-bottom:24px;'>
  <div style='display:inline-block;width:76px;height:76px;line-height:76px;
       background:linear-gradient(135deg,{$cfg['color']},".self::darkenColor($cfg['color'],20).");
       border-radius:50%;font-size:34px;
       box-shadow:0 12px 32px rgba(0,0,0,0.18);'>{$cfg['icon']}</div>
  <h2 style='margin:16px 0 6px;font-size:24px;font-weight:800;color:#1a202c;
             font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Arial,sans-serif;'>
    Order Update
  </h2>
  <p style='margin:0;color:#718096;font-size:15px;
             font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Arial,sans-serif;'>
    Hi <strong style='color:#2d3748;'>" . htmlspecialchars($order['shipping_name']) . "</strong>,
    {$cfg['msg']}
  </p>
</div>

" . self::infoBox([
    ['📋 &nbsp;Order',  $order['order_number']],
    ['📊 &nbsp;Status', self::badge($cfg['badge'], $cfg['color'])],
], 'Order Details') . "

{$noteBlock}
{$trackBlock}

" . self::btn($siteUrl . 'account/orders', 'View Order Details', $cfg['color'], '🔍') . "
";

            $m->Body    = self::wrap("{$cfg['icon']} Order Update", $bodyHtml);
            $m->AltBody = "Order {$order['order_number']} is now: " . ucfirst($newStatus);
            return $m->send();

        } catch (\Exception $e) {
            error_log("Mail error (status update): " . $e->getMessage());
            return false;
        }
    }


    // ════════════════════════════════════════════════════════════════
    //  WELCOME EMAIL
    // ════════════════════════════════════════════════════════════════
    public static function sendWelcome(string $email, string $name): bool
    {
        try {
            $m = self::mailer();
            $siteName   = Helper::setting('site_name',   'Anything.lk');
            $brandColor = Helper::setting('brand_color', '#E63946');
            $siteUrl    = Helper::url('');

            $m->addAddress($email);
            $m->Subject = "Welcome to {$siteName}! 🎉";

            $features = [
                ['🛍️', 'Browse Thousands of Products',  'Discover everything from electronics to fashion, all in one place.'],
                ['❤️', 'Save to Your Wishlist',         'Bookmark your favourite items and never lose track of them.'],
                ['📦', 'Real-Time Order Tracking',       'Stay updated every step of the way, right from confirmation to delivery.'],
                ['💬', 'Leave Honest Reviews',           'Help the community by sharing your experience after a purchase.'],
            ];

            $featureRows = '';
            foreach ($features as [$ico, $title, $desc]) {
                $featureRows .= "
<tr>
  <td style='padding:0 0 14px;'>
    <table width='100%' cellpadding='0' cellspacing='0'>
      <tr>
        <td width='52' valign='top' style='padding-top:2px;'>
          <div style='width:44px;height:44px;line-height:44px;border-radius:12px;
               background:#f7fafc;text-align:center;font-size:20px;
               border:1px solid #e2e8f0;'>{$ico}</div>
        </td>
        <td style='padding-left:14px;'>
          <p style='margin:0 0 3px;font-size:14px;font-weight:700;color:#1a202c;
                     font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Arial,sans-serif;'>
            {$title}
          </p>
          <p style='margin:0;font-size:13px;color:#718096;line-height:1.6;
                     font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Arial,sans-serif;'>
            {$desc}
          </p>
        </td>
      </tr>
    </table>
  </td>
</tr>";
            }

            $bodyHtml = "
<!-- Hero -->
<div style='text-align:center;background:linear-gradient(135deg,{$brandColor},".self::darkenColor($brandColor,20).");
     border-radius:16px;padding:36px 24px;margin-bottom:30px;'>
  <div style='font-size:48px;margin-bottom:10px;'>🎉</div>
  <h2 style='margin:0 0 8px;font-size:26px;font-weight:800;color:#ffffff;
             font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Arial,sans-serif;'>
    Welcome, " . htmlspecialchars($name) . "!
  </h2>
  <p style='margin:0;font-size:15px;color:rgba(255,255,255,0.85);
             font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Arial,sans-serif;'>
    Your account is ready. Let's get shopping!
  </p>
</div>

<p style='margin:0 0 20px;font-size:15px;color:#4a5568;
           font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Arial,sans-serif;'>
  Here's everything you can do with your new account:
</p>

<table width='100%' cellpadding='0' cellspacing='0'>
  {$featureRows}
</table>

<!-- Promo code card -->
<div style='background:linear-gradient(135deg,#1a202c,#2d3748);border-radius:16px;
     padding:24px;text-align:center;margin:24px 0;'>
  <p style='margin:0 0 6px;font-size:12px;font-weight:700;letter-spacing:1.5px;
             color:#a0aec0;text-transform:uppercase;
             font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Arial,sans-serif;'>
    Your Welcome Gift 🎁
  </p>
  <p style='margin:0 0 12px;font-size:13px;color:#718096;
             font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Arial,sans-serif;'>
    Use this code for 10% off your first order
  </p>
  <div style='display:inline-block;background:rgba(255,255,255,0.1);
       border:2px dashed rgba(255,255,255,0.3);border-radius:10px;padding:10px 28px;'>
    <span style='font-size:24px;font-weight:800;color:#ffffff;
                 letter-spacing:3px;font-family:\"Courier New\",Courier,monospace;'>
      WELCOME10
    </span>
  </div>
</div>

" . self::btn($siteUrl, 'Start Shopping Now', $brandColor, '🛒') . "
";

            $m->Body    = self::wrap("Welcome to {$siteName}", $bodyHtml);
            $m->AltBody = "Welcome to {$siteName}, {$name}! Use code WELCOME10 for 10% off your first order.";
            return $m->send();

        } catch (\Exception $e) {
            error_log("Mail error (welcome): " . $e->getMessage());
            return false;
        }
    }


    // ════════════════════════════════════════════════════════════════
    //  PASSWORD RESET
    // ════════════════════════════════════════════════════════════════
    public static function sendPasswordReset(string $email, string $name, string $token): bool
    {
        try {
            $m = self::mailer();
            $siteName = Helper::setting('site_name', 'Anything.lk');
            $resetUrl = Helper::url("reset-password?token={$token}");
            $safeName = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
            $m->WordWrap = 78;

            $m->addAddress($email);
            $m->Subject = "Reset Your {$siteName} Password 🔐";

            $bodyHtml = "
<!-- Hero -->
<div style='text-align:center;margin-bottom:28px;'>
  <div style='display:inline-block;width:76px;height:76px;line-height:76px;
       background:linear-gradient(135deg,#e53e3e,#c53030);
       border-radius:50%;font-size:34px;
       box-shadow:0 12px 32px rgba(229,62,62,0.3);'>🔐</div>
  <h2 style='margin:16px 0 6px;font-size:24px;font-weight:800;color:#1a202c;
             font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Arial,sans-serif;'>
    Password Reset Request
  </h2>
  <p style='margin:0;color:#718096;font-size:15px;
             font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Arial,sans-serif;'>
    Hi <strong style='color:#2d3748;'>{$safeName}</strong>,
    we received a request to reset your password.
  </p>
</div>

<p style='margin:0 0 8px;font-size:15px;color:#4a5568;
           font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Arial,sans-serif;'>
  Click the button below to set a new password for your <strong>{$siteName}</strong> account.
  This link will expire in <strong>1 hour</strong>.
</p>

" . self::alertBox('For your security, never share this link with anyone.', 'warning') . "

" . self::btn($resetUrl, 'Reset My Password', '#e53e3e', '🔑') . "

<div style='background:#f7fafc;border-radius:12px;padding:16px 18px;
     border:1px solid #e2e8f0;margin:20px 0;'>
  <p style='margin:0 0 6px;font-size:12px;font-weight:700;color:#a0aec0;
             letter-spacing:.8px;text-transform:uppercase;
             font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Arial,sans-serif;'>
    Or copy this link into your browser:
  </p>
  <p style='margin:0;font-size:12px;color:#718096;word-break:break-all;line-height:1.6;
             font-family:\"Courier New\",Courier,monospace;'>
    <a href='{$resetUrl}' style='color:#718096;text-decoration:underline;'>{$resetUrl}</a>
  </p>
</div>

" . self::alertBox("Didn't request a password reset? No action needed — your account is safe.", 'info') . "
";

            $body = preg_replace('/(.{1,900})(\s|$)/u', "$1\r\n", self::wrap("Password Reset", $bodyHtml));
            $m->Body = $body;
            $m->AltBody = "Password Reset\n\nHi {$name},\nReset your password: {$resetUrl}\nExpires in 1 hour.";

            return $m->send();

        } catch (\Exception $e) {
            error_log("Mail error (password reset): " . $e->getMessage());
            return false;
        }
    }


    // ════════════════════════════════════════════════════════════════
    //  LOW STOCK ALERT (Admin)
    // ════════════════════════════════════════════════════════════════
    public static function sendLowStockAlert(array $items): bool
    {
        try {
            $config    = parse_ini_file(ROOT . '/config.ini');
            $admins = array_unique(array_filter([
                $cfg['admin_email'] ?? null,
                $cfg['mail_user'] ?? null,
                Helper::setting('support_email', '')
            ]));
            
            if (empty($admins)) return false;

            $m = self::mailer();
            $siteName   = Helper::setting('site_name', 'Anything.lk');
            $brandColor = Helper::setting('brand_color', '#E63946');
            $adminUrl   = Helper::url('admin/stock');

            foreach ($admins as $admin) {
                $m->addAddress($admin);
            }
            $m->Subject = "⚠️ Low Stock Alert — " . count($items) . " products need restocking | {$siteName}";

            $rows = '';
            foreach ($items as $i => $item) {
                $bg    = ($i % 2 === 0) ? '#ffffff' : '#fffbeb';
                $level = $item['quantity'] <= 2 ? '#e53e3e' : '#d69e2e';
                $rows .= "
<tr style='background:{$bg};'>
  <td style='padding:13px 14px;font-size:14px;color:#2d3748;
             font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Arial,sans-serif;'>
    📦 " . htmlspecialchars($item['product_name']) . "
  </td>
  <td style='padding:13px 10px;text-align:center;'>
    <span style='background:{$level};color:#fff;padding:4px 12px;border-radius:20px;
                 font-size:13px;font-weight:800;
                 font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Arial,sans-serif;'>
      {$item['quantity']} left
    </span>
  </td>
  <td style='padding:13px 10px;text-align:center;font-size:14px;color:#718096;
             font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Arial,sans-serif;'>
    {$item['low_stock_threshold']}
  </td>
  <td style='padding:13px 14px;text-align:center;font-size:13px;color:#4a5568;
             font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Arial,sans-serif;'>
    🏭 " . htmlspecialchars($item['warehouse']) . "
  </td>
</tr>";
            }

            $bodyHtml = "
<!-- Hero -->
<div style='text-align:center;background:linear-gradient(135deg,#e53e3e,#9b2c2c);
     border-radius:16px;padding:32px 24px;margin-bottom:28px;'>
  <div style='font-size:44px;margin-bottom:10px;'>⚠️</div>
  <h2 style='margin:0 0 6px;font-size:24px;font-weight:800;color:#ffffff;
             font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Arial,sans-serif;'>
    Low Stock Alert
  </h2>
  <p style='margin:0;font-size:15px;color:rgba(255,255,255,0.85);
             font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Arial,sans-serif;'>
    " . count($items) . " product(s) need immediate restocking
  </p>
</div>

<!-- Table -->
<div style='border-radius:14px;overflow:hidden;border:1px solid #fed7d7;margin:0 0 24px;'>
  <table width='100%' cellpadding='0' cellspacing='0'>
    <tr style='background:#e53e3e;'>
      <th align='left'
        style='padding:12px 14px;color:#fff;font-size:12px;font-weight:700;
               letter-spacing:.8px;text-transform:uppercase;
               font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Arial,sans-serif;'>
        Product
      </th>
      <th align='center'
        style='padding:12px 10px;color:#fff;font-size:12px;font-weight:700;
               letter-spacing:.8px;text-transform:uppercase;
               font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Arial,sans-serif;'>
        Current Stock
      </th>
      <th align='center'
        style='padding:12px 10px;color:#fff;font-size:12px;font-weight:700;
               letter-spacing:.8px;text-transform:uppercase;
               font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Arial,sans-serif;'>
        Threshold
      </th>
      <th align='center'
        style='padding:12px 14px;color:#fff;font-size:12px;font-weight:700;
               letter-spacing:.8px;text-transform:uppercase;
               font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Arial,sans-serif;'>
        Warehouse
      </th>
    </tr>
    {$rows}
  </table>
</div>

" . self::alertBox('Restock soon to avoid losing sales. Items at 0 are hidden from the store automatically.', 'warning') . "

" . self::btn($adminUrl, 'Open Stock Manager', '#e53e3e', '📊') . "
";

            $m->Body    = self::wrap("⚠️ Low Stock Alert", $bodyHtml);
            $m->AltBody = count($items) . " products are low on stock at {$siteName}. Login to restock.";
            return $m->send();

        } catch (\Exception $e) {
            error_log("Mail error (low stock): " . $e->getMessage());
            return false;
        }
    }


    // ════════════════════════════════════════════════════════════════
    //  CONTACT FORM ACKNOWLEDGEMENT
    // ════════════════════════════════════════════════════════════════
    public static function sendContactAck(string $email, string $name, string $subject): bool
    {
        try {
            $m = self::mailer();
            $siteName     = Helper::setting('site_name',     'Anything.lk');
            $brandColor   = Helper::setting('brand_color', '#E63946');
            $supportEmail = Helper::setting('support_email', '');
            $supportPhone = Helper::setting('support_phone', '');
            $siteUrl      = Helper::url('');

            $contactParts = [];
            if ($supportEmail) $contactParts[] = '<a href="mailto:' . htmlspecialchars($supportEmail) . '" style="color:' . $brandColor . ';text-decoration:none;">'
                                                . htmlspecialchars($supportEmail) . '</a>';
            if ($supportPhone) $contactParts[] = '<a href="tel:' . preg_replace('/[^+\d]/', '', $supportPhone) . '" style="color:' . $brandColor . ';text-decoration:none;">'
                                                . htmlspecialchars($supportPhone) . '</a>';
            $contactNote = $contactParts
                ? "<div style='margin-top:10px;font-size:13px;color:#718096;
                               font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Arial,sans-serif;'>
                     You can also reach us at: " . implode(' or ', $contactParts) . "
                   </div>"
                : '';

            $m->addAddress($email);
            $m->Subject = "📬 We received your message — {$siteName}";

            $bodyHtml = "
<!-- Hero -->
<div style='text-align:center;margin-bottom:28px;'>
  <div style='display:inline-block;width:76px;height:76px;line-height:76px;
       background:linear-gradient(135deg,#4299e1,#2b6cb0);
       border-radius:50%;font-size:34px;
       box-shadow:0 12px 32px rgba(66,153,225,0.3);'>📬</div>
  <h2 style='margin:16px 0 6px;font-size:24px;font-weight:800;color:#1a202c;
             font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Arial,sans-serif;'>
    We Got Your Message!
  </h2>
  <p style='margin:0;color:#718096;font-size:15px;
             font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Arial,sans-serif;'>
    Hi <strong style='color:#2d3748;'>" . htmlspecialchars($name) . "</strong>, thanks for reaching out!
  </p>
</div>

" . self::infoBox([
    ['📝 &nbsp;Subject',       htmlspecialchars($subject)],
    ['⏱️ &nbsp;Response Time', 'Within 24 business hours'],
    ['📧 &nbsp;Sent To',       htmlspecialchars($siteName) . ' Support'],
]) . "

<p style='margin:20px 0 14px;font-size:15px;color:#4a5568;
           font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Arial,sans-serif;'>
  Our support team has received your enquiry and will get back to you as soon as possible.
  We're available Monday to Saturday, 9 AM – 6 PM.
</p>

{$contactNote}

" . self::btn($siteUrl, 'Continue Shopping', $brandColor, '🛍️') . "
";

            $m->Body    = self::wrap("Message Received 📬", $bodyHtml);
            $m->AltBody = "We received your message at {$siteName} and will respond within 24 business hours.";
            return $m->send();

        } catch (\Exception $e) {
            error_log("Mail error (contact ack): " . $e->getMessage());
            return false;
        }
    }
}