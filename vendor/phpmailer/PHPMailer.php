<?php
/**
 * UTHRShop SMTP Mailer — Full SMTP implementation, no dependencies
 */
namespace PHPMailer\PHPMailer;

class Exception extends \Exception {}

class PHPMailer
{
    public string $Host        = 'smtp.gmail.com';
    public int    $Port        = 587;
    public bool   $SMTPAuth    = true;
    public string $SMTPSecure  = 'tls';   // 'tls'|'ssl'|''
    public string $Username    = '';
    public string $Password    = '';
    public string $From        = '';
    public string $FromName    = '';
    public string $Subject     = '';
    public string $Body        = '';
    public string $AltBody     = '';
    public string $CharSet     = 'UTF-8';
    public int    $SMTPDebug   = 0;
    public int    $Timeout     = 30;

    private array  $to          = [];
    private array  $cc          = [];
    private array  $bcc         = [];
    private array  $replyTo     = [];
    private array  $attachments = [];
    private bool   $isHTML      = false;
    private mixed  $smtp        = null;
    private string $mailer      = 'smtp';

    public function isSMTP()                      : static { $this->mailer = 'smtp'; return $this; }
    public function isMail()                      : static { $this->mailer = 'mail'; return $this; }
    public function isHTML(bool $v = true)        : static { $this->isHTML = $v; return $this; }
    public function setFrom(string $a, string $n='') : static { $this->From=$a; $this->FromName=$n; return $this; }
    public function addAddress(string $a, string $n='') : static { $this->to[] = [$a,$n]; return $this; }
    public function addCC(string $a, string $n='')      : static { $this->cc[] = [$a,$n]; return $this; }
    public function addBCC(string $a, string $n='')     : static { $this->bcc[]= [$a,$n]; return $this; }
    public function addReplyTo(string $a, string $n='') : static { $this->replyTo[]=[$a,$n]; return $this; }
    public function clearAddresses() : static { $this->to=[]; $this->cc=[]; $this->bcc=[]; return $this; }
    public function clearAttachments(): static { $this->attachments=[]; return $this; }

    public function addAttachment(string $path, string $name='', string $encoding='base64', string $type='application/octet-stream') : static {
        $this->attachments[] = compact('path','name','encoding','type');
        return $this;
    }

    // ── Main send ───────────────────────────────────────────────────
    public function send(): bool {
        if (empty($this->to)) throw new Exception('No recipients set.');
        if ($this->mailer === 'mail') return $this->sendViaMail();
        return $this->sendViaSMTP();
    }

    // ── Build MIME message ──────────────────────────────────────────
    private function buildMessage(): array {
        $boundary = 'utm_' . md5(uniqid());
        $hasAtt   = !empty($this->attachments);
        $hasAlt   = !empty($this->AltBody);

        if ($hasAtt) {
            $ct = "multipart/mixed; boundary=\"{$boundary}\"";
        } elseif ($hasAlt) {
            $ct = "multipart/alternative; boundary=\"{$boundary}\"";
        } else {
            $ct = ($this->isHTML ? 'text/html' : 'text/plain') . "; charset={$this->CharSet}";
        }

        // Headers
        $headers = [];
        $headers[] = 'Date: ' . date('r');
        $headers[] = 'From: ' . $this->encodeHeader($this->FromName) . " <{$this->From}>";
        $toLine = implode(', ', array_map(fn($t) => $this->encodeHeader($t[1])." <{$t[0]}>", $this->to));
        $headers[] = "To: $toLine";
        if ($this->cc) $headers[] = 'Cc: '.implode(', ',array_map(fn($c)=>"<{$c[0]}>", $this->cc));
        if ($this->replyTo) $headers[] = 'Reply-To: '."<{$this->replyTo[0][0]}>";
        $headers[] = 'Subject: ' . $this->encodeHeader($this->Subject);
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = "Content-Type: $ct";
        $headers[] = 'Message-ID: <'.uniqid('utm_').'@uthrshop.lk>';
        $headers[] = 'X-Mailer: UTHRShop-Mailer/1.0';

        // Body
        if (!$hasAtt && !$hasAlt) {
            $body = $this->Body;
        } else {
            $body = '';
            if ($hasAlt && !$hasAtt) {
                $body .= "--{$boundary}\r\n";
                $body .= "Content-Type: text/plain; charset={$this->CharSet}\r\n\r\n";
                $body .= $this->AltBody . "\r\n";
                $body .= "--{$boundary}\r\n";
                $body .= "Content-Type: text/html; charset={$this->CharSet}\r\n\r\n";
                $body .= $this->Body . "\r\n";
                $body .= "--{$boundary}--";
            } elseif ($hasAtt) {
                $body .= "--{$boundary}\r\n";
                $body .= "Content-Type: ".($this->isHTML?'text/html':'text/plain')."; charset={$this->CharSet}\r\n\r\n";
                $body .= $this->Body . "\r\n";
                foreach ($this->attachments as $att) {
                    $fname   = $att['name'] ?: basename($att['path']);
                    $fdata   = base64_encode(file_get_contents($att['path']));
                    $body .= "--{$boundary}\r\n";
                    $body .= "Content-Type: {$att['type']}; name=\"{$fname}\"\r\n";
                    $body .= "Content-Transfer-Encoding: base64\r\n";
                    $body .= "Content-Disposition: attachment; filename=\"{$fname}\"\r\n\r\n";
                    $body .= chunk_split($fdata) . "\r\n";
                }
                $body .= "--{$boundary}--";
            }
        }

        return [implode("\r\n", $headers), $body];
    }

