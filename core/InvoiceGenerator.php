<?php
// ============================================================
// INVOICE GENERATOR — Pure PHP, generates PDF via HTML+CSS
// Printed through browser or saved as PDF via browser dialog.
// All branding is loaded dynamically from the settings table.
// ============================================================
class InvoiceGenerator
{
    private array  $order;
    private array  $items;
    private string $type; // 'invoice' | 'receipt'

    public function __construct(array $order, array $items, string $type = 'invoice')
    {
        $this->order = $order;
        $this->items = $items;
        $this->type  = $type;
    }

    // ── Load all branding/company data from the settings table ─────
    private static function branding(): array
    {
        return [
            'name'         => Helper::setting('site_name',         'Anything.lk'),
            'tagline'      => Helper::setting('site_tagline',       ''),
            'logo'         => Helper::setting('site_logo',          ''),
            'address'      => Helper::setting('site_address',       ''),
            'phone'        => Helper::setting('support_phone',      Helper::setting('site_phone', '')),
            'email'        => Helper::setting('support_email',      Helper::setting('site_email', '')),
            'website'      => Helper::setting('site_website',       ''),
            'currency'     => Helper::setting('currency',           'LKR'),
            'bank_name'    => Helper::setting('bank_name',          ''),
            'bank_account' => Helper::setting('bank_account',       ''),
            'bank_branch'  => Helper::setting('bank_branch',        ''),
            'footer_note'  => Helper::setting('invoice_footer_note', ''),
            'fb'           => Helper::setting('social_facebook',    ''),
            'ig'           => Helper::setting('social_instagram',   ''),
            'tw'           => Helper::setting('social_twitter',     ''),
            'yt'           => Helper::setting('social_youtube',     ''),
        ];
    }

    // ── Build logo HTML block (img or text fallback) ────────────────
    private static function logoHtml(array $b): string
    {
        if ($b['logo']) {
            $url = Helper::url('uploads/logo/' . $b['logo']);
            return '<img src="' . htmlspecialchars($url) . '" alt="' . htmlspecialchars($b['name']) . '"'
                 . ' style="max-height:62px;max-width:220px;object-fit:contain;display:block;">';
        }
        $parts = explode('.', $b['name'], 2);
        $html  = '<span style="font-size:26px;font-weight:900;color:#E63946;letter-spacing:-0.5px;">'
               . htmlspecialchars($parts[0]);
        if (isset($parts[1])) {
            $html .= '<span style="color:#555;">.' . htmlspecialchars($parts[1]) . '</span>';
        }
        return $html . '</span>';
    }

    // ── Build company contact lines below the logo ──────────────────
    private static function contactHtml(array $b): string
    {
        $parts = [];
        if ($b['address']) $parts[] = nl2br(htmlspecialchars($b['address']));
        if ($b['phone'])   $parts[] = htmlspecialchars($b['phone']);
        if ($b['email'])   $parts[] = htmlspecialchars($b['email']);
        if ($b['website']) $parts[] = htmlspecialchars($b['website']);
        return $parts ? implode('<br>', $parts) : '';
    }

    // ── Build bank details line ─────────────────────────────────────
    private static function bankHtml(array $b): string
    {
        $parts = [];
        if ($b['bank_name'])    $parts[] = 'Bank: '   . htmlspecialchars($b['bank_name']);
        if ($b['bank_account']) $parts[] = 'Acc: '    . htmlspecialchars($b['bank_account']);
        if ($b['bank_branch'])  $parts[] = 'Branch: ' . htmlspecialchars($b['bank_branch']);
        return $parts ? '<p>' . implode(' &middot; ', $parts) . '</p>' : '';
    }

    // ── Build social icon links (text-based, print-safe) ───────────
    private static function socialHtml(array $b): string
    {
        $defs = [
            [$b['fb'], 'f',  '#1877f2'],
            [$b['ig'], 'ig', '#e1306c'],
            [$b['tw'], 't',  '#1d9bf0'],
            [$b['yt'], 'yt', '#ff0000'],
        ];
        $html = '';
        foreach ($defs as [$url, $letter, $color]) {
            if (!$url) continue;
            $html .= '<a href="' . htmlspecialchars($url) . '" style="'
                   . 'display:inline-block;width:22px;height:22px;background:' . $color . ';'
                   . 'border-radius:4px;color:#fff;text-align:center;line-height:22px;'
                   . 'font-size:10px;font-weight:700;text-decoration:none;margin-right:4px;">'
                   . $letter . '</a>';
        }
        return $html;
    }

