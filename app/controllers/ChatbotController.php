<?php
require_once CORE . '/Controller.php';

class ChatbotController extends Controller {

    public function respond(array $p=[]): void {
        if (!RateLimit::check('chatbot', 30, 60)) {
            $this->json(['reply'=>"You're sending messages too fast! Please slow down. 😊"]);
        }

        $message = Helper::sanitize($_POST['message'] ?? '');
        if (!$message) {
            $this->json(['reply'=>"Hi! I'm the Anything.lk assistant. How can I help you today? 😊"]);
        }

        $lower = mb_strtolower(trim($message));
        $reply = $this->getReply($lower, $message);
        $this->json(['reply' => $reply]);
    }

    private function getReply(string $lower, string $original): string {
        // Greetings
        if (preg_match('/^(hi|hello|hey|good morning|good afternoon|good evening|howdy|what\'s up|whats up|hola)\b/', $lower)) {
            $greets = [
                "Hi there! 👋 Welcome to Anything.lk! How can I help you today?",
                "Hello! 😊 I'm here to help. What are you looking for?",
                "Hey! Welcome to Anything.lk — Sri Lanka's favourite online store. What can I do for you?",
            ];
            return $greets[array_rand($greets)];
        }

        // Thank you
        if (preg_match('/thank(s| you)|thx|tysm/i', $lower)) {
            return "You're most welcome! 😊 Is there anything else I can help you with?";
        }

        // Order tracking
        if (str_contains($lower,'track') || (str_contains($lower,'order') && str_contains($lower,'where'))) {
            return "📦 To track your order:\n1. Go to **My Account → Orders**\n2. Or visit our [Order Tracking](/order-tracking) page\n3. Enter your order number + email\n\nNeed help with a specific order number?";
        }

        // Returns
        if (str_contains($lower,'return') || str_contains($lower,'refund') || str_contains($lower,'exchange')) {
            return "↩️ Our Return Policy:\n• Returns accepted within **7 days** of delivery\n• Item must be unused and in original packaging\n• Refunds processed in 3–5 business days\n\nVisit our [Return Policy](/return-policy) page for full details or contact support.";
        }

        // Shipping
        if (str_contains($lower,'ship') || str_contains($lower,'deliver') || str_contains($lower,'how long')) {
            return "🚚 Shipping Options:\n• **Standard** (3–7 days): LKR 350 | Free over LKR 5,000\n• **Express** (1–2 days): LKR 650\n• **Same Day** (Colombo): LKR 950\n\nWe deliver island-wide across Sri Lanka! 🇱🇰";
        }

        // Payment
        if (str_contains($lower,'pay') || str_contains($lower,'payment') || str_contains($lower,'koko') || str_contains($lower,'mintpay')) {
            return "💳 Payment Methods:\n• 💵 Cash on Delivery\n• 🟠 KOKO (3 interest-free installments)\n• 💚 MintPay (3 or 6 monthly installments)\n• 💳 Payzy (online card payment)\n\nAll transactions are SSL secured! 🔒";
        }

        // Coupon/discount
        if (str_contains($lower,'coupon') || str_contains($lower,'discount') || str_contains($lower,'promo') || str_contains($lower,'offer')) {
            return "🏷️ **New Customer Offer:** Use code **WELCOME10** for 10% off your first order!\n\nApply at checkout. Min order: LKR 500.";
        }

        // Contact/Support
        if (str_contains($lower,'contact') || str_contains($lower,'support') || str_contains($lower,'help') || str_contains($lower,'phone') || str_contains($lower,'call')) {
            return "📞 Contact Us:\n• Phone: +94 77 000 0000\n• Email: support@anything.lk\n• WhatsApp: Click the WhatsApp button below!\n• Hours: Mon–Sat 8am–9pm, Sun 10am–6pm";
        }

        // Product search
        try {
            $q     = $this->db->connect()->real_escape_string($original);
            $prods = $this->db->select(
                "SELECT name, slug, price, sale_price FROM products
                 WHERE MATCH(name,tags,short_desc) AGAINST('$q' IN BOOLEAN MODE)
                   AND status='active' LIMIT 3"
            ) ?: [];

            if (!empty($prods)) {
                $reply = "🔍 Here's what I found for \"" . e($original) . "\":\n\n";
                foreach ($prods as $pr) {
                    $price = $pr['sale_price'] ?: $pr['price'];
                    $reply .= "• **{$pr['name']}** — LKR " . number_format($price, 2) . "\n";
                }
                $reply .= "\n[View all results](/search?q=" . urlencode($original) . ")";
                return $reply;
            }
        } catch (\Throwable $e) {}

        // Fallback
        $fallbacks = [
            "I'm not sure about that, but our team can help! 😊 Contact us at **support@anything.lk** or call **+94 77 000 0000**.",
            "That's a great question! For specific queries, please contact our support team or check our [FAQ page](/faq).",
            "I'd love to help more with that! Try searching on our [products page](/products) or reach out to our support team.",
        ];
        return $fallbacks[array_rand($fallbacks)];
    }
}