    // ── PHP mail() fallback ─────────────────────────────────────────
    private function sendViaMail(): bool {
        [$headers, $body] = $this->buildMessage();
        $to      = implode(',', array_column($this->to, 0));
        $addFrom = '-f' . $this->From;
        return mail($to, $this->Subject, $body, $headers, $addFrom);
    }

    // ── Full SMTP client ────────────────────────────────────────────
    private function sendViaSMTP(): bool {
        $host = ($this->SMTPSecure === 'ssl') ? 'ssl://'.$this->Host : $this->Host;
        $this->smtp = @fsockopen($host, $this->Port, $errno, $errstr, $this->Timeout);
        if (!$this->smtp) throw new Exception("SMTP connect failed ($errno): $errstr");

        stream_set_timeout($this->smtp, $this->Timeout);
        $this->smtpGet();   // 220 greeting

        $this->smtpPut("EHLO uthrshop.lk");
        $ehlo = $this->smtpGet();

        // STARTTLS
        if ($this->SMTPSecure === 'tls') {
            $this->smtpPut("STARTTLS");
            $this->smtpGet(); // 220
            if (!stream_socket_enable_crypto($this->smtp, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                throw new Exception("TLS negotiation failed.");
            }
            $this->smtpPut("EHLO uthrshop.lk");
            $this->smtpGet();
        }

        // AUTH
        if ($this->SMTPAuth) {
            $this->smtpPut("AUTH LOGIN");
            $this->smtpGet(); // 334
            $this->smtpPut(base64_encode($this->Username));
            $this->smtpGet(); // 334
            $this->smtpPut(base64_encode($this->Password));
            $r = $this->smtpGet();
            if (!str_starts_with(trim($r), '235')) throw new Exception("SMTP auth failed: $r");
        }

        $this->smtpPut("MAIL FROM:<{$this->From}>");
        $this->smtpGet();

        $allTo = array_merge($this->to, $this->cc, $this->bcc);
        foreach ($allTo as $rcpt) {
            $this->smtpPut("RCPT TO:<{$rcpt[0]}>");
            $this->smtpGet();
        }

        $this->smtpPut("DATA");
        $this->smtpGet(); // 354

        [$headers, $body] = $this->buildMessage();
        $this->smtpPut($headers . "\r\n\r\n" . $body . "\r\n.");
        $r = $this->smtpGet();

        $this->smtpPut("QUIT");
        fclose($this->smtp);

        if (!str_starts_with(trim($r), '250')) throw new Exception("Message rejected: $r");
        return true;
    }

    private function smtpPut(string $data): void {
        if ($this->SMTPDebug > 0) echo "→ $data\n";
        fwrite($this->smtp, $data . "\r\n");
    }

    private function smtpGet(): string {
        $resp = '';
        while ($line = fgets($this->smtp, 515)) {
            $resp .= $line;
            if (substr($line, 3, 1) === ' ') break;
        }
        if ($this->SMTPDebug > 0) echo "← $resp";
        return $resp;
    }

    private function encodeHeader(string $str): string {
        if (!$str) return '';
        if (mb_detect_encoding($str, 'ASCII', true)) return $str;
        return '=?UTF-8?B?' . base64_encode($str) . '?=';
    }
}
