<?php
define('ROOT', __DIR__);
require_once ROOT . '/db_connect.php';
$db   = new Db();
$conn = $db->connect();

$conn->query('CREATE TABLE IF NOT EXISTS chat_sessions (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NULL,
  guest_id VARCHAR(64) NULL,
  status ENUM("open","closed") DEFAULT "open",
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY idx_status (status),
  KEY idx_guest (guest_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

$conn->query('CREATE TABLE IF NOT EXISTS chat_messages (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  session_id INT UNSIGNED NOT NULL,
  sender ENUM("user","bot","admin") NOT NULL,
  message TEXT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  KEY idx_session (session_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

$conn->query('CREATE TABLE IF NOT EXISTS chat_auto_replies (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  keyword VARCHAR(150) NOT NULL,
  reply TEXT NOT NULL,
  status TINYINT(1) DEFAULT 1,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

$count = (int)($conn->query("SELECT COUNT(*) AS c FROM chat_auto_replies")->fetch_assoc()['c'] ?? 0);
if ($count === 0) {
    $seeds = [
        ["hi,hello,hey,hiya",                    "Hello! 👋 Welcome to Anything.lk. How can I help you today?"],
        ["track,order status,where is my order",  "Please share your order number (e.g. #1042) and I'll look that up! You can also visit the Order Tracking page."],
        ["shipping,delivery,deliver,ship",         "We deliver island-wide across Sri Lanka 🚚 Standard: 3–5 business days. Express: 1–2 days."],
        ["return,refund,exchange",                 "We accept returns within 14 days of delivery. Items must be unused in original packaging. Email support@anything.lk to start a return. 📦"],
        ["payment,pay,cod,card,bank",              "We accept Cash on Delivery, bank transfers, and all major credit/debit cards. 100% secure. 💳"],
        ["contact,phone,email,support",            "Reach us at:\n📧 support@anything.lk\n📞 +94 77 000 0000\nOr use our Contact Us page!"],
        ["offer,discount,promo,sale,deal,coupon",  "Check our homepage for the latest deals! We run seasonal promotions regularly. 🎉"],
        ["human,agent,person,staff,representative","I'll flag this for our support team! Available Mon–Sat, 9AM–6PM. Leave your message and we'll reply soon. 🙋"],
        ["hours,working,open,available,time",      "Support team hours: Monday–Saturday, 9:00 AM – 6:00 PM. 🕘"],
        ["warranty,guarantee",                     "Most products include a manufacturer warranty. Check the product page for details. 🛡️"],
        ["cancel,cancellation",                    "Orders can be cancelled before they are shipped. Contact us immediately with your order number!"],
        ["account,login,password,register",        "You can manage your account at the My Account page. Forgot your password? Use the Forgot Password link on the login page."],
    ];
    foreach ($seeds as [$kw, $reply]) {
        $k = $conn->real_escape_string($kw);
        $r = $conn->real_escape_string($reply);
        $conn->query("INSERT INTO chat_auto_replies (keyword, reply) VALUES ('$k', '$r')");
    }
}

echo '<h3 style="font-family:sans-serif;color:green">✅ Chat tables created & seeded. <a href="/">Go to site</a></h3>';