    // ── Full page CSS ───────────────────────────────────────────────
    private static function css(): string
    {
        return '
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: "Segoe UI", Arial, sans-serif; font-size: 14px; color: #333; background: #fff; }
.invoice-wrap { max-width: 800px; margin: 0 auto; padding: 40px; }

/* Header */
.inv-header {
    display: flex; justify-content: space-between; align-items: flex-start;
    margin-bottom: 32px; padding-bottom: 22px;
    border-bottom: 3px solid #E63946;
}
.inv-company { max-width: 56%; }
.inv-company-contact { font-size: 12px; color: #666; margin-top: 9px; line-height: 1.85; }
.inv-title { text-align: right; }
.inv-title h2 { font-size: 34px; color: #E63946; font-weight: 900; letter-spacing: 4px; }
.inv-title p  { font-size: 13px; color: #555; margin-top: 5px; line-height: 1.65; }

/* Info grid */
.info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 26px; }
.info-box  { background: #f8f9fa; border-radius: 8px; padding: 15px 18px; border-left: 3px solid #E63946; }
.info-box h4 {
    font-size: 10px; text-transform: uppercase; letter-spacing: 1.5px;
    color: #E63946; margin-bottom: 9px; font-weight: 700;
}
.info-box p { line-height: 1.8; font-size: 13px; }

/* Items table */
table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
thead th {
    background: #E63946; color: #fff; padding: 11px 14px;
    text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: 0.6px;
}
tbody td { padding: 10px 14px; border-bottom: 1px solid #eee; font-size: 13px; vertical-align: middle; }
tr.even td { background: #fafafa; }
tfoot td  { padding: 9px 14px; font-size: 13px; color: #555; }
tfoot tr.total-row td {
    font-size: 15px; font-weight: 700; color: #E63946;
    border-top: 2px solid #E63946; padding-top: 12px;
}

/* Footer */
.inv-footer { margin-top: 40px; padding-top: 18px; border-top: 1px solid #e0e0e0; }
.inv-footer-grid { display: flex; justify-content: space-between; align-items: flex-end; gap: 20px; }
.inv-footer p  { font-size: 12px; color: #888; line-height: 1.75; margin-bottom: 4px; }
.sig-block {
    width: 160px; border-top: 1px solid #aaa; margin-top: 38px; margin-left: auto;
    padding-top: 6px; font-size: 11px; color: #888; text-align: center;
}
.inv-thanks {
    text-align: center; margin-top: 26px; padding: 13px 18px;
    background: #fff8f8; border-radius: 8px;
    font-size: 14px; font-weight: 700; color: #E63946;
    border: 1px solid #fde0e2;
}

/* Watermark */
.watermark {
    position: fixed; top: 50%; left: 50%;
    transform: translate(-50%,-50%) rotate(-35deg);
    font-size: 96px; font-weight: 900;
    color: rgba(230,57,70,.05); z-index: 0;
    pointer-events: none; letter-spacing: 8px;
    white-space: nowrap;
}

@media print {
    body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .no-print { display: none !important; }
}
';
    }

    // ── Generate full HTML invoice ──────────────────────────────────
    public function toHTML(): string
    {
        $o         = $this->order;
        $items     = $this->items;
        $isReceipt = ($this->type === 'receipt');
        $date      = date('F j, Y', strtotime($o['created_at']));
        $dueDate   = date('F j, Y', strtotime($o['created_at'] . ' +7 days'));
        $b         = self::branding();
        $cur       = htmlspecialchars($b['currency']);

        // ── Item rows ───────────────────────────────────────────────
        $rows = '';
        foreach ($items as $i => $item) {
            $varName = $item['variation_name']
                ? ' <small style="color:#888;">(' . htmlspecialchars($item['variation_name']) . ')</small>' : '';
            $cls     = ($i % 2 === 0) ? 'even' : 'odd';
            $rows .= '<tr class="' . $cls . '">';
            $rows .= '<td>' . htmlspecialchars($item['product_name']) . $varName . '</td>';
            $rows .= '<td style="text-align:center;">' . (int)$item['quantity'] . '</td>';
            $rows .= '<td style="text-align:right;">' . $cur . ' ' . number_format($item['unit_price'], 2) . '</td>';
            $rows .= '<td style="text-align:right;"><strong>' . $cur . ' ' . number_format($item['total_price'], 2) . '</strong></td>';
            $rows .= '</tr>';
        }

        // ── Discount row ────────────────────────────────────────────
        $discountRow = '';
        if ($o['discount_amount'] > 0) {
            $couponLabel = $o['coupon_code'] ? ' (' . htmlspecialchars($o['coupon_code']) . ')' : '';
            $discountRow = '<tr><td colspan="3" style="text-align:right;">Discount' . $couponLabel . '</td>'
                         . '<td style="text-align:right;color:#27ae60;">-' . $cur . ' ' . number_format($o['discount_amount'], 2) . '</td></tr>';
        }

        $shippingText = $o['shipping_amount'] > 0
            ? $cur . ' ' . number_format($o['shipping_amount'], 2)
            : 'Free';

        $stateText    = $o['shipping_state'] ? ', ' . htmlspecialchars($o['shipping_state']) : '';
        $notesHtml    = $o['notes']
            ? '<div style="background:#fff8e1;border-left:4px solid #ffb83c;padding:12px 16px;border-radius:4px;margin-bottom:22px;">'
              . '<strong>Notes:</strong> ' . htmlspecialchars($o['notes']) . '</div>'
            : '';
        $trackingHtml = $o['tracking_number']
            ? '<p style="margin-bottom:12px;">Tracking: <strong>' . htmlspecialchars($o['tracking_number']) . '</strong></p>'
            : '';

        $payMethod   = ucfirst(str_replace('_', ' ', $o['payment_method']));
        $shipMethod  = ucfirst($o['shipping_method'] ?? 'Standard');
        $orderStatus = ucfirst($o['status']);

        // ── Payment status badge ────────────────────────────────────
        if ($isReceipt) {
            $badgeHtml = '<p style="margin-top:9px;"><span style="display:inline-block;padding:4px 14px;'
                       . 'border-radius:20px;font-size:12px;font-weight:700;background:#d1e7dd;color:#0f5132;">&#10003; PAID</span></p>';
            if (!empty($o['payment_ref'])) {
                $badgeHtml .= '<p style="font-size:12px;color:#666;margin-top:4px;">Ref: ' . htmlspecialchars($o['payment_ref']) . '</p>';
            }
        } else {
            $payBg    = $o['payment_status'] === 'paid' ? '#d1e7dd' : '#fff3cd';
            $payColor = $o['payment_status'] === 'paid' ? '#0f5132' : '#664d03';
            $badgeHtml = '<p style="margin-top:9px;"><span style="display:inline-block;padding:4px 14px;'
                       . 'border-radius:20px;font-size:12px;font-weight:700;background:' . $payBg . ';color:' . $payColor . ';">'
                       . ucfirst($o['payment_status']) . '</span></p>';
        }

        // ── Branding helpers ────────────────────────────────────────
        $logoBlock    = self::logoHtml($b);
        $contact      = self::contactHtml($b);
        $bank         = self::bankHtml($b);
        $socials      = self::socialHtml($b);
        $footerNote   = $b['footer_note'] ?: 'Payment Terms: Due on Receipt';
        $thankName    = htmlspecialchars($b['name']);

        // ── HTML OUTPUT ─────────────────────────────────────────────
        $docType = $isReceipt ? 'Receipt' : 'Invoice';
        $html    = '<!DOCTYPE html><html lang="en"><head>';
        $html   .= '<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">';
        $html   .= '<title>' . $docType . ' — ' . htmlspecialchars($o['order_number']) . '</title>';
        $html   .= '<style>' . self::css() . '</style>';
        $html   .= '</head><body>';

        // Watermark
        $html .= '<div class="watermark">' . ($isReceipt ? 'PAID' : strtoupper($o['payment_status'])) . '</div>';

        // Print bar (hidden on print)
        $html .= '<div class="no-print" style="text-align:right;padding:12px 40px;background:#f8f9fa;border-bottom:1px solid #e0e0e0;">';
        $html .= '<button onclick="window.print()" style="background:#E63946;color:#fff;border:none;padding:10px 24px;border-radius:6px;cursor:pointer;font-size:14px;">&#128438; Print / Save PDF</button>';
        $html .= ' <button onclick="window.close()" style="background:#eee;color:#333;border:none;padding:10px 24px;border-radius:6px;cursor:pointer;font-size:14px;">Close</button>';
        $html .= '</div>';

        $html .= '<div class="invoice-wrap">';

        // ── Header ─────────────────────────────────────────────────
        $html .= '<div class="inv-header">';
        $html .= '<div class="inv-company">';
        $html .= $logoBlock;
        if ($contact) {
            $html .= '<div class="inv-company-contact">' . $contact . '</div>';
        }
        $html .= '</div>';
        $html .= '<div class="inv-title">';
        $html .= '<h2>' . ($isReceipt ? 'RECEIPT' : 'INVOICE') . '</h2>';
        $html .= '<p><strong>' . htmlspecialchars($o['order_number']) . '</strong></p>';
        $html .= '<p>Date: ' . $date . '</p>';
        if (!$isReceipt) $html .= '<p>Due: ' . $dueDate . '</p>';
        $html .= $badgeHtml;
        $html .= '</div>';
        $html .= '</div>'; // .inv-header

        // ── Info grid ───────────────────────────────────────────────
        $html .= '<div class="info-grid">';
        $html .= '<div class="info-box"><h4>Billed To</h4><p>';
        $html .= '<strong>' . htmlspecialchars($o['shipping_name']) . '</strong><br>';
        $html .= htmlspecialchars($o['shipping_phone']) . '<br>';
        $html .= htmlspecialchars($o['shipping_address']) . '<br>';
        $html .= htmlspecialchars($o['shipping_city']) . $stateText . '<br>';
        $html .= htmlspecialchars($o['shipping_country']);
        $html .= '</p></div>';
        $html .= '<div class="info-box"><h4>Order Details</h4><p>';
        $html .= '<strong>Order #:</strong> ' . htmlspecialchars($o['order_number']) . '<br>';
        $html .= '<strong>Date:</strong> ' . $date . '<br>';
        $html .= '<strong>Payment:</strong> ' . $payMethod . '<br>';
        $html .= '<strong>Shipping:</strong> ' . $shipMethod . '<br>';
        $html .= '<strong>Status:</strong> ' . $orderStatus;
        $html .= '</p></div>';
        $html .= '</div>'; // .info-grid

        // ── Items table ─────────────────────────────────────────────
        $html .= '<table>';
        $html .= '<thead><tr>';
        $html .= '<th>Product</th>';
        $html .= '<th style="text-align:center;width:80px;">Qty</th>';
        $html .= '<th style="text-align:right;width:130px;">Unit Price</th>';
        $html .= '<th style="text-align:right;width:130px;">Total</th>';
        $html .= '</tr></thead>';
        $html .= '<tbody>' . $rows . '</tbody>';
        $html .= '<tfoot>';
        $html .= '<tr><td colspan="3" style="text-align:right;">Subtotal</td><td style="text-align:right;">' . $cur . ' ' . number_format($o['subtotal'], 2) . '</td></tr>';
        $html .= $discountRow;
        $html .= '<tr><td colspan="3" style="text-align:right;">Shipping</td><td style="text-align:right;">' . $shippingText . '</td></tr>';
        $html .= '<tr class="total-row"><td colspan="3" style="text-align:right;">TOTAL</td><td style="text-align:right;">' . $cur . ' ' . number_format($o['total_amount'], 2) . '</td></tr>';
        $html .= '</tfoot>';
        $html .= '</table>';

        $html .= $notesHtml;
        $html .= $trackingHtml;

        // ── Footer ──────────────────────────────────────────────────
        $html .= '<div class="inv-footer">';
        $html .= '<div class="inv-footer-grid">';

        // Left: terms, bank, social
        $html .= '<div>';
        $html .= '<p>' . htmlspecialchars($footerNote) . '</p>';
        $html .= $bank;
        if ($socials) {
            $html .= '<div style="margin-top:10px;">' . $socials . '</div>';
        }
        $html .= '</div>';

        // Right: signature
        $html .= '<div style="text-align:right;">';
        $html .= '<p>Thank you for your business!</p>';
        $html .= '<div class="sig-block">Authorised Signature</div>';
        $html .= '</div>';

        $html .= '</div>'; // .inv-footer-grid
        $html .= '</div>'; // .inv-footer

        // Thank-you bar
        $html .= '<div class="inv-thanks">&#9825; Thank you for shopping with ' . $thankName . '!</div>';

        $html .= '</div>'; // .invoice-wrap
        $html .= '</body></html>';

        return $html;
    }

    // ── Stream invoice directly to browser (preview + print) ───────
    public function stream(): void
    {
        header('Content-Type: text/html; charset=UTF-8');
        $label = $this->type === 'receipt' ? 'Receipt' : 'Invoice';
        header('Content-Disposition: inline; filename="' . $label . '-' . $this->order['order_number'] . '.html"');
        echo $this->toHTML();
        exit;
    }

    // ── Generate auto-download PDF page via html2pdf.js ────────────
    //
    // Opens a full-screen "Preparing PDF…" overlay while html2pdf.js
    // captures the .invoice-wrap element (no print bar, no dialog)
    // and saves it as a real PDF file to the user's downloads folder.
    public function toDownloadHTML(): string
    {
        $label  = $this->type === 'receipt' ? 'Receipt' : 'Invoice';
        $fname  = $label . '-' . $this->order['order_number'] . '.pdf';
        $title  = $label . ' — ' . $this->order['order_number'];

        // Build the base invoice HTML then inject the download machinery
        $base = $this->toHTML();

        // Strip closing tags so we can append before </body></html>
        $base = str_replace('</body></html>', '', $base);

        $fnameJson = json_encode($fname);
        $titleJson = json_encode($title);

        $base .= '
<!-- ═══ DOWNLOAD MODE — appended by InvoiceGenerator::streamDownload() ═══ -->
<style>
/* Hide the print/close bar — html2pdf captures .invoice-wrap only anyway */
.no-print { display: none !important; }

#dlOverlay {
    position: fixed; inset: 0;
    background: rgba(255,255,255,.97);
    z-index: 99999;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    gap: 18px;
    font-family: "Segoe UI", Arial, sans-serif;
}
#dlSpinner {
    width: 52px; height: 52px;
    border: 4px solid #e5e7eb;
    border-top-color: #E63946;
    border-radius: 50%;
    animation: dlSpin .8s linear infinite;
}
@keyframes dlSpin { to { transform: rotate(360deg); } }
#dlIcon   { display: none; font-size: 42px; }
#dlTitle  { font-size: 18px; font-weight: 700; color: #1f2937; }
#dlSub    { font-size: 13px; color: #6b7280; max-width: 300px; text-align: center; }
#dlClose  {
    display: none;
    margin-top: 4px; padding: 10px 30px;
    background: #E63946; color: #fff;
    border: none; border-radius: 7px;
    font-size: 14px; font-weight: 600;
    cursor: pointer; transition: opacity .2s;
}
#dlClose:hover { opacity: .88; }
</style>

<div id="dlOverlay">
    <div id="dlSpinner"></div>
    <div id="dlIcon"></div>
    <div id="dlTitle">Preparing your PDF&hellip;</div>
    <div id="dlSub">Your download will start automatically. Please wait.</div>
    <button id="dlClose" onclick="window.close()">Close Window</button>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
(function () {
    var fname   = ' . $fnameJson . ';
    var content = document.querySelector(".invoice-wrap");

    if (!content) {
        document.getElementById("dlSpinner").style.display = "none";
        document.getElementById("dlTitle").textContent     = "Error";
        document.getElementById("dlSub").textContent       = "Could not locate invoice content.";
        document.getElementById("dlClose").style.display   = "inline-block";
        return;
    }

    var opt = {
        margin:      [10, 10, 12, 10],
        filename:    fname,
        image:       { type: "jpeg", quality: 0.98 },
        html2canvas: { scale: 2, useCORS: true, allowTaint: true, logging: false },
        jsPDF:       { unit: "mm", format: "a4", orientation: "portrait" }
    };

    html2pdf().set(opt).from(content).save()
        .then(function () {
            document.getElementById("dlSpinner").style.display = "none";
            document.getElementById("dlIcon").textContent      = "✅";
            document.getElementById("dlIcon").style.display    = "block";
            document.getElementById("dlTitle").textContent     = "Download complete!";
            document.getElementById("dlSub").textContent       = "Your PDF has been saved to your downloads folder.";
            document.getElementById("dlClose").style.display   = "inline-block";
            document.getElementById("dlClose").textContent     = "Close Window";
        })
        .catch(function () {
            document.getElementById("dlSpinner").style.display      = "none";
            document.getElementById("dlIcon").textContent           = "⚠️";
            document.getElementById("dlIcon").style.display         = "block";
            document.getElementById("dlTitle").textContent          = "Download failed";
            document.getElementById("dlSub").textContent            = "Please use the Print / Save PDF button instead.";
            document.getElementById("dlClose").style.display        = "inline-block";
            document.getElementById("dlClose").textContent          = "Go back";
            document.getElementById("dlClose").onclick              = function () { history.back(); };
        });
})();
</script>
</body></html>';

        return $base;
    }

    // ── Stream the auto-download page directly to browser ──────────
    public function streamDownload(): void
    {
        header('Content-Type: text/html; charset=UTF-8');
        $label = $this->type === 'receipt' ? 'Receipt' : 'Invoice';
        header('Content-Disposition: inline; filename="' . $label . '-' . $this->order['order_number'] . '-download.html"');
        echo $this->toDownloadHTML();
        exit;
    }
}
