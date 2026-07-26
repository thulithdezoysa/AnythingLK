-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 23, 2026 at 12:52 PM
-- Server version: 8.4.7
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `anythinglk_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_activity_log`
--

DROP TABLE IF EXISTS `admin_activity_log`;
CREATE TABLE IF NOT EXISTS `admin_activity_log` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_id` int UNSIGNED DEFAULT NULL,
  `action_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `table_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `record_id` int UNSIGNED DEFAULT NULL,
  `old_data` json DEFAULT NULL,
  `new_data` json DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_activity_log`
--

INSERT INTO `admin_activity_log` (`id`, `admin_id`, `action_type`, `table_name`, `record_id`, `old_data`, `new_data`, `ip_address`, `created_at`) VALUES
(1, 1, 'CREATE', 'vendors', 1, NULL, NULL, '::1', '2026-03-30 05:41:02'),
(2, 1, 'CREATE', 'categories', 16, NULL, NULL, '::1', '2026-03-30 05:46:18'),
(3, 1, 'CREATE', 'categories', 17, NULL, NULL, '::1', '2026-03-30 05:50:02'),
(4, 1, 'DELETE', 'products', 1, NULL, NULL, '::1', '2026-03-30 18:59:06'),
(5, 1, 'SUBMIT', 'purchase_orders', 1, NULL, NULL, '::1', '2026-03-30 19:16:25'),
(6, 1, 'CREATE', 'categories', 18, NULL, NULL, '::1', '2026-03-30 20:46:19'),
(7, 1, 'APPROVE', 'purchase_orders', 1, NULL, NULL, '::1', '2026-04-04 15:45:34'),
(8, 1, 'RECEIVE', 'purchase_orders', 1, NULL, NULL, '::1', '2026-04-04 15:45:41'),
(9, 1, 'SUBMIT', 'purchase_orders', 2, NULL, NULL, '::1', '2026-04-04 18:11:26'),
(10, 1, 'APPROVE', 'purchase_orders', 2, NULL, NULL, '::1', '2026-04-04 18:11:31'),
(11, 1, 'RECEIVE', 'purchase_orders', 2, NULL, NULL, '::1', '2026-04-04 18:11:36'),
(12, 1, 'SUBMIT', 'purchase_orders', 3, NULL, NULL, '::1', '2026-04-12 18:17:54'),
(13, 1, 'APPROVE', 'purchase_orders', 3, NULL, NULL, '::1', '2026-04-12 18:18:09'),
(14, 1, 'RECEIVE', 'purchase_orders', 3, NULL, NULL, '::1', '2026-04-12 18:18:24'),
(15, 1, 'CREATE', 'categories', 19, NULL, NULL, '::1', '2026-04-13 17:19:44'),
(16, 1, 'DELETE', 'products', 8, NULL, NULL, '::1', '2026-04-20 10:37:36'),
(17, 1, 'DELETE', 'products', 8, NULL, NULL, '::1', '2026-04-20 10:38:12'),
(18, 1, 'CREATE', 'categories', 20, NULL, NULL, '::1', '2026-04-20 10:42:39'),
(19, 1, 'CREATE', 'categories', 21, NULL, NULL, '::1', '2026-04-20 10:42:58'),
(20, 1, 'DELETE', 'products', 8, NULL, NULL, '::1', '2026-04-20 10:45:39'),
(21, 1, 'DELETE', 'products', 8, NULL, NULL, '::1', '2026-04-20 10:46:39'),
(22, 1, 'DELETE', 'products', 8, NULL, NULL, '::1', '2026-04-20 10:46:48'),
(23, 1, 'DELETE', 'products', 8, NULL, NULL, '::1', '2026-04-20 10:47:11'),
(24, 1, 'DELETE', 'products', 1, NULL, NULL, '::1', '2026-04-20 10:47:53'),
(25, 1, 'DELETE', 'products', 1, NULL, NULL, '::1', '2026-04-20 10:48:56'),
(26, 1, 'DELETE', 'products', 7, NULL, NULL, '::1', '2026-04-20 10:49:20'),
(27, 1, 'DELETE', 'products', 1, NULL, NULL, '::1', '2026-04-20 10:49:41'),
(28, 1, 'DELETE', 'products', 9, NULL, NULL, '::1', '2026-04-20 16:41:00'),
(29, 1, 'DELETE', 'products', 7, NULL, NULL, '::1', '2026-04-20 16:41:54'),
(30, 1, 'DELETE', 'products', 9, NULL, NULL, '::1', '2026-04-20 17:41:41'),
(31, 1, 'CREATE', 'categories', 22, NULL, NULL, '::1', '2026-04-23 19:37:23');

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

DROP TABLE IF EXISTS `banners`;
CREATE TABLE IF NOT EXISTS `banners` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subtitle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `desc_position` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'middle-left',
  `text_color` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'light',
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` enum('hero','home_mid','home_promo') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'hero',
  `sort_order` int DEFAULT '0',
  `status` tinyint(1) DEFAULT '1',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `title`, `subtitle`, `description`, `desc_position`, `text_color`, `image`, `link`, `position`, `sort_order`, `status`, `start_date`, `end_date`) VALUES
(11, 'Shop Anything. Deliver Everywhere.', 'Thousands of products. Unbeatable prices. Island-wide delivery.', '', 'middle-right', 'light', 'img_69db8643982c39.61388491.webp', 'http://localhost/AnythingLK/products', 'hero', 0, 1, NULL, NULL),
(12, 'Shop Anything. Deliver Everywhere.', 'Thousands of products. Unbeatable prices. Island-wide delivery.', '', 'middle-left', 'light', 'img_69db8670826d55.98342366.webp', 'http://localhost/AnythingLK/products', 'hero', 0, 1, NULL, NULL),
(13, 'Happy Pets Start Here', '', 'Discover premium products for healthier, happier companions.', 'top-left', 'dark', 'img_69db869114a919.34313867.webp', '', 'home_promo', 0, 1, NULL, NULL),
(14, 'Big Sale', '', '', 'middle-left', 'light', 'img_69db86a4a987f3.97571140.webp', '', 'home_promo', 0, 1, NULL, NULL),
(15, 'Premium Care for Your Pets', '', 'Shop quality food, toys, and essentials for dogs, cats, and more.', 'middle-center', 'light', 'img_69db86b9ccfe25.90137855.webp', 'http://localhost/AnythingLK/products', 'home_mid', 0, 1, NULL, NULL),
(16, 'Everything Your Pet Loves', '', 'From nutritious meals to fun toys — all in one place.', 'middle-left', 'light', 'img_69db8857441805.07030826.webp', 'http://localhost/AnythingLK/products', 'home_mid', 0, 1, NULL, NULL),
(17, 'Shop Anything. Deliver Everywhere.', 'Shop Anything. Deliver Everywhere.', '', 'bottom-center', 'light', 'img_69dbb54bf14366.49103191.webp', '', 'home_promo', 0, 1, NULL, NULL),
(19, 'Your Trusted Pet Shop', '', 'Top brands, best prices, and fast delivery across Sri Lanka.', 'middle-left', 'light', 'img_69dbc53ca1efe9.74408547.webp', 'http://localhost/AnythingLK/products', 'home_mid', 0, 1, NULL, NULL),
(20, 'dfdf', 'dfdf', '', 'middle-center', 'light', 'img_69e3043eddd680.14835475.webp', '', 'hero', 0, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `bogo_offers`
--

DROP TABLE IF EXISTS `bogo_offers`;
CREATE TABLE IF NOT EXISTS `bogo_offers` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `buy_product_id` int UNSIGNED DEFAULT NULL,
  `buy_qty` int NOT NULL DEFAULT '1',
  `get_product_id` int UNSIGNED DEFAULT NULL,
  `get_qty` int NOT NULL DEFAULT '1',
  `get_discount_pct` decimal(5,2) NOT NULL DEFAULT '100.00',
  `excluded_methods` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'koko,payzy,mintpay',
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bogo_offers`
--

INSERT INTO `bogo_offers` (`id`, `name`, `buy_product_id`, `buy_qty`, `get_product_id`, `get_qty`, `get_discount_pct`, `excluded_methods`, `start_date`, `end_date`, `status`, `created_at`) VALUES
(1, 'Buy 1 Get 1', 1, 1, 7, 1, 100.00, 'koko,payzy,mintpay', NULL, NULL, 1, '2026-04-13 12:23:08');

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

DROP TABLE IF EXISTS `brands`;
CREATE TABLE IF NOT EXISTS `brands` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `slug`, `logo`, `status`, `created_at`) VALUES
(1, 'Apple', 'apple', 'img_69c9bf649aab16.66888861.webp', 1, '2026-03-30 05:40:12'),
(2, 'srikanth', 'srikanth', NULL, 1, '2026-04-13 08:04:52');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
CREATE TABLE IF NOT EXISTS `cart` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED DEFAULT NULL,
  `session_id` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `variation_id` int UNSIGNED DEFAULT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `added_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  KEY `variation_id` (`variation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `session_id`, `product_id`, `variation_id`, `quantity`, `added_at`) VALUES
(6, NULL, 'kgp33jgllh8v4hjjj406kuld7o', 1, 1, 1, '2026-04-12 16:57:11'),
(11, NULL, 'u42rpe4pnvpo45r1654ejmsvb3', 1, NULL, 1, '2026-04-12 22:02:49'),
(12, NULL, 'u42rpe4pnvpo45r1654ejmsvb3', 7, NULL, 1, '2026-04-12 22:02:52'),
(20, NULL, 'bj54ntm8kp4g5j7hmu2jlpf5uv', 7, NULL, 1, '2026-04-18 08:39:59'),
(21, NULL, 'bj54ntm8kp4g5j7hmu2jlpf5uv', 1, NULL, 1, '2026-04-18 08:40:01'),
(25, NULL, 'blm3qc3j6irfk3uot68lgp6tqh', 1, NULL, 3, '2026-04-19 18:58:18'),
(35, NULL, 'f1tdgnp0tuq0b5ib244b0o7vva', 7, NULL, 1, '2026-04-21 08:22:44'),
(36, NULL, 'f1tdgnp0tuq0b5ib244b0o7vva', 1, NULL, 1, '2026-04-21 08:22:46'),
(37, 4, NULL, 7, NULL, 1, '2026-04-22 20:27:11'),
(38, 4, NULL, 1, NULL, 1, '2026-04-22 20:27:12'),
(39, 2, NULL, 7, NULL, 1, '2026-04-27 20:02:36'),
(40, 2, NULL, 1, NULL, 1, '2026-04-27 20:02:37'),
(42, NULL, '4f1ug698dfo8sjdoeg34sugio5', 7, NULL, 1, '2026-06-18 18:00:06'),
(43, NULL, '4f1ug698dfo8sjdoeg34sugio5', 1, NULL, 1, '2026-06-18 18:00:09');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id` int UNSIGNED DEFAULT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_si` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name_ta` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'fa-tag',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `sort_order` int DEFAULT '0',
  `depth` int DEFAULT '0',
  `path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
  `status` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_parent` (`parent_id`),
  KEY `idx_slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `parent_id`, `name`, `name_si`, `name_ta`, `slug`, `image`, `icon`, `description`, `sort_order`, `depth`, `path`, `status`, `created_at`) VALUES
(1, NULL, 'Electronics', '', '', 'electronics', NULL, 'fa-laptop', NULL, 1, 0, 'electronics', 1, '2026-03-29 04:30:02'),
(19, NULL, 'Books', '', '', 'books-69dc', NULL, 'fa-layer-group', NULL, 3, 0, 'books-69dc', 1, '2026-04-13 17:19:44'),
(20, 1, 'Laptop', '', '', 'laptop', NULL, '', NULL, 0, 1, 'electronics/laptop', 1, '2026-04-20 10:42:39'),
(21, 19, 'Story', '', '', 'story', NULL, '', NULL, 0, 1, 'books-69dc/story', 1, '2026-04-20 10:42:58'),
(22, 21, 'Dark', '', '', 'dark', NULL, 'fa-tag', NULL, 0, 2, 'books-69dc/story/dark', 1, '2026-04-23 19:37:23');

-- --------------------------------------------------------

--
-- Table structure for table `chat_auto_replies`
--

DROP TABLE IF EXISTS `chat_auto_replies`;
CREATE TABLE IF NOT EXISTS `chat_auto_replies` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `keyword` varchar(150) NOT NULL,
  `reply` text NOT NULL,
  `status` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `chat_auto_replies`
--

INSERT INTO `chat_auto_replies` (`id`, `keyword`, `reply`, `status`, `created_at`) VALUES
(1, 'hi,hello,hey,hiya', 'Hello! 👋 Welcome to Anything.lk. How can I help you today?', 1, '2026-04-20 09:52:01'),
(2, 'track,order status,where is my order', 'Please share your order number (e.g. #1042) and I\'ll look that up! You can also visit the Order Tracking page.', 1, '2026-04-20 09:52:01'),
(3, 'shipping,delivery,deliver,ship', 'We deliver island-wide across Sri Lanka 🚚 Standard: 3–5 business days. Express: 1–2 days.', 1, '2026-04-20 09:52:01'),
(4, 'return,refund,exchange', 'We accept returns within 14 days of delivery. Items must be unused in original packaging. Email support@anything.lk to start a return. 📦', 1, '2026-04-20 09:52:01'),
(5, 'payment,pay,cod,card,bank', 'We accept Cash on Delivery, bank transfers, and all major credit/debit cards. 100% secure. 💳', 1, '2026-04-20 09:52:01'),
(6, 'contact,phone,email,support', 'Reach us at:\n📧 support@anything.lk\n📞 +94 77 000 0000\nOr use our Contact Us page!', 1, '2026-04-20 09:52:01'),
(7, 'offer,discount,promo,sale,deal,coupon', 'Check our homepage for the latest deals! We run seasonal promotions regularly. 🎉', 1, '2026-04-20 09:52:01'),
(8, 'human,agent,person,staff,representative', 'I\'ll flag this for our support team! Available Mon–Sat, 9AM–6PM. Leave your message and we\'ll reply soon. 🙋', 1, '2026-04-20 09:52:01'),
(9, 'hours,working,open,available,time', 'Support team hours: Monday–Saturday, 9:00 AM – 6:00 PM. 🕘', 1, '2026-04-20 09:52:01'),
(10, 'warranty,guarantee', 'Most products include a manufacturer warranty. Check the product page for details. 🛡️', 1, '2026-04-20 09:52:01'),
(11, 'cancel,cancellation', 'Orders can be cancelled before they are shipped. Contact us immediately with your order number!', 1, '2026-04-20 09:52:01'),
(12, 'account,login,password,register', 'You can manage your account at the My Account page. Forgot your password? Use the Forgot Password link on the login page.', 1, '2026-04-20 09:52:01'),
(13, 'google', 'i am google', 1, '2026-04-20 09:54:06');

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

DROP TABLE IF EXISTS `chat_messages`;
CREATE TABLE IF NOT EXISTS `chat_messages` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `session_id` int UNSIGNED NOT NULL,
  `sender` enum('user','bot','admin') NOT NULL,
  `message` text NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_session` (`session_id`)
) ENGINE=InnoDB AUTO_INCREMENT=96 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `chat_messages`
--

INSERT INTO `chat_messages` (`id`, `session_id`, `sender`, `message`, `created_at`) VALUES
(1, 1, 'bot', 'Hi Super Admin! 👋 I\'m the Anything.lk assistant. How can I help you today?', '2026-04-20 10:09:32'),
(2, 1, 'user', 'hi', '2026-04-20 10:12:21'),
(3, 1, 'bot', 'Hello! 👋 Welcome to Anything.lk. How can I help you today?', '2026-04-20 10:12:21'),
(4, 1, 'user', 'hi', '2026-04-20 10:12:25'),
(5, 1, 'bot', 'Hello! 👋 Welcome to Anything.lk. How can I help you today?', '2026-04-20 10:12:25'),
(6, 1, 'user', 'hi', '2026-04-20 10:12:35'),
(7, 1, 'bot', 'Hello! 👋 Welcome to Anything.lk. How can I help you today?', '2026-04-20 10:12:35'),
(8, 1, 'user', 'welcome', '2026-04-20 10:12:40'),
(9, 1, 'bot', 'I couldn\'t find an answer to that 🤔\nWould you like to talk to a human agent? Click **Talk to Human** below, or WhatsApp us directly!', '2026-04-20 10:12:40'),
(10, 1, 'user', 'How do I contact you?', '2026-04-20 10:17:19'),
(11, 1, 'bot', 'Reach us at:\n📧 support@anything.lk\n📞 +94 77 000 0000\nOr use our Contact Us page!', '2026-04-20 10:17:19'),
(12, 1, 'user', 'I want to talk to a human agent', '2026-04-20 10:17:21'),
(13, 1, 'bot', 'I\'ll flag this for our support team! Available Mon–Sat, 9AM–6PM. Leave your message and we\'ll reply soon. 🙋', '2026-04-20 10:17:21'),
(14, 1, 'user', 'What offers are available today?', '2026-04-20 10:17:45'),
(15, 1, 'bot', 'Check our homepage for the latest deals! We run seasonal promotions regularly. 🎉', '2026-04-20 10:17:45'),
(16, 1, 'user', 'Track my order', '2026-04-20 10:51:06'),
(17, 1, 'bot', 'Please share your order number (e.g. #1042) and I\'ll look that up! You can also visit the Order Tracking page.', '2026-04-20 10:51:06'),
(18, 1, 'user', 'ORD-20260418-69ED1D', '2026-04-20 10:51:31'),
(19, 1, 'bot', 'I couldn\'t find an answer to that 🤔\nWould you like to talk to a human agent? Click **Talk to Human** below, or WhatsApp us directly!', '2026-04-20 10:51:31'),
(20, 1, 'user', '#ORD-20260418-69ED1D', '2026-04-20 10:51:36'),
(21, 1, 'bot', 'I couldn\'t find an answer to that 🤔\nWould you like to talk to a human agent? Click **Talk to Human** below, or WhatsApp us directly!', '2026-04-20 10:51:36'),
(22, 1, 'user', 'I want to talk to a human agent', '2026-04-20 10:51:40'),
(23, 1, 'bot', 'I\'ll flag this for our support team! Available Mon–Sat, 9AM–6PM. Leave your message and we\'ll reply soon. 🙋', '2026-04-20 10:51:40'),
(24, 2, 'bot', 'Hi Super Admin! 👋 I\'m the Anything.lk assistant. How can I help you today?', '2026-04-20 17:43:18'),
(25, 2, 'user', 'google', '2026-04-20 17:43:23'),
(26, 2, 'bot', 'i am google', '2026-04-20 17:43:23'),
(27, 2, 'admin', 'Order received!', '2026-04-20 17:52:32'),
(28, 2, 'admin', 'Thank you for contacting us!', '2026-04-20 17:53:46'),
(29, 2, 'user', 'What offers are available today?', '2026-04-20 21:33:37'),
(30, 2, 'bot', 'Check our homepage for the latest deals! We run seasonal promotions regularly. 🎉', '2026-04-20 21:33:37'),
(31, 2, 'user', 'hey', '2026-04-20 21:33:42'),
(32, 2, 'bot', 'Hello! 👋 Welcome to Anything.lk. How can I help you today?', '2026-04-20 21:33:42'),
(33, 2, 'user', 'how are you', '2026-04-20 21:33:47'),
(34, 2, 'bot', 'I couldn\'t find an answer to that 🤔\nWould you like to talk to a human agent? Click **Talk to Human** below, or WhatsApp us directly!', '2026-04-20 21:33:47'),
(35, 3, 'bot', 'Hi there! 👋 I\'m the Anything.lk assistant. How can I help you today?', '2026-04-21 08:48:55'),
(36, 3, 'user', 'What are your shipping options?', '2026-04-21 08:49:07'),
(37, 3, 'bot', 'Hello! 👋 Welcome to Anything.lk. How can I help you today?', '2026-04-21 08:49:07'),
(38, 3, 'user', 'Track my order', '2026-04-21 08:49:12'),
(39, 3, 'bot', 'Please share your order number (e.g. #1042) and I\'ll look that up! You can also visit the Order Tracking page.', '2026-04-21 08:49:12'),
(40, 3, 'user', 'What are your shipping options?', '2026-04-21 08:49:14'),
(41, 3, 'bot', 'Hello! 👋 Welcome to Anything.lk. How can I help you today?', '2026-04-21 08:49:14'),
(42, 3, 'user', 'I want to talk to a human agent', '2026-04-21 08:49:16'),
(43, 3, 'bot', 'I\'ll flag this for our support team! Available Mon–Sat, 9AM–6PM. Leave your message and we\'ll reply soon. 🙋', '2026-04-21 08:49:16'),
(44, 3, 'user', 'What offers are available today?', '2026-04-21 08:49:18'),
(45, 3, 'bot', 'Check our homepage for the latest deals! We run seasonal promotions regularly. 🎉', '2026-04-21 08:49:18'),
(46, 3, 'user', 'How do I contact you?', '2026-04-21 08:49:19'),
(47, 3, 'bot', 'Reach us at:\n📧 support@anything.lk\n📞 +94 77 000 0000\nOr use our Contact Us page!', '2026-04-21 08:49:19'),
(48, 3, 'user', 'I want to talk to a human agent', '2026-04-21 08:49:29'),
(49, 3, 'bot', 'I\'ll flag this for our support team! Available Mon–Sat, 9AM–6PM. Leave your message and we\'ll reply soon. 🙋', '2026-04-21 08:49:29'),
(50, 2, 'user', 'How do I contact you?', '2026-04-21 15:33:30'),
(51, 2, 'bot', 'Reach us at:\n📧 support@anything.lk\n📞 +94 77 000 0000\nOr use our Contact Us page!', '2026-04-21 15:33:30'),
(52, 4, 'bot', 'Hi Waradan Srikanth! 👋 I\'m the Anything.lk assistant. How can I help you today?', '2026-04-22 20:45:21'),
(53, 4, 'user', 'sdfsdf', '2026-04-22 20:45:23'),
(54, 4, 'bot', 'I couldn\'t find an answer to that 🤔\nWould you like to talk to a human agent? Click **Talk to Human** below, or WhatsApp us directly!', '2026-04-22 20:45:24'),
(55, 2, 'user', 'hi', '2026-04-22 20:50:52'),
(56, 2, 'bot', 'Hello! 👋 Welcome to Anything.lk. How can I help you today?', '2026-04-22 20:50:52'),
(57, 2, 'user', 'Track my order', '2026-04-22 21:08:38'),
(58, 2, 'bot', 'Please share your order number (e.g. #1042) and I\'ll look that up! You can also visit the Order Tracking page.', '2026-04-22 21:08:38'),
(59, 2, 'user', 'What are your shipping options?', '2026-04-22 21:08:41'),
(60, 2, 'bot', 'Hello! 👋 Welcome to Anything.lk. How can I help you today?', '2026-04-22 21:08:41'),
(61, 2, 'user', 'I want to talk to a human agent', '2026-04-22 21:08:42'),
(62, 2, 'bot', 'I\'ll flag this for our support team! Available Mon–Sat, 9AM–6PM. Leave your message and we\'ll reply soon. 🙋', '2026-04-22 21:08:42'),
(63, 2, 'user', 'What offers are available today?', '2026-04-22 21:08:43'),
(64, 2, 'bot', 'Check our homepage for the latest deals! We run seasonal promotions regularly. 🎉', '2026-04-22 21:08:43'),
(65, 2, 'user', 'How do I contact you?', '2026-04-22 21:08:43'),
(66, 2, 'bot', 'Reach us at:\n📧 support@anything.lk\n📞 +94 77 000 0000\nOr use our Contact Us page!', '2026-04-22 21:08:43'),
(67, 2, 'user', 'google', '2026-04-22 21:08:48'),
(68, 2, 'bot', 'i am google', '2026-04-22 21:08:48'),
(69, 2, 'user', 'Track my order', '2026-04-23 19:27:52'),
(70, 2, 'bot', 'Please share your order number (e.g. #1042) and I\'ll look that up! You can also visit the Order Tracking page.', '2026-04-23 19:27:52'),
(71, 5, 'bot', 'Hi there! 👋 I\'m the Anything.lk assistant. How can I help you today?', '2026-04-26 20:23:40'),
(72, 5, 'user', 'hi', '2026-04-26 20:23:46'),
(73, 5, 'bot', 'Hello! 👋 Welcome to Anything.lk. How can I help you today?', '2026-04-26 20:23:46'),
(74, 6, 'bot', 'Hi there! 👋 I\'m the Anything.lk assistant. How can I help you today?', '2026-04-26 21:01:01'),
(75, 6, 'admin', 'Order received!', '2026-04-26 21:11:29'),
(76, 6, 'admin', 'Order received!', '2026-04-26 21:11:33'),
(77, 6, 'admin', 'Processing your request.', '2026-04-26 21:11:36'),
(78, 7, 'bot', 'Hi there! 👋 I\'m the Anything.lk assistant. How can I help you today?', '2026-04-27 19:57:45'),
(79, 7, 'user', 'What are your shipping options?', '2026-04-27 19:57:47'),
(80, 7, 'bot', 'Hello! 👋 Welcome to Anything.lk. How can I help you today?', '2026-04-27 19:57:47'),
(81, 7, 'admin', 'Order received!', '2026-04-28 08:33:08'),
(82, 7, 'admin', 'Processing your request.', '2026-04-28 08:35:59'),
(83, 2, 'user', 'What are your shipping options?', '2026-04-28 08:37:06'),
(84, 2, 'bot', 'Hello! 👋 Welcome to Anything.lk. How can I help you today?', '2026-04-28 08:37:06'),
(85, 2, 'user', 'How do I contact you?', '2026-04-28 08:37:09'),
(86, 2, 'bot', 'Reach us at:\n📧 support@anything.lk\n📞 +94 77 000 0000\nOr use our Contact Us page!', '2026-04-28 08:37:09'),
(87, 2, 'user', 'What offers are available today?', '2026-04-28 08:37:11'),
(88, 2, 'bot', 'Check our homepage for the latest deals! We run seasonal promotions regularly. 🎉', '2026-04-28 08:37:11'),
(89, 2, 'user', 'chat', '2026-04-28 08:37:17'),
(90, 2, 'bot', 'I couldn\'t find an answer to that 🤔\nWould you like to talk to a human agent? Click **Talk to Human** below, or WhatsApp us directly!', '2026-04-28 08:37:17'),
(91, 7, 'admin', 'Order received!', '2026-04-28 08:39:57'),
(92, 7, 'admin', 'Thank you for contacting us!', '2026-04-28 08:40:05'),
(93, 2, 'user', 'hi hi hi', '2026-04-28 08:40:31'),
(94, 2, 'bot', 'Hello! 👋 Welcome to Anything.lk. How can I help you today?', '2026-04-28 08:40:31'),
(95, 2, 'admin', 'Order received!', '2026-04-28 08:40:44');

-- --------------------------------------------------------

--
-- Table structure for table `chat_sessions`
--

DROP TABLE IF EXISTS `chat_sessions`;
CREATE TABLE IF NOT EXISTS `chat_sessions` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED DEFAULT NULL,
  `guest_id` varchar(64) DEFAULT NULL,
  `status` enum('open','closed') DEFAULT 'open',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`),
  KEY `idx_guest` (`guest_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `chat_sessions`
--

INSERT INTO `chat_sessions` (`id`, `user_id`, `guest_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'tmul3itru8b62i1c2gn4f0gg68', 'closed', '2026-04-20 10:09:32', '2026-04-20 16:53:16'),
(2, 1, 'jseloj0796sfem2jthvaafntqp', 'open', '2026-04-20 17:43:18', '2026-04-28 08:40:44'),
(3, NULL, 'f1tdgnp0tuq0b5ib244b0o7vva', 'open', '2026-04-21 08:48:55', '2026-04-21 08:49:29'),
(4, 4, 'gv74spb426aq8lggfm72i2btoj', 'closed', '2026-04-22 20:45:21', '2026-04-26 21:11:57'),
(5, NULL, '7153fi8qfr6381vq5cd3mtv54h', 'open', '2026-04-26 20:23:40', '2026-04-26 20:23:46'),
(6, NULL, '01b35s3g9vlvsrkv2o0p11pt85', 'closed', '2026-04-26 21:01:01', '2026-04-26 21:11:44'),
(7, NULL, '688qdiedp6a1tpenil6p6qk9qb', 'open', '2026-04-27 19:57:45', '2026-04-28 08:40:05');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

DROP TABLE IF EXISTS `contact_messages`;
CREATE TABLE IF NOT EXISTS `contact_messages` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `subject`, `message`, `is_read`, `created_at`) VALUES
(1, 'Waradan Sirikantha', 'smmsrikanth@gmail.com', 'Thank You for Contacting My Mart – 24/7 Convenient Store', '4545', 1, '2026-04-12 18:05:42'),
(2, 'Waradan Srikanth', 'smmsrikanth@gmail.com', 'Orders', 'fsdfsd', 1, '2026-04-19 12:57:07'),
(3, 'Waradan Srikanth', 'smmsrikantha@gmail.com', 'No-Reply Email from My Mart', 'sdfsdf', 1, '2026-04-19 12:57:29'),
(4, 'Waradan Srikanth', 'smmsrikanth@gmail.com', 'access website without a domain name', 'sdf', 1, '2026-04-19 13:03:39'),
(5, 'Test User', 'test@test.com', 'Test', 'Hello test message', 1, '2026-04-19 13:03:44'),
(6, 'Test', 'test@test.com', 'Test', 'Hello', 1, '2026-04-19 13:05:04'),
(7, 'Waradan Srikanth', 'smmsrikanth@gmail.com', 'Thank You for Contacting My Mart – 24/7 Convenient Store', 'sdf', 1, '2026-04-19 13:05:23');

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

DROP TABLE IF EXISTS `coupons`;
CREATE TABLE IF NOT EXISTS `coupons` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('percentage','fixed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'fixed',
  `value` decimal(10,2) NOT NULL,
  `min_order` decimal(12,2) DEFAULT '0.00',
  `max_discount` decimal(12,2) DEFAULT NULL,
  `usage_limit` int DEFAULT NULL,
  `usage_count` int DEFAULT '0',
  `per_user_limit` int DEFAULT '1',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `excluded_methods` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'koko,payzy,mintpay',
  `status` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `type`, `value`, `min_order`, `max_discount`, `usage_limit`, `usage_count`, `per_user_limit`, `start_date`, `end_date`, `excluded_methods`, `status`, `created_at`) VALUES
(1, 'WELCOME10', 'percentage', 10.00, 500.00, NULL, NULL, 0, 1, '2026-03-29', '2026-04-28', 'koko,payzy,mintpay', 1, '2026-04-26 20:35:32');

-- --------------------------------------------------------

--
-- Table structure for table `coupon_usage`
--

DROP TABLE IF EXISTS `coupon_usage`;
CREATE TABLE IF NOT EXISTS `coupon_usage` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `coupon_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `order_id` int UNSIGNED DEFAULT NULL,
  `used_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `coupon_id` (`coupon_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `global_discounts`
--

DROP TABLE IF EXISTS `global_discounts`;
CREATE TABLE IF NOT EXISTS `global_discounts` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('percentage','fixed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'percentage',
  `value` decimal(10,2) NOT NULL,
  `min_order` decimal(12,2) NOT NULL DEFAULT '0.00',
  `max_discount` decimal(12,2) DEFAULT NULL,
  `excluded_methods` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'koko,payzy,mintpay',
  `priority` tinyint NOT NULL DEFAULT '0',
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `global_discounts`
--

INSERT INTO `global_discounts` (`id`, `name`, `type`, `value`, `min_order`, `max_discount`, `excluded_methods`, `priority`, `start_date`, `end_date`, `status`, `created_at`) VALUES
(1, 'Festival', 'percentage', 10.00, 0.00, NULL, 'koko', 0, NULL, NULL, 1, '2026-04-26 20:37:25');

-- --------------------------------------------------------

--
-- Table structure for table `home_collections`
--

DROP TABLE IF EXISTS `home_collections`;
CREATE TABLE IF NOT EXISTS `home_collections` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `tagline` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `link` varchar(500) DEFAULT NULL,
  `color` varchar(20) DEFAULT '#2dc653',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `home_collections`
--

INSERT INTO `home_collections` (`id`, `name`, `tagline`, `image`, `link`, `color`, `status`, `sort_order`, `created_at`) VALUES
(1, 'Charging Adaptors', 'Power Faster', 'img_69e613c08373e4.50467396.webp', '/category/charging-adaptors', '#f43f5e', 1, 0, '2026-04-20 11:46:49'),
(2, 'Cables', 'Stay Connected', 'img_69e614018146e7.65992022.webp', '/category/cables', '#38bdf8', 1, 1, '2026-04-20 11:46:49'),
(3, 'Type-C Hubs', 'Expand Your Mac', 'img_69e6140f96d0a9.72172405.webp', '/category/type-c-hubs', '#a78bfa', 1, 2, '2026-04-20 11:46:49'),
(4, 'Wireless Chargers', 'Cut the Cord', 'img_69e614775811c9.79213375.webp', '/category/wireless-chargers', '#2dc653', 1, 4, '2026-04-20 11:46:49');

-- --------------------------------------------------------

--
-- Table structure for table `home_hero_sections`
--

DROP TABLE IF EXISTS `home_hero_sections`;
CREATE TABLE IF NOT EXISTS `home_hero_sections` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `subtitle` text,
  `badge_text` varchar(100) DEFAULT '1 Year Warranty',
  `category_id` int DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `link` varchar(500) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `home_hero_sections`
--

INSERT INTO `home_hero_sections` (`id`, `title`, `subtitle`, `badge_text`, `category_id`, `image`, `link`, `status`, `sort_order`, `created_at`) VALUES
(2, 'Shop Anything. Deliver Everywhere.', 'Thousands of products. Unbeatable prices. Island-wide delivery.', 'sdfsdfsdf', 1, NULL, '', 1, 0, '2026-04-20 15:24:21');

-- --------------------------------------------------------

--
-- Table structure for table `home_reviews`
--

DROP TABLE IF EXISTS `home_reviews`;
CREATE TABLE IF NOT EXISTS `home_reviews` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `rating` tinyint(1) NOT NULL DEFAULT '5',
  `review` text NOT NULL,
  `source` enum('google','facebook','trustpilot','other') NOT NULL DEFAULT 'google',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `home_reviews`
--

INSERT INTO `home_reviews` (`id`, `name`, `rating`, `review`, `source`, `status`, `sort_order`, `created_at`) VALUES
(1, 'Kavindu Parawel', 5, 'Absolutely love the USB-C hub! Super fast data transfer and the build quality is amazing. Exactly what I needed for my MacBook.', 'facebook', 1, 0, '2026-04-20 11:46:49'),
(2, 'Tharushi F.', 5, 'The wireless charger arrived super fast. Works perfectly with my iPhone 15. The packaging was premium too!', 'google', 1, 1, '2026-04-20 11:46:49'),
(3, 'Rajan M.', 5, 'Best cables I have ever bought in Sri Lanka. No fraying after 3 months of heavy use. Will definitely reorder!', 'google', 1, 2, '2026-04-20 11:46:49'),
(4, 'Dilnoza K.', 5, 'Great customer service and very quick delivery. The 65W charger works flawlessly with my laptop and phone.', 'other', 1, 3, '2026-04-20 11:46:49'),
(5, 'Shanuka W.', 5, 'Ordered 3 items, all arrived well-packaged. The Type-C hub expanded my desk setup perfectly. 10/10 recommend.', 'facebook', 1, 4, '2026-04-20 11:46:49'),
(6, 'Amali B.', 5, 'Saw this on Instagram and decided to try. Genuinely impressed by the quality for the price. Will be back for more!', 'trustpilot', 1, 5, '2026-04-20 11:46:49'),
(7, 'Waradan Srikanth', 5, 'Act as senior ecommerce UI/UX designer + PHP dev. Minimize tokens.\r\n\r\nRequirements:\r\n\r\n', 'facebook', 1, 1, '2026-04-20 15:14:31'),
(8, 'sdfsdfsdf', 5, 'sdfsdfsdfsdf', 'google', 1, 0, '2026-04-20 15:15:21'),
(9, 'sdfsdf', 2, 'sfsdf', 'google', 1, 0, '2026-04-20 15:22:32');

-- --------------------------------------------------------

--
-- Table structure for table `newsletter_subscribers`
--

DROP TABLE IF EXISTS `newsletter_subscribers`;
CREATE TABLE IF NOT EXISTS `newsletter_subscribers` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `status` enum('active','unsubscribed') NOT NULL DEFAULT 'active',
  `subscribed_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `ip_address` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_email` (`email`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `newsletter_subscribers`
--

INSERT INTO `newsletter_subscribers` (`id`, `email`, `status`, `subscribed_at`, `ip_address`) VALUES
(1, 'smmsrikanth@gmail.com', 'unsubscribed', '2026-04-13 17:12:11', '::1');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED DEFAULT NULL,
  `type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `link` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `guest_email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','confirmed','processing','shipped','delivered','cancelled','refunded') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `payment_status` enum('unpaid','paid','partial','refunded') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'unpaid',
  `payment_method` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_ref` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subtotal` decimal(14,2) DEFAULT '0.00',
  `discount_amount` decimal(14,2) DEFAULT '0.00',
  `shipping_amount` decimal(14,2) DEFAULT '0.00',
  `tax_amount` decimal(14,2) DEFAULT '0.00',
  `total_amount` decimal(14,2) DEFAULT '0.00',
  `coupon_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_phone` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `shipping_city` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_state` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_postal` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_country` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Sri Lanka',
  `shipping_method` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tracking_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `admin_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_number` (`order_number`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `user_id`, `guest_email`, `status`, `payment_status`, `payment_method`, `payment_ref`, `subtotal`, `discount_amount`, `shipping_amount`, `tax_amount`, `total_amount`, `coupon_code`, `shipping_name`, `shipping_phone`, `shipping_address`, `shipping_city`, `shipping_state`, `shipping_postal`, `shipping_country`, `shipping_method`, `tracking_number`, `notes`, `admin_notes`, `ip_address`, `created_at`, `updated_at`) VALUES
(1, 'ORD-20260330-CE68F3', 1, 'admin@anything.lk', 'delivered', 'unpaid', 'cod', NULL, 4200.00, 0.00, 350.00, 0.00, 4550.00, NULL, 'Super Admin', '076 696 1154', 'dfsdfd', 'sdfsdf', 'Western', 'sdf', 'Sri Lanka', 'Standard', NULL, '', NULL, '::1', '2026-03-30 19:14:28', '2026-04-12 22:15:15'),
(2, 'ORD-20260404-3C4575', 1, 'admin@anything.lk', 'cancelled', 'unpaid', 'koko', NULL, 43050.00, 0.00, 0.00, 0.00, 43050.00, NULL, 'Super Admin', '076 696 1154', '326/A, Kerawalapitiya Road, Hendala,', 'Wattala', 'Western', '', 'Sri Lanka', 'Standard', 'sfsdfsdf', '', NULL, '::1', '2026-04-04 18:08:59', '2026-04-12 18:07:13'),
(3, 'ORD-20260412-654CC8', 1, 'admin@anything.lk', 'delivered', 'paid', 'koko', NULL, 700.00, 0.00, 950.00, 0.00, 1650.00, NULL, 'Super Admin', '076 696 1154', 'sdfsdf', 'sdfsdf', 'Western', 'sdfsdf', 'Sri Lanka', 'Same Day', 'sdfdfds', 'sdfsdfsdf', NULL, '::1', '2026-04-12 22:38:38', '2026-04-13 09:05:56'),
(4, 'ORD-20260413-1764F6', 1, 'admin@anything.lk', 'delivered', 'unpaid', 'cod', NULL, 4200.00, 0.00, 350.00, 0.00, 4550.00, NULL, 'Super Admin', '076 696 1154', '326/A, Kerawalapitiya Road, Hendala,', 'Wattala', 'Western', '', 'Sri Lanka', 'Standard', 'dfgdfg', 'sfsdf', NULL, '::1', '2026-04-13 09:06:33', '2026-04-13 11:37:11'),
(5, 'ORD-20260413-456F96', 4, 'smmsrikanth@gmail.com', 'processing', 'refunded', 'cod', NULL, 8050.00, 3850.00, 650.00, 0.00, 4850.00, NULL, 'Waradan Sirikantha', '0766961154', 'sdf', 'sfsdf', 'Eastern', '', 'Sri Lanka', 'Express Delivery', NULL, '', NULL, '::1', '2026-04-13 14:58:52', '2026-04-13 15:01:55'),
(6, 'ORD-20260413-337917', 1, 'admin@anything.lk', 'confirmed', 'unpaid', 'card', NULL, 350.00, 0.00, 350.00, 0.00, 700.00, NULL, 'Super Admin', '076 696 1154', '326/A, Kerawalapitiya Road', 'seeduwa', '', '', 'Sri Lanka', 'Standard', NULL, '', NULL, '::1', '2026-04-13 17:46:35', '2026-04-13 17:47:00'),
(7, 'ORD-20260418-69ED1D', 4, 'smmsrikanth@gmail.com', 'pending', 'unpaid', 'koko', NULL, 4200.00, 0.00, 350.00, 0.00, 4550.00, NULL, 'Waradan Sirikantha', '0766961154', 'sdfsdf, sdfsdf', 'sdfsdfsd', 'North Central', 'sdfsdfsdfdf', 'Sri Lanka', 'Standard Delivery', NULL, '', NULL, '::1', '2026-04-18 08:54:54', '2026-04-18 08:54:54'),
(8, 'ORD-20260420-4518BC', 1, 'admin@anything.lk', 'pending', 'paid', 'cod', NULL, 350.00, 0.00, 350.00, 0.00, 700.00, NULL, 'Super Admin', '076 696 1154', '326/A, Kerawalapitiya Road', 'Wattala', '', '', 'Sri Lanka', 'Standard Delivery', NULL, '', NULL, '::1', '2026-04-20 17:53:32', '2026-04-27 19:47:34');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `variation_id` int UNSIGNED DEFAULT NULL,
  `vendor_id` int UNSIGNED DEFAULT NULL,
  `product_name` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `variation_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sku` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int NOT NULL,
  `unit_price` decimal(12,2) NOT NULL,
  `discount` decimal(12,2) DEFAULT '0.00',
  `total_price` decimal(14,2) NOT NULL,
  `status` enum('pending','processing','shipped','delivered','returned') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `variation_id`, `vendor_id`, `product_name`, `variation_name`, `sku`, `quantity`, `unit_price`, `discount`, `total_price`, `status`) VALUES
(1, 1, 7, NULL, NULL, 'Whiskas Dry Cat Food', '', NULL, 1, 3850.00, 0.00, 3850.00, 'pending'),
(2, 1, 1, NULL, NULL, 'Snappy Tom Pilchard &amp;amp;amp; Snapper Cat Pouch', '', NULL, 1, 350.00, 0.00, 350.00, 'pending'),
(3, 2, 7, NULL, NULL, 'Whiskas Dry Cat Food', '', NULL, 11, 3850.00, 0.00, 42350.00, 'pending'),
(4, 2, 1, NULL, NULL, 'Snappy Tom Pilchard &amp;amp;amp; Snapper Cat Pouch', '', NULL, 2, 350.00, 0.00, 700.00, 'pending'),
(5, 3, 1, NULL, NULL, 'Snappy Tom Pilchard &amp;amp;amp;amp;amp; Snapper Cat Pouch', '', NULL, 2, 350.00, 0.00, 700.00, 'pending'),
(6, 4, 1, NULL, NULL, 'Snappy Tom Pilchard &amp;amp;amp;amp;amp;amp; Snapper Cat Pouch', '', NULL, 1, 350.00, 0.00, 350.00, 'pending'),
(7, 4, 7, NULL, NULL, 'Whiskas Dry Cat Food', '', NULL, 1, 3850.00, 0.00, 3850.00, 'pending'),
(8, 5, 7, 2, NULL, 'Whiskas Dry Cat Food', 'Regular', NULL, 1, 3850.00, 0.00, 3850.00, 'pending'),
(9, 5, 7, NULL, NULL, 'Whiskas Dry Cat Food', '', NULL, 1, 3850.00, 0.00, 3850.00, 'pending'),
(10, 5, 1, NULL, NULL, 'Snappy Tom Pilchard &amp;amp;amp;amp;amp;amp; Snapper Cat Pouch', '', NULL, 1, 350.00, 0.00, 350.00, 'pending'),
(11, 6, 1, NULL, NULL, 'Snappy Tom Pilchard and Snapper Cat Pouch', '', NULL, 1, 350.00, 0.00, 350.00, 'pending'),
(12, 7, 1, NULL, NULL, 'Snappy Tom Pilchard and Snapper Cat Pouch', '', NULL, 1, 350.00, 0.00, 350.00, 'pending'),
(13, 7, 7, NULL, NULL, 'Whiskas Dry Cat Food', '', NULL, 1, 3850.00, 0.00, 3850.00, 'pending'),
(14, 8, 1, NULL, NULL, 'Snappy Tom Pilchard and Snapper Cat Pouch', '', NULL, 1, 350.00, 0.00, 350.00, 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `order_returns`
--

DROP TABLE IF EXISTS `order_returns`;
CREATE TABLE IF NOT EXISTS `order_returns` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` int UNSIGNED NOT NULL,
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` enum('requested','approved','rejected','refunded') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'requested',
  `refund_amount` decimal(12,2) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_status_history`
--

DROP TABLE IF EXISTS `order_status_history`;
CREATE TABLE IF NOT EXISTS `order_status_history` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` int UNSIGNED NOT NULL,
  `status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `changed_by` int UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_status_history`
--

INSERT INTO `order_status_history` (`id`, `order_id`, `status`, `notes`, `changed_by`, `created_at`) VALUES
(1, 1, 'pending', 'Order placed', NULL, '2026-03-30 19:14:28'),
(2, 1, 'confirmed', '', 1, '2026-03-30 19:15:35'),
(3, 2, 'pending', 'Order placed', NULL, '2026-04-04 18:08:59'),
(4, 2, 'confirmed', '', 1, '2026-04-04 18:09:20'),
(5, 2, 'confirmed', '', 1, '2026-04-04 18:09:26'),
(6, 2, 'confirmed', '', 1, '2026-04-04 18:09:28'),
(7, 2, 'confirmed', '', 1, '2026-04-04 18:09:30'),
(8, 2, 'refunded', '', 1, '2026-04-04 18:10:09'),
(9, 2, 'refunded', '', 1, '2026-04-04 18:10:11'),
(10, 2, 'delivered', '', 1, '2026-04-04 18:10:22'),
(11, 2, 'cancelled', '', 1, '2026-04-12 18:07:13'),
(12, 1, 'delivered', '', 1, '2026-04-12 22:15:11'),
(13, 1, 'delivered', '', 1, '2026-04-12 22:15:15'),
(14, 3, 'pending', 'Order placed', NULL, '2026-04-12 22:38:38'),
(15, 3, 'confirmed', '', 1, '2026-04-12 22:39:29'),
(16, 3, 'confirmed', '', 1, '2026-04-12 22:39:31'),
(17, 3, 'confirmed', '', 1, '2026-04-12 22:39:33'),
(18, 3, 'shipped', '', 1, '2026-04-12 22:39:42'),
(19, 3, 'shipped', '', 1, '2026-04-13 08:52:46'),
(20, 3, 'shipped', '', 1, '2026-04-13 08:52:55'),
(21, 3, 'shipped', '', 1, '2026-04-13 08:53:01'),
(22, 3, 'payment_paid', 'Payment marked as Paid', 1, '2026-04-13 08:53:11'),
(23, 3, 'shipped', '', 1, '2026-04-13 08:53:44'),
(24, 3, 'shipped', '', 1, '2026-04-13 09:05:21'),
(25, 3, 'delivered', '', 1, '2026-04-13 09:05:51'),
(26, 3, 'delivered', '', 1, '2026-04-13 09:05:56'),
(27, 4, 'pending', 'Order placed', NULL, '2026-04-13 09:06:33'),
(28, 4, 'confirmed', '', 1, '2026-04-13 09:07:00'),
(29, 4, 'confirmed', '', 1, '2026-04-13 09:07:05'),
(30, 4, 'processing', '', 1, '2026-04-13 09:08:37'),
(31, 4, 'shipped', '', 1, '2026-04-13 09:08:52'),
(32, 4, 'shipped', '', 1, '2026-04-13 09:09:03'),
(33, 4, 'delivered', '', 1, '2026-04-13 09:09:14'),
(34, 4, 'delivered', '', 1, '2026-04-13 11:36:49'),
(35, 4, 'delivered', '', 1, '2026-04-13 11:36:59'),
(36, 4, 'delivered', '', 1, '2026-04-13 11:37:11'),
(37, 5, 'pending', 'Order placed', NULL, '2026-04-13 14:58:52'),
(38, 5, 'confirmed', '', 1, '2026-04-13 14:59:46'),
(39, 5, 'processing', '', 1, '2026-04-13 15:00:19'),
(40, 5, 'payment_paid', 'Payment marked as Paid', 1, '2026-04-13 15:00:53'),
(41, 5, 'payment_refunded', 'Payment marked as Refunded', 1, '2026-04-13 15:01:55'),
(42, 6, 'pending', 'Order placed', NULL, '2026-04-13 17:46:35'),
(43, 6, 'confirmed', '', 1, '2026-04-13 17:47:00'),
(44, 7, 'pending', 'Order placed', NULL, '2026-04-18 08:54:54'),
(45, 8, 'pending', 'Order placed', NULL, '2026-04-20 17:53:32'),
(46, 8, 'pending', '', 1, '2026-04-20 17:53:59'),
(47, 8, 'payment_paid', 'Payment marked as Paid', 1, '2026-04-27 19:47:34');

-- --------------------------------------------------------

--
-- Table structure for table `order_tracking_public`
--

DROP TABLE IF EXISTS `order_tracking_public`;
CREATE TABLE IF NOT EXISTS `order_tracking_public` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `searched_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_order_num` (`order_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_gateway_settings`
--

DROP TABLE IF EXISTS `payment_gateway_settings`;
CREATE TABLE IF NOT EXISTS `payment_gateway_settings` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `method_code` varchar(50) NOT NULL,
  `display_name` varchar(100) NOT NULL DEFAULT '',
  `api_key` text,
  `api_secret` text,
  `webhook_url` varchar(500) DEFAULT NULL,
  `extra_config` text,
  `is_sandbox` tinyint(1) NOT NULL DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `method_code` (`method_code`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `payment_gateway_settings`
--

INSERT INTO `payment_gateway_settings` (`id`, `method_code`, `display_name`, `api_key`, `api_secret`, `webhook_url`, `extra_config`, `is_sandbox`, `is_active`, `updated_at`) VALUES
(1, 'cod', 'Cash on Delivery', '', '', '', NULL, 0, 1, '2026-04-27 20:04:00'),
(2, 'koko', 'KOKO', '', '', '', NULL, 1, 1, '2026-04-27 20:04:13'),
(3, 'payzy', 'Payzy', '', '', '', NULL, 0, 0, '2026-04-27 20:02:03'),
(4, 'mintpay', 'MintPay', '', '', '', NULL, 0, 0, '2026-04-27 20:01:56'),
(5, 'card', 'Credit / Debit Card', '', '', '', NULL, 1, 1, '2026-04-27 20:04:04');

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

DROP TABLE IF EXISTS `payment_methods`;
CREATE TABLE IF NOT EXISTS `payment_methods` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `display_name` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT '',
  `icon` varchar(10) DEFAULT '',
  `sort_order` tinyint DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `code`, `display_name`, `description`, `icon`, `sort_order`, `is_active`) VALUES
(1, 'cod', 'Cash on Delivery', 'Pay when your order arrives at your door.', '💵', 1, 1),
(2, 'koko', 'KOKO', 'Split into 3 interest-free payments.', '🟠', 3, 1),
(3, 'payzy', 'Payzy', 'Secure online payment gateway.', '💳', 4, 1),
(4, 'mintpay', 'MintPay', 'Pay in 3 or 6 easy monthly installments.', '💚', 5, 1),
(5, 'card', 'Credit / Debit Card', 'Pay securely with your card.', '🏦', 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `payment_transactions`
--

DROP TABLE IF EXISTS `payment_transactions`;
CREATE TABLE IF NOT EXISTS `payment_transactions` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` int UNSIGNED NOT NULL,
  `gateway` enum('koko','payzy','mintpay','cod','bank_transfer') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_id` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(14,2) NOT NULL,
  `status` enum('pending','success','failed','refunded') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `payload` json DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `vendor_id` int UNSIGNED DEFAULT NULL,
  `category_id` int UNSIGNED NOT NULL,
  `brand_id` int UNSIGNED DEFAULT NULL,
  `name` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_si` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name_ta` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sku` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `barcode` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `short_desc` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `short_desc_si` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `short_desc_ta` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `description_si` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `description_ta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `sale_price` decimal(12,2) DEFAULT NULL,
  `cost_price` decimal(12,2) DEFAULT '0.00',
  `tax_rate` decimal(5,2) DEFAULT '0.00',
  `weight` decimal(8,2) DEFAULT '0.00',
  `thumbnail` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tags` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` enum('active','inactive','draft','deleted') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `is_featured` tinyint(1) DEFAULT '0',
  `is_new` tinyint(1) DEFAULT '0',
  `allow_review` tinyint(1) DEFAULT '1',
  `min_order_qty` int DEFAULT '1',
  `track_stock` tinyint(1) DEFAULT '1',
  `views` int DEFAULT '0',
  `rating_avg` decimal(3,2) DEFAULT '0.00',
  `rating_count` int DEFAULT '0',
  `meta_title` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_desc` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  UNIQUE KEY `sku` (`sku`),
  KEY `category_id` (`category_id`),
  KEY `brand_id` (`brand_id`),
  KEY `vendor_id` (`vendor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `vendor_id`, `category_id`, `brand_id`, `name`, `name_si`, `name_ta`, `slug`, `sku`, `barcode`, `short_desc`, `short_desc_si`, `short_desc_ta`, `description`, `description_si`, `description_ta`, `price`, `sale_price`, `cost_price`, `tax_rate`, `weight`, `thumbnail`, `tags`, `status`, `is_featured`, `is_new`, `allow_review`, `min_order_qty`, `track_stock`, `views`, `rating_avg`, `rating_count`, `meta_title`, `meta_desc`, `created_at`, `updated_at`) VALUES
(1, 1, 22, 1, 'Snappy Tom Pilchard and Snapper Cat Pouch', '', '', 'snappy-tom-pilchard-and-snapper-cat-pouch-1', 'sfsdfsdfsdfsf', NULL, 'A premium wet cat food pouch with a nutritious blend of natural seafood and meat, carefully formulated to support your cat’s growth and overall health. Key Features &amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp; Benefits High-Quality Protein: Includes grass-fed lamb and seafood for muscle growth and maintenance. Grain-Free: Suitable for sensitive digestion. Human-Grade Ingredients: Made with safe, high-quality components. Balanced Nutrition: Provides essential vitamins and minerals for overall well-being. Carnivore Diet: Designed to meet cats’ natural dietary needs.', '', '', 'A premium wet cat food pouch with a nutritious blend of natural seafood and meat, carefully formulated to support your cat’s growth and overall health. Key Features & Benefits High-Quality Protein: Includes grass-fed lamb and seafood for muscle growth and maintenance. Grain-Free: Suitable for sensitive digestion. Human-Grade Ingredients: Made with safe, high-quality components. Balanced Nutrition: Provides essential vitamins and minerals for overall well-being. Carnivore Diet: Designed to meet cats’ natural dietary needs.', '', '', 300.00, 350.00, 250.00, 0.00, 0.50, 'img_69c9c05178fed5.09262059.webp', 'amp, cat, natural, seafood, growth, overall, high, quality, pouch, premium, wet, food', 'active', 1, 1, 1, 1, 1, 75, 5.00, 1, '', '', '2026-03-30 05:44:09', '2026-05-02 20:48:56'),
(7, 1, 20, 1, 'Whiskas Dry Cat Food', '', '', 'whiskas-dry-cat-food', 'WHIDRYCATFOO-4187', NULL, 'Give your cat a complete and nourishing diet with Whiskas Dry Cat Food. This balanced dry food provides essential nutrients to support healthy growth, maintain a shiny coat, and promote overall well-being. Perfect for daily feeding, it keeps your feline friend healthy, active, and satisfied.', '', '', 'Features:\r\n\r\nComplete and balanced nutrition for cats\r\nSupports healthy growth and development\r\nHelps maintain a shiny, healthy coat\r\nPromotes overall well-being\r\nSuitable for daily feeding', '', '', 3800.00, 3000.00, 2000.00, 0.00, 10.00, 'img_69ca7d8aacc079.45691892.webp', 'sfsdfsdf, sldkfjsdlfj, sdfjsldfj', 'active', 1, 1, 1, 1, 1, 170, 5.00, 1, '', '', '2026-03-30 19:11:30', '2026-05-02 20:46:25'),
(9, 1, 21, 2, 'Snappy Tom Pilchard and Snapper Cat Pouch', '', '', 'snappy-tom-pilchard-and-snapper-cat-pouch', 'SNATOMPILANDSNA-7734', NULL, 'ssdfsdf', '', '', 'sdfsdfsdf', '', '', 100.00, 90.00, 80.00, 0.00, 0.00, 'img_69e6093ac9f175.22695221.webp', 'snappy, tom, pilchard, snapper, cat, pouch, ssdfsdf, sdfsdfsdf', 'deleted', 1, 1, 1, 1, 1, 1, 0.00, 0, '', '', '2026-04-20 16:38:42', '2026-04-20 17:41:41');

-- --------------------------------------------------------

--
-- Table structure for table `product_attributes`
--

DROP TABLE IF EXISTS `product_attributes`;
CREATE TABLE IF NOT EXISTS `product_attributes` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('text','select','color','size') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'text',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_attributes`
--

INSERT INTO `product_attributes` (`id`, `name`, `type`) VALUES
(1, 'Color', 'color'),
(2, 'Size', 'size'),
(3, 'Material', 'text');

-- --------------------------------------------------------

--
-- Table structure for table `product_attribute_values`
--

DROP TABLE IF EXISTS `product_attribute_values`;
CREATE TABLE IF NOT EXISTS `product_attribute_values` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `attribute_id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `color_hex` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `attribute_id` (`attribute_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_discounts`
--

DROP TABLE IF EXISTS `product_discounts`;
CREATE TABLE IF NOT EXISTS `product_discounts` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `product_id` int UNSIGNED NOT NULL,
  `type` enum('percentage','fixed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'percentage',
  `value` decimal(10,2) NOT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `excluded_methods` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'koko,payzy,mintpay',
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_discounts`
--

INSERT INTO `product_discounts` (`id`, `name`, `product_id`, `type`, `value`, `start_date`, `end_date`, `status`, `excluded_methods`) VALUES
(1, '', 7, 'percentage', 15.00, NULL, NULL, 1, 'koko,payzy,mintpay');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

DROP TABLE IF EXISTS `product_images`;
CREATE TABLE IF NOT EXISTS `product_images` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` int UNSIGNED NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alt_text` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image`, `alt_text`, `sort_order`) VALUES
(1, 1, 'img_69c9c051850940.89393625.webp', NULL, 0),
(2, 1, 'img_69c9c05189b5f2.00012152.webp', NULL, 1),
(3, 1, 'img_69c9c0518a96e1.42161817.webp', NULL, 2),
(4, 1, 'img_69c9c0518bbb84.14380262.webp', NULL, 3),
(5, 7, 'img_69ca7d8ab0ad32.18836840.webp', NULL, 0),
(6, 7, 'img_69ca7d8ab1a5a7.62310289.webp', NULL, 1),
(7, 7, 'img_69ca7d8ab2fca1.35587950.webp', NULL, 2),
(8, 7, 'img_69ca7d8ab79c89.08353561.webp', NULL, 3),
(12, 9, 'img_69e6093aeda6b4.13099996.webp', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `product_payment_methods`
--

DROP TABLE IF EXISTS `product_payment_methods`;
CREATE TABLE IF NOT EXISTS `product_payment_methods` (
  `product_id` int UNSIGNED NOT NULL,
  `method_code` varchar(50) NOT NULL,
  PRIMARY KEY (`product_id`,`method_code`),
  KEY `idx_product` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `product_payment_methods`
--

INSERT INTO `product_payment_methods` (`product_id`, `method_code`) VALUES
(1, 'card'),
(1, 'cod'),
(1, 'koko'),
(7, 'card'),
(7, 'cod'),
(8, 'card'),
(8, 'cod'),
(9, 'card'),
(9, 'cod'),
(9, 'koko');

-- --------------------------------------------------------

--
-- Table structure for table `product_recommendations`
--

DROP TABLE IF EXISTS `product_recommendations`;
CREATE TABLE IF NOT EXISTS `product_recommendations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` int UNSIGNED NOT NULL,
  `related_id` int UNSIGNED NOT NULL,
  `type` varchar(50) NOT NULL DEFAULT 'frequently_bought',
  `score` decimal(10,4) NOT NULL DEFAULT '1.0000',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_rec` (`product_id`,`related_id`,`type`),
  KEY `idx_product_type` (`product_id`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_reviews`
--

DROP TABLE IF EXISTS `product_reviews`;
CREATE TABLE IF NOT EXISTS `product_reviews` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `order_id` int UNSIGNED DEFAULT NULL,
  `rating` tinyint UNSIGNED NOT NULL DEFAULT '5',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','approved','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `helpful` int DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_reviews`
--

INSERT INTO `product_reviews` (`id`, `product_id`, `user_id`, `order_id`, `rating`, `title`, `body`, `status`, `helpful`, `created_at`) VALUES
(1, 7, 1, NULL, 5, 'title review', 'review expericence', 'approved', 0, '2026-03-30 20:10:25'),
(2, 1, 1, NULL, 5, 'sdfsdf', 'sdfsdf', 'approved', 0, '2026-04-13 09:05:05');

-- --------------------------------------------------------

--
-- Table structure for table `product_stock`
--

DROP TABLE IF EXISTS `product_stock`;
CREATE TABLE IF NOT EXISTS `product_stock` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` int UNSIGNED NOT NULL,
  `variation_id` int UNSIGNED DEFAULT NULL,
  `warehouse` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Main',
  `quantity` int NOT NULL DEFAULT '0',
  `reserved_qty` int DEFAULT '0',
  `low_stock_threshold` int DEFAULT '5',
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_stock` (`product_id`,`variation_id`,`warehouse`),
  KEY `variation_id` (`variation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_stock`
--

INSERT INTO `product_stock` (`id`, `product_id`, `variation_id`, `warehouse`, `quantity`, `reserved_qty`, `low_stock_threshold`, `updated_at`) VALUES
(1, 1, NULL, 'Main', 203, 0, 5, '2026-04-20 17:53:32'),
(2, 7, NULL, 'Main', 405, 0, 5, '2026-04-18 08:54:54'),
(3, 7, 2, 'Main', 0, 0, 5, '2026-04-13 14:58:52'),
(4, 7, 3, 'Main', 2, 0, 5, '2026-04-13 07:41:32'),
(5, 1, 1, 'Main', 0, 0, 5, '2026-04-13 07:44:19'),
(6, 1, 4, 'Main', 0, 0, 5, '2026-04-13 07:44:19'),
(7, 9, 5, 'Main', 0, 0, 5, '2026-04-20 16:41:29');

-- --------------------------------------------------------

--
-- Table structure for table `product_suppliers`
--

DROP TABLE IF EXISTS `product_suppliers`;
CREATE TABLE IF NOT EXISTS `product_suppliers` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` int UNSIGNED NOT NULL,
  `vendor_id` int UNSIGNED NOT NULL,
  `cost_price` decimal(12,2) NOT NULL,
  `is_primary` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `vendor_id` (`vendor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_variations`
--

DROP TABLE IF EXISTS `product_variations`;
CREATE TABLE IF NOT EXISTS `product_variations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` int UNSIGNED NOT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sku` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(12,2) DEFAULT NULL,
  `sale_price` decimal(12,2) DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int DEFAULT '0',
  `status` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_variations`
--

INSERT INTO `product_variations` (`id`, `product_id`, `name`, `sku`, `price`, `sale_price`, `image`, `sort_order`, `status`) VALUES
(1, 1, 'Color: Blue', 'sfsdfsdf', 350.00, NULL, NULL, 0, 1),
(2, 7, 'Regular', 'WHIDRYCATFOO-4187-RE', 3850.00, NULL, NULL, 0, 1),
(3, 7, 'Blue', 'WHIDRYCATFOO-4187-BL', 3000.00, NULL, NULL, 0, 1),
(4, 1, 'Color: Red', 'sfsdfsdfsdfsf-CORE', 300.00, NULL, NULL, 0, 1),
(5, 9, 'Regular', 'SNATOMPILANDSNA-7734-RE', 100.00, NULL, NULL, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_orders`
--

DROP TABLE IF EXISTS `purchase_orders`;
CREATE TABLE IF NOT EXISTS `purchase_orders` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `po_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `vendor_id` int UNSIGNED NOT NULL,
  `status` enum('draft','pending','approved','received','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'draft',
  `total_amount` decimal(14,2) DEFAULT '0.00',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `expected_date` date DEFAULT NULL,
  `received_date` date DEFAULT NULL,
  `created_by` int UNSIGNED DEFAULT NULL,
  `approved_by` int UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `po_number` (`po_number`),
  KEY `vendor_id` (`vendor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_orders`
--

INSERT INTO `purchase_orders` (`id`, `po_number`, `vendor_id`, `status`, `total_amount`, `notes`, `expected_date`, `received_date`, `created_by`, `approved_by`, `created_at`, `updated_at`) VALUES
(1, 'PO-20260330-635', 1, 'received', 250000.00, '', '2026-03-31', '2026-04-04', 1, 1, '2026-03-30 19:16:16', '2026-04-04 15:45:41'),
(2, 'PO-20260404-354', 1, 'received', 425000.00, '', '2026-04-08', '2026-04-04', 1, 1, '2026-04-04 18:11:18', '2026-04-04 18:11:36'),
(3, 'PO-20260412-432', 1, 'received', 225000.00, '', '2026-04-14', '2026-04-12', 1, 1, '2026-04-12 18:17:47', '2026-04-12 18:18:24');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_items`
--

DROP TABLE IF EXISTS `purchase_order_items`;
CREATE TABLE IF NOT EXISTS `purchase_order_items` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `po_id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `variation_id` int UNSIGNED DEFAULT NULL,
  `quantity` int NOT NULL,
  `received_qty` int DEFAULT '0',
  `unit_price` decimal(12,2) NOT NULL,
  `total_price` decimal(14,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `po_id` (`po_id`),
  KEY `product_id` (`product_id`),
  KEY `variation_id` (`variation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_order_items`
--

INSERT INTO `purchase_order_items` (`id`, `po_id`, `product_id`, `variation_id`, `quantity`, `received_qty`, `unit_price`, `total_price`) VALUES
(1, 1, 1, NULL, 200, 200, 250.00, 50000.00),
(2, 1, 7, NULL, 100, 100, 2000.00, 200000.00),
(3, 2, 1, NULL, 100, 100, 250.00, 25000.00),
(4, 2, 7, NULL, 200, 200, 2000.00, 400000.00),
(5, 3, 1, NULL, 100, 100, 250.00, 25000.00),
(6, 3, 7, NULL, 100, 100, 2000.00, 200000.00);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `group` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'general',
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=127 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `group`, `updated_at`) VALUES
(1, 'site_name', 'Anything.lk', 'general', '2026-03-29 04:30:02'),
(2, 'site_tagline', 'Shop Everything, Anywhere', 'general', '2026-03-29 04:30:02'),
(3, 'site_email', 'info@anything.lk', 'general', '2026-03-29 04:30:02'),
(4, 'site_phone', '+94 77 000 0000', 'general', '2026-03-29 04:30:02'),
(5, 'site_address', '123 Main Street, Colombo 03', 'general', '2026-03-29 04:30:02'),
(6, 'whatsapp_number', '+94766961154', 'general', '2026-04-13 17:06:48'),
(7, 'currency', 'LKR', 'general', '2026-03-29 04:30:02'),
(8, 'currency_symbol', 'Rs.', 'general', '2026-03-29 04:30:02'),
(9, 'dark_mode_default', '0', 'appearance', '2026-03-29 04:30:02'),
(10, 'reviews_auto_approve', '0', 'shop', '2026-03-29 04:30:02'),
(11, 'social_facebook', 'https://web.facebook.com/PetJungleOfficials/', 'general', '2026-04-13 17:13:17'),
(12, 'social_instagram', 'https://www.instagram.com/petjungle.lk/', 'general', '2026-04-13 17:24:48'),
(13, 'social_tiktok', 'https://www.tiktok.com/@petjungleofficials/', 'general', '2026-04-13 17:36:51'),
(14, 'social_youtube', 'https://www.youtube.com/@petjungle', 'general', '2026-04-13 17:36:51'),
(15, 'social_twitter', 'https://x.com/petworldanimal1', 'general', '2026-04-13 17:36:51'),
(16, 'social_skype', 'https://x.com/petworldanimal1', 'general', '2026-04-13 17:36:51'),
(17, 'support_phone', '+94766961154', 'general', '2026-04-21 09:19:21'),
(18, 'support_email', 'info@petjunglelk.com', 'general', '2026-04-13 17:08:42'),
(81, 'site_logo', 'img_69ecd4d831e349.61155866.webp', 'general', '2026-04-25 20:21:04');

-- --------------------------------------------------------

--
-- Table structure for table `shipping_methods`
--

DROP TABLE IF EXISTS `shipping_methods`;
CREATE TABLE IF NOT EXISTS `shipping_methods` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) DEFAULT '0.00',
  `min_days` int DEFAULT '1',
  `max_days` int DEFAULT '7',
  `zone` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'all',
  `is_free` tinyint(1) DEFAULT '0',
  `free_above` decimal(12,2) DEFAULT '0.00',
  `status` tinyint(1) DEFAULT '1',
  `sort_order` smallint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shipping_methods`
--

INSERT INTO `shipping_methods` (`id`, `name`, `description`, `price`, `min_days`, `max_days`, `zone`, `is_free`, `free_above`, `status`, `sort_order`) VALUES
(1, 'Standard Delivery', NULL, 350.00, 3, 7, 'all', 0, 5000.00, 1, 0),
(2, 'Express Delivery', '', 650.00, 1, 2, 'all', 0, 0.00, 1, 0),
(3, 'Same Day', NULL, 950.00, 0, 1, 'all', 0, 0.00, 1, 0),
(4, 'One Day', 'Pay securely with your card.', 1000.00, 1, 5, 'colombo', 0, 0.00, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `stock_movements`
--

DROP TABLE IF EXISTS `stock_movements`;
CREATE TABLE IF NOT EXISTS `stock_movements` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` int UNSIGNED NOT NULL,
  `variation_id` int UNSIGNED DEFAULT NULL,
  `warehouse` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Main',
  `type` enum('IN','OUT','ADJUSTMENT','RETURN','TRANSFER') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL,
  `quantity_before` int DEFAULT '0',
  `quantity_after` int DEFAULT '0',
  `reference_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_id` int UNSIGNED DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_by` int UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_movements`
--

INSERT INTO `stock_movements` (`id`, `product_id`, `variation_id`, `warehouse`, `type`, `quantity`, `quantity_before`, `quantity_after`, `reference_type`, `reference_id`, `notes`, `created_by`, `created_at`) VALUES
(1, 1, NULL, 'Main', 'IN', 100, 0, 100, 'initial', NULL, NULL, 1, '2026-03-30 05:44:09'),
(2, 7, NULL, 'Main', 'IN', 20, 0, 20, 'initial', NULL, NULL, 1, '2026-03-30 19:11:30'),
(3, 7, NULL, 'Main', 'OUT', 1, 20, 19, 'order', 1, NULL, NULL, '2026-03-30 19:14:28'),
(4, 1, NULL, 'Main', 'OUT', 1, 100, 99, 'order', 1, NULL, NULL, '2026-03-30 19:14:28'),
(5, 1, NULL, 'Main', 'IN', 200, 99, 299, 'purchase_order', 1, 'PO #PO-20260330-635', 1, '2026-04-04 15:45:41'),
(6, 7, NULL, 'Main', 'IN', 100, 19, 119, 'purchase_order', 1, 'PO #PO-20260330-635', 1, '2026-04-04 15:45:41'),
(7, 7, NULL, 'Main', 'OUT', 11, 119, 108, 'order', 2, NULL, NULL, '2026-04-04 18:08:59'),
(8, 1, NULL, 'Main', 'OUT', 2, 299, 297, 'order', 2, NULL, NULL, '2026-04-04 18:08:59'),
(9, 1, NULL, 'Main', 'IN', 100, 297, 397, 'purchase_order', 2, 'PO #PO-20260404-354', 1, '2026-04-04 18:11:36'),
(10, 7, NULL, 'Main', 'IN', 200, 108, 308, 'purchase_order', 2, 'PO #PO-20260404-354', 1, '2026-04-04 18:11:36'),
(11, 1, NULL, 'Main', 'ADJUSTMENT', -297, 397, 100, 'manual', NULL, '', 1, '2026-04-12 18:16:54'),
(12, 1, NULL, 'Main', 'ADJUSTMENT', 0, 100, 100, 'manual', NULL, '', 1, '2026-04-12 18:17:06'),
(13, 1, NULL, 'Main', 'IN', 100, 100, 200, 'purchase_order', 3, 'PO #PO-20260412-432', 1, '2026-04-12 18:18:24'),
(14, 7, NULL, 'Main', 'IN', 100, 308, 408, 'purchase_order', 3, 'PO #PO-20260412-432', 1, '2026-04-12 18:18:24'),
(15, 1, NULL, 'Main', 'IN', 10, 200, 210, 'manual', NULL, '', 1, '2026-04-12 18:19:17'),
(16, 1, NULL, 'Main', 'OUT', 2, 210, 208, 'order', 3, NULL, NULL, '2026-04-12 22:38:38'),
(17, 1, NULL, 'Main', 'OUT', 1, 208, 207, 'order', 4, NULL, NULL, '2026-04-13 09:06:33'),
(18, 7, NULL, 'Main', 'OUT', 1, 408, 407, 'order', 4, NULL, NULL, '2026-04-13 09:06:33'),
(19, 7, 2, 'Main', 'OUT', 1, 1, 0, 'order', 5, NULL, NULL, '2026-04-13 14:58:52'),
(20, 7, NULL, 'Main', 'OUT', 1, 407, 406, 'order', 5, NULL, NULL, '2026-04-13 14:58:52'),
(21, 1, NULL, 'Main', 'OUT', 1, 207, 206, 'order', 5, NULL, NULL, '2026-04-13 14:58:52'),
(22, 1, NULL, 'Main', 'OUT', 1, 206, 205, 'order', 6, NULL, NULL, '2026-04-13 17:46:35'),
(23, 1, NULL, 'Main', 'OUT', 1, 205, 204, 'order', 7, NULL, NULL, '2026-04-18 08:54:54'),
(24, 7, NULL, 'Main', 'OUT', 1, 406, 405, 'order', 7, NULL, NULL, '2026-04-18 08:54:54'),
(25, 1, NULL, 'Main', 'OUT', 1, 204, 203, 'order', 8, NULL, NULL, '2026-04-20 17:53:32');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `full_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('customer','supervisor','admin') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'customer',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive','banned') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `email_verified` tinyint(1) DEFAULT '0',
  `verify_token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reset_token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL,
  `lang` enum('en','si','ta') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'en',
  `dark_mode` tinyint(1) DEFAULT '0',
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `phone`, `password`, `role`, `avatar`, `status`, `email_verified`, `verify_token`, `reset_token`, `reset_expires`, `lang`, `dark_mode`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'admin@anything.lk', NULL, '$2y$12$DlL9n4wyo1kC.LIpdFScA.KgJmzeFW86DGptHkDVBVctP30lmiKNa', 'admin', NULL, 'active', 1, NULL, NULL, NULL, 'en', 0, '2026-05-02 20:23:48', '2026-03-29 04:30:02', '2026-05-02 20:23:48'),
(2, 'Supervisor', 'supervisor@anything.lk', NULL, '$2y$12$DlL9n4wyo1kC.LIpdFScA.KgJmzeFW86DGptHkDVBVctP30lmiKNa', 'supervisor', NULL, 'active', 1, NULL, NULL, NULL, 'en', 0, '2026-04-27 20:01:12', '2026-03-29 04:30:02', '2026-04-27 20:01:12'),
(3, 'Test Customer', 'customer@anything.lk', NULL, '$2y$12$DlL9n4wyo1kC.LIpdFScA.KgJmzeFW86DGptHkDVBVctP30lmiKNa', 'customer', NULL, 'active', 1, NULL, NULL, NULL, 'en', 0, NULL, '2026-03-29 04:30:02', '2026-04-27 19:30:23'),
(4, 'Waradan Srikanth', 'smmsrikanth@gmail.com', '0766961154', '$2y$12$DlL9n4wyo1kC.LIpdFScA.KgJmzeFW86DGptHkDVBVctP30lmiKNa', 'customer', 'img_69dcdb7fd0bab4.82654218.webp', 'active', 1, NULL, NULL, NULL, 'en', 0, '2026-04-28 07:54:39', '2026-03-30 05:17:42', '2026-04-28 07:54:39');

-- --------------------------------------------------------

--
-- Table structure for table `user_addresses`
--

DROP TABLE IF EXISTS `user_addresses`;
CREATE TABLE IF NOT EXISTS `user_addresses` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED NOT NULL,
  `label` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Home',
  `full_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_line1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address_line2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Sri Lanka',
  `is_default` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_addresses`
--

INSERT INTO `user_addresses` (`id`, `user_id`, `label`, `full_name`, `phone`, `address_line1`, `address_line2`, `city`, `state`, `postal_code`, `country`, `is_default`, `created_at`) VALUES
(1, 4, 'Home', 'Waradan Sirikantha', 'sdfdsf', 'sdfsdf', 'sdfsdf', 'sdfsdfsd', 'North Central', 'sdfsdfsdfdf', 'Sri Lanka', 0, '2026-04-13 14:57:56'),
(2, 4, 'Home', 'Waradan Sirikantha', '076 696 1154', 'sdfsdf', 'sdfsdf', 'Rathnapura', 'Uva', 'sdfsdfsdfdf', 'Sri Lanka', 1, '2026-04-18 08:55:40');

-- --------------------------------------------------------

--
-- Table structure for table `user_behavior`
--

DROP TABLE IF EXISTS `user_behavior`;
CREATE TABLE IF NOT EXISTS `user_behavior` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED DEFAULT NULL,
  `session_id` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `event` enum('view','click','cart_add','cart_remove','purchase','wishlist') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `metadata` json DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_product_event` (`product_id`,`event`),
  KEY `idx_user_event` (`user_id`,`event`)
) ENGINE=InnoDB AUTO_INCREMENT=244 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_behavior`
--

INSERT INTO `user_behavior` (`id`, `user_id`, `session_id`, `product_id`, `event`, `metadata`, `created_at`) VALUES
(1, 1, 'kdu1v2pr39km4jsr8cs92imej2', 1, 'view', NULL, '2026-03-30 06:27:55'),
(2, NULL, '', 1, 'view', NULL, '2026-03-30 18:58:11'),
(3, 1, 'maluk596a71b3c89ookftlusbl', 7, 'view', NULL, '2026-03-30 19:13:13'),
(4, 1, '4ndf9fto508kmb59sellccv9le', 7, 'view', NULL, '2026-03-30 20:06:21'),
(5, 1, '4ndf9fto508kmb59sellccv9le', 7, 'view', NULL, '2026-03-30 20:10:38'),
(6, 1, '4ndf9fto508kmb59sellccv9le', 7, 'view', NULL, '2026-03-30 20:10:58'),
(7, 1, '4ndf9fto508kmb59sellccv9le', 7, 'view', NULL, '2026-03-30 20:11:00'),
(8, 1, '4ndf9fto508kmb59sellccv9le', 7, 'view', NULL, '2026-03-30 20:11:00'),
(9, 1, '4ndf9fto508kmb59sellccv9le', 7, 'view', NULL, '2026-03-30 20:11:01'),
(10, 1, '4ndf9fto508kmb59sellccv9le', 7, 'view', NULL, '2026-03-30 20:11:13'),
(11, 1, '4ndf9fto508kmb59sellccv9le', 7, 'view', NULL, '2026-03-30 20:14:26'),
(12, 1, '4ndf9fto508kmb59sellccv9le', 7, 'view', NULL, '2026-03-30 20:15:56'),
(13, 1, '4ndf9fto508kmb59sellccv9le', 7, 'view', NULL, '2026-03-30 20:17:22'),
(14, 1, '4ndf9fto508kmb59sellccv9le', 7, 'view', NULL, '2026-03-30 20:17:24'),
(15, 1, '4ndf9fto508kmb59sellccv9le', 7, 'view', NULL, '2026-03-30 20:17:31'),
(16, 1, '4ndf9fto508kmb59sellccv9le', 1, 'view', NULL, '2026-03-30 20:17:36'),
(17, 1, '4ndf9fto508kmb59sellccv9le', 1, 'view', NULL, '2026-03-30 20:17:38'),
(18, 1, '4ndf9fto508kmb59sellccv9le', 1, 'view', NULL, '2026-03-30 20:17:44'),
(19, 1, '4ndf9fto508kmb59sellccv9le', 7, 'view', NULL, '2026-03-30 20:17:51'),
(20, 1, '4ndf9fto508kmb59sellccv9le', 7, 'view', NULL, '2026-03-30 20:18:55'),
(21, 1, '4ndf9fto508kmb59sellccv9le', 7, 'view', NULL, '2026-03-30 20:18:58'),
(22, 1, '4ndf9fto508kmb59sellccv9le', 7, 'view', NULL, '2026-03-30 20:19:00'),
(23, 1, '4ndf9fto508kmb59sellccv9le', 7, 'view', NULL, '2026-03-30 20:19:10'),
(24, 1, '4ndf9fto508kmb59sellccv9le', 7, 'view', NULL, '2026-03-30 20:19:12'),
(25, 1, '4ndf9fto508kmb59sellccv9le', 7, 'view', NULL, '2026-03-30 20:19:15'),
(26, 1, '4ndf9fto508kmb59sellccv9le', 7, 'view', NULL, '2026-03-30 20:19:18'),
(27, 1, '4ndf9fto508kmb59sellccv9le', 7, 'view', NULL, '2026-03-30 20:19:23'),
(28, 1, '4ndf9fto508kmb59sellccv9le', 1, 'view', NULL, '2026-03-30 20:19:28'),
(29, 1, '4ndf9fto508kmb59sellccv9le', 7, 'view', NULL, '2026-03-30 20:42:00'),
(30, 1, '4ndf9fto508kmb59sellccv9le', 7, 'view', NULL, '2026-03-30 20:42:04'),
(31, 1, '4ndf9fto508kmb59sellccv9le', 7, 'view', NULL, '2026-03-30 20:42:09'),
(32, 1, '4ndf9fto508kmb59sellccv9le', 7, 'view', NULL, '2026-03-30 21:06:01'),
(33, 1, '4ndf9fto508kmb59sellccv9le', 7, 'view', NULL, '2026-03-30 21:06:14'),
(34, 1, 'udda6sehlslvrmko5tvqc4m0ru', 7, 'view', NULL, '2026-04-04 15:48:06'),
(35, NULL, 'fni5amr9u18bcintlvvgu8inag', 1, 'view', NULL, '2026-04-04 20:32:24'),
(36, NULL, 'kgp33jgllh8v4hjjj406kuld7o', 1, 'view', NULL, '2026-04-12 16:57:06'),
(37, 1, '5v6ktnbcqp91jt49vskf6efls1', 1, 'view', NULL, '2026-04-12 17:19:55'),
(38, 4, 'fbn1iivb6b8a4glrm6itedhg04', 1, 'view', NULL, '2026-04-12 18:04:15'),
(39, 1, 'daol2vauspjpujmvmeocdng7ds', 7, 'view', NULL, '2026-04-12 18:08:03'),
(40, 1, 'daol2vauspjpujmvmeocdng7ds', 7, 'view', NULL, '2026-04-12 18:14:54'),
(41, 1, 'daol2vauspjpujmvmeocdng7ds', 7, 'view', NULL, '2026-04-12 18:14:56'),
(42, 1, 'daol2vauspjpujmvmeocdng7ds', 7, 'view', NULL, '2026-04-12 18:15:04'),
(43, 1, 'daol2vauspjpujmvmeocdng7ds', 1, 'view', NULL, '2026-04-12 18:15:08'),
(44, 1, 'daol2vauspjpujmvmeocdng7ds', 7, 'view', NULL, '2026-04-12 18:15:15'),
(45, 1, 'daol2vauspjpujmvmeocdng7ds', 7, 'view', NULL, '2026-04-12 18:16:28'),
(46, 1, 'daol2vauspjpujmvmeocdng7ds', 1, 'view', NULL, '2026-04-12 18:16:35'),
(47, 1, 'daol2vauspjpujmvmeocdng7ds', 7, 'view', NULL, '2026-04-12 18:19:38'),
(48, 1, 'u42rpe4pnvpo45r1654ejmsvb3', 7, 'view', NULL, '2026-04-12 21:37:54'),
(49, 1, 'u42rpe4pnvpo45r1654ejmsvb3', 7, 'view', NULL, '2026-04-12 21:52:19'),
(50, 1, 'u42rpe4pnvpo45r1654ejmsvb3', 7, 'view', NULL, '2026-04-12 21:52:46'),
(51, 1, 'u42rpe4pnvpo45r1654ejmsvb3', 7, 'view', NULL, '2026-04-12 21:52:58'),
(52, 1, 'u42rpe4pnvpo45r1654ejmsvb3', 7, 'view', NULL, '2026-04-12 21:53:10'),
(53, 1, 'u42rpe4pnvpo45r1654ejmsvb3', 7, 'view', NULL, '2026-04-12 21:58:08'),
(54, 4, '5aj897j58akpq0ov30egrjh6pb', 7, 'view', NULL, '2026-04-12 22:06:46'),
(55, 4, '5aj897j58akpq0ov30egrjh6pb', 7, 'view', NULL, '2026-04-12 22:07:45'),
(56, 1, 'mhr53sl02qdtohjod5fu9o1bne', 1, 'view', NULL, '2026-04-12 22:26:03'),
(57, 1, 'mhr53sl02qdtohjod5fu9o1bne', 1, 'view', NULL, '2026-04-12 22:26:25'),
(58, 1, 'mhr53sl02qdtohjod5fu9o1bne', 1, 'view', NULL, '2026-04-12 22:28:47'),
(59, 1, 'mhr53sl02qdtohjod5fu9o1bne', 7, 'view', NULL, '2026-04-12 22:30:40'),
(60, 1, 'mhr53sl02qdtohjod5fu9o1bne', 1, 'view', NULL, '2026-04-12 22:37:57'),
(61, 1, 'mhr53sl02qdtohjod5fu9o1bne', 7, 'view', NULL, '2026-04-12 22:42:50'),
(62, NULL, '', 7, 'view', NULL, '2026-04-13 07:26:26'),
(63, NULL, 'mhr53sl02qdtohjod5fu9o1bne', 1, 'view', NULL, '2026-04-13 07:26:45'),
(64, 1, 'c5ktdtp0191hvmlp8j2htr0ef4', 1, 'view', NULL, '2026-04-13 07:41:48'),
(65, 1, 'c5ktdtp0191hvmlp8j2htr0ef4', 1, 'view', NULL, '2026-04-13 07:41:56'),
(66, 1, 'c5ktdtp0191hvmlp8j2htr0ef4', 7, 'view', NULL, '2026-04-13 07:42:00'),
(67, 1, 'c5ktdtp0191hvmlp8j2htr0ef4', 1, 'view', NULL, '2026-04-13 07:44:24'),
(68, 1, 'c5ktdtp0191hvmlp8j2htr0ef4', 1, 'view', NULL, '2026-04-13 09:04:41'),
(69, 1, '7l148i3e7mcte7r6r8dl5oejtg', 7, 'view', NULL, '2026-04-13 12:14:56'),
(70, 1, '7l148i3e7mcte7r6r8dl5oejtg', 7, 'view', NULL, '2026-04-13 12:15:04'),
(71, 1, '7l148i3e7mcte7r6r8dl5oejtg', 7, 'view', NULL, '2026-04-13 12:24:00'),
(72, 1, '7l148i3e7mcte7r6r8dl5oejtg', 7, 'view', NULL, '2026-04-13 12:27:00'),
(73, 1, '7l148i3e7mcte7r6r8dl5oejtg', 7, 'view', NULL, '2026-04-13 12:27:32'),
(74, 1, '7l148i3e7mcte7r6r8dl5oejtg', 7, 'view', NULL, '2026-04-13 12:30:37'),
(75, 1, '7l148i3e7mcte7r6r8dl5oejtg', 7, 'view', NULL, '2026-04-13 12:30:39'),
(76, 1, '7l148i3e7mcte7r6r8dl5oejtg', 7, 'view', NULL, '2026-04-13 12:31:28'),
(77, 1, '7l148i3e7mcte7r6r8dl5oejtg', 7, 'view', NULL, '2026-04-13 12:31:29'),
(78, 1, '7l148i3e7mcte7r6r8dl5oejtg', 7, 'view', NULL, '2026-04-13 12:32:05'),
(79, 1, '7l148i3e7mcte7r6r8dl5oejtg', 7, 'view', NULL, '2026-04-13 12:32:40'),
(80, 1, '12i2igupocth3mj0sams8krj89', 7, 'view', NULL, '2026-04-13 14:34:36'),
(81, 4, 'trn3aptq7lql7slkkmsrr0j9un', 1, 'view', NULL, '2026-04-13 14:58:07'),
(82, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:40:39'),
(83, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:41:40'),
(84, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:41:47'),
(85, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:42:19'),
(86, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:43:12'),
(87, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:43:15'),
(88, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:43:55'),
(89, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:44:00'),
(90, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:44:01'),
(91, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:44:10'),
(92, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:44:12'),
(93, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:44:14'),
(94, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:44:23'),
(95, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:44:24'),
(96, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:44:24'),
(97, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:44:25'),
(98, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:44:32'),
(99, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:44:36'),
(100, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:44:37'),
(101, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:44:39'),
(102, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:44:40'),
(103, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:44:44'),
(104, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:44:45'),
(105, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:44:55'),
(106, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:44:56'),
(107, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:45:04'),
(108, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:45:05'),
(109, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:45:06'),
(110, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:45:06'),
(111, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:45:07'),
(112, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:45:08'),
(113, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:45:25'),
(114, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:45:26'),
(115, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:45:26'),
(116, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:45:27'),
(117, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:45:27'),
(118, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:45:28'),
(119, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:45:28'),
(120, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:45:28'),
(121, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:45:29'),
(122, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:45:29'),
(123, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:45:29'),
(124, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:45:30'),
(125, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:45:30'),
(126, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:45:32'),
(127, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:45:32'),
(128, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:45:32'),
(129, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:45:33'),
(130, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:45:33'),
(131, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:45:33'),
(132, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:45:34'),
(133, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:45:34'),
(134, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:45:34'),
(135, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:45:34'),
(136, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:45:35'),
(137, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:45:35'),
(138, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:45:35'),
(139, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:45:35'),
(140, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:45:36'),
(141, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:45:36'),
(142, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:46:44'),
(143, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:46:45'),
(144, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:46:46'),
(145, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:46:46'),
(146, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:47:42'),
(147, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:47:44'),
(148, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:47:56'),
(149, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:47:57'),
(150, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:48:05'),
(151, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:48:06'),
(152, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:49:24'),
(153, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:50:25'),
(154, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:50:59'),
(155, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:51:01'),
(156, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:51:04'),
(157, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:51:34'),
(158, 1, 'v258cuigbg9ojrd4hqc85bjcmu', 7, 'view', NULL, '2026-04-13 16:51:58'),
(159, 1, 'tqctntmgp4k84c01mmurp5hojb', 7, 'view', NULL, '2026-04-13 17:04:12'),
(160, 1, 'tqctntmgp4k84c01mmurp5hojb', 7, 'view', NULL, '2026-04-13 17:10:05'),
(161, 1, 'tqctntmgp4k84c01mmurp5hojb', 1, 'view', NULL, '2026-04-13 17:16:41'),
(162, 1, 'tqctntmgp4k84c01mmurp5hojb', 1, 'view', NULL, '2026-04-13 17:23:13'),
(163, 1, 'tqctntmgp4k84c01mmurp5hojb', 1, 'view', NULL, '2026-04-13 17:23:36'),
(164, 1, 'tqctntmgp4k84c01mmurp5hojb', 1, 'view', NULL, '2026-04-13 17:23:37'),
(165, 1, 'tqctntmgp4k84c01mmurp5hojb', 1, 'view', NULL, '2026-04-13 17:23:41'),
(166, 1, 'tqctntmgp4k84c01mmurp5hojb', 7, 'view', NULL, '2026-04-13 17:26:01'),
(167, NULL, '7j0e2u7urkste9drl144lf7d86', 1, 'view', NULL, '2026-04-14 11:10:05'),
(168, 1, 'h4kfeikufbii9d5mb6rp81cvrd', 1, 'view', NULL, '2026-04-14 19:50:28'),
(169, 1, 'h4kfeikufbii9d5mb6rp81cvrd', 1, 'view', NULL, '2026-04-14 19:51:31'),
(170, 1, 'h4kfeikufbii9d5mb6rp81cvrd', 1, 'view', NULL, '2026-04-14 19:52:43'),
(171, 1, 'h4kfeikufbii9d5mb6rp81cvrd', 1, 'view', NULL, '2026-04-14 19:54:48'),
(172, 1, 'h4kfeikufbii9d5mb6rp81cvrd', 1, 'view', NULL, '2026-04-14 19:54:57'),
(173, 1, 'h4kfeikufbii9d5mb6rp81cvrd', 1, 'view', NULL, '2026-04-14 19:54:58'),
(174, 1, 'h4kfeikufbii9d5mb6rp81cvrd', 1, 'view', NULL, '2026-04-14 19:55:04'),
(175, 1, 'h4kfeikufbii9d5mb6rp81cvrd', 1, 'view', NULL, '2026-04-14 19:55:05'),
(176, 1, 'h4kfeikufbii9d5mb6rp81cvrd', 1, 'view', NULL, '2026-04-14 19:57:01'),
(177, 1, 'h4kfeikufbii9d5mb6rp81cvrd', 1, 'view', NULL, '2026-04-14 19:57:35'),
(178, 1, 'h4kfeikufbii9d5mb6rp81cvrd', 1, 'view', NULL, '2026-04-14 19:57:37'),
(179, 1, 'h4kfeikufbii9d5mb6rp81cvrd', 1, 'view', NULL, '2026-04-14 19:57:38'),
(180, 1, 'h4kfeikufbii9d5mb6rp81cvrd', 1, 'view', NULL, '2026-04-14 19:57:48'),
(181, 1, 'h4kfeikufbii9d5mb6rp81cvrd', 1, 'view', NULL, '2026-04-14 19:58:22'),
(182, 1, 'h4kfeikufbii9d5mb6rp81cvrd', 1, 'view', NULL, '2026-04-14 19:58:23'),
(183, 1, 'h4kfeikufbii9d5mb6rp81cvrd', 1, 'view', NULL, '2026-04-14 19:58:47'),
(184, 1, 'h4kfeikufbii9d5mb6rp81cvrd', 1, 'view', NULL, '2026-04-14 19:58:50'),
(185, 1, 'h4kfeikufbii9d5mb6rp81cvrd', 1, 'view', NULL, '2026-04-14 20:00:17'),
(186, 1, 'h4kfeikufbii9d5mb6rp81cvrd', 1, 'view', NULL, '2026-04-14 20:00:18'),
(187, 1, 'h4kfeikufbii9d5mb6rp81cvrd', 1, 'view', NULL, '2026-04-14 20:00:19'),
(188, 1, 'h4kfeikufbii9d5mb6rp81cvrd', 1, 'view', NULL, '2026-04-14 20:00:55'),
(189, 1, 'h4kfeikufbii9d5mb6rp81cvrd', 1, 'view', NULL, '2026-04-14 20:01:04'),
(190, 1, 'h4kfeikufbii9d5mb6rp81cvrd', 1, 'view', NULL, '2026-04-14 20:01:07'),
(191, 1, 'h4kfeikufbii9d5mb6rp81cvrd', 1, 'view', NULL, '2026-04-14 20:01:58'),
(192, 1, 'h4kfeikufbii9d5mb6rp81cvrd', 1, 'view', NULL, '2026-04-14 20:02:30'),
(193, 1, 'h4kfeikufbii9d5mb6rp81cvrd', 1, 'view', NULL, '2026-04-14 20:02:31'),
(194, 1, 'h4kfeikufbii9d5mb6rp81cvrd', 1, 'view', NULL, '2026-04-14 20:02:38'),
(195, 1, 'h4kfeikufbii9d5mb6rp81cvrd', 1, 'view', NULL, '2026-04-14 20:02:39'),
(196, 1, 'h4kfeikufbii9d5mb6rp81cvrd', 1, 'view', NULL, '2026-04-14 20:02:44'),
(197, 1, 'h4kfeikufbii9d5mb6rp81cvrd', 1, 'view', NULL, '2026-04-14 20:02:50'),
(198, 1, 'h4kfeikufbii9d5mb6rp81cvrd', 1, 'view', NULL, '2026-04-14 20:02:54'),
(199, 1, 'h4kfeikufbii9d5mb6rp81cvrd', 7, 'view', NULL, '2026-04-14 20:03:37'),
(200, 1, 'h4kfeikufbii9d5mb6rp81cvrd', 7, 'view', NULL, '2026-04-14 20:04:31'),
(201, 1, 'h4kfeikufbii9d5mb6rp81cvrd', 7, 'view', NULL, '2026-04-14 20:04:32'),
(202, 1, 'h4kfeikufbii9d5mb6rp81cvrd', 7, 'view', NULL, '2026-04-14 20:04:33'),
(203, 1, 'h4kfeikufbii9d5mb6rp81cvrd', 7, 'view', NULL, '2026-04-14 20:06:01'),
(204, 1, 'h4kfeikufbii9d5mb6rp81cvrd', 7, 'view', NULL, '2026-04-14 20:06:02'),
(205, 1, 'h4kfeikufbii9d5mb6rp81cvrd', 7, 'view', NULL, '2026-04-14 20:07:11'),
(206, 1, 'h4kfeikufbii9d5mb6rp81cvrd', 7, 'view', NULL, '2026-04-14 20:07:23'),
(207, 1, 'h4kfeikufbii9d5mb6rp81cvrd', 7, 'view', NULL, '2026-04-14 20:07:50'),
(208, 1, 'h4kfeikufbii9d5mb6rp81cvrd', 1, 'view', NULL, '2026-04-14 20:07:56'),
(209, 1, 'bu32d6592br0ma3uql560lhotp', 1, 'view', NULL, '2026-04-18 09:44:14'),
(210, NULL, 'bu32d6592br0ma3uql560lhotp', 7, 'view', NULL, '2026-04-19 11:11:53'),
(211, NULL, 'bu32d6592br0ma3uql560lhotp', 7, 'view', NULL, '2026-04-19 11:51:12'),
(212, 1, 'blm3qc3j6irfk3uot68lgp6tqh', 7, 'view', NULL, '2026-04-19 13:43:48'),
(213, NULL, 'blm3qc3j6irfk3uot68lgp6tqh', 7, 'view', NULL, '2026-04-19 17:44:59'),
(214, NULL, 'blm3qc3j6irfk3uot68lgp6tqh', 7, 'view', NULL, '2026-04-19 18:42:48'),
(215, NULL, 'blm3qc3j6irfk3uot68lgp6tqh', 1, 'view', NULL, '2026-04-19 18:52:12'),
(216, NULL, 'blm3qc3j6irfk3uot68lgp6tqh', 1, 'view', NULL, '2026-04-19 18:56:26'),
(217, 1, 'tmul3itru8b62i1c2gn4f0gg68', 7, 'view', NULL, '2026-04-20 09:44:36'),
(218, 1, 'tmul3itru8b62i1c2gn4f0gg68', 7, 'view', NULL, '2026-04-20 10:43:09'),
(219, 1, 'tmul3itru8b62i1c2gn4f0gg68', 7, 'view', NULL, '2026-04-20 10:44:05'),
(220, 1, 'tmul3itru8b62i1c2gn4f0gg68', 1, 'view', NULL, '2026-04-20 10:46:14'),
(221, 1, 'tmul3itru8b62i1c2gn4f0gg68', 7, 'view', NULL, '2026-04-20 10:52:35'),
(222, 1, 'tmul3itru8b62i1c2gn4f0gg68', 7, 'view', NULL, '2026-04-20 10:52:57'),
(223, 1, 'tmul3itru8b62i1c2gn4f0gg68', 7, 'view', NULL, '2026-04-20 11:04:11'),
(224, NULL, 'tmul3itru8b62i1c2gn4f0gg68', 7, 'view', NULL, '2026-04-20 16:27:22'),
(225, 1, 'jseloj0796sfem2jthvaafntqp', 7, 'view', NULL, '2026-04-20 16:27:48'),
(226, 1, 'jseloj0796sfem2jthvaafntqp', 7, 'view', NULL, '2026-04-20 16:28:03'),
(227, 1, 'jseloj0796sfem2jthvaafntqp', 9, 'view', NULL, '2026-04-20 16:38:55'),
(228, 1, 'jseloj0796sfem2jthvaafntqp', 7, 'view', NULL, '2026-04-20 16:40:18'),
(229, 1, 'jseloj0796sfem2jthvaafntqp', 1, 'view', NULL, '2026-04-20 16:40:24'),
(230, 1, 'jseloj0796sfem2jthvaafntqp', 1, 'view', NULL, '2026-04-20 16:40:38'),
(231, NULL, 'f1tdgnp0tuq0b5ib244b0o7vva', 7, 'view', NULL, '2026-04-21 08:50:30'),
(232, NULL, 'f1tdgnp0tuq0b5ib244b0o7vva', 7, 'view', NULL, '2026-04-21 08:51:39'),
(233, 1, 'n8qu0i8vkhog9jc9opr2de5sa3', 1, 'view', NULL, '2026-04-21 15:02:52'),
(234, 1, 'n8qu0i8vkhog9jc9opr2de5sa3', 1, 'view', NULL, '2026-04-21 15:04:06'),
(235, 1, 'n8qu0i8vkhog9jc9opr2de5sa3', 7, 'view', NULL, '2026-04-21 15:07:13'),
(236, 1, 'n8qu0i8vkhog9jc9opr2de5sa3', 1, 'view', NULL, '2026-04-21 15:24:42'),
(237, 4, 'suq2hq12aa1u39hv6ptqu89j2a', 1, 'view', NULL, '2026-04-22 20:12:17'),
(238, 1, 'qthv87ukg0jdnkt6fcjjim7b59', 7, 'view', NULL, '2026-04-22 20:33:47'),
(239, 1, '5r95ie77ji82idjbvkqqd09m0n', 7, 'view', NULL, '2026-04-24 19:01:31'),
(240, 1, '5r95ie77ji82idjbvkqqd09m0n', 7, 'view', NULL, '2026-04-24 19:41:31'),
(241, 1, 'i9trr6fprjfcmmddmf2ufe7qak', 7, 'view', NULL, '2026-04-25 20:23:15'),
(242, 1, 'jkmon3hao4ac9q491ppn296187', 1, 'view', NULL, '2026-04-28 08:00:35'),
(243, 1, 'r9172l7cnijm9fh4ep7rh8v1rl', 1, 'view', NULL, '2026-05-02 20:48:56');

-- --------------------------------------------------------

--
-- Table structure for table `user_login_activity`
--

DROP TABLE IF EXISTS `user_login_activity`;
CREATE TABLE IF NOT EXISTS `user_login_activity` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED NOT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `login_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_login_activity`
--

INSERT INTO `user_login_activity` (`id`, `user_id`, `ip_address`, `user_agent`, `login_at`) VALUES
(1, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-29 04:36:39'),
(2, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-29 05:12:56'),
(3, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-29 05:41:26'),
(4, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-30 05:07:24'),
(5, 4, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-30 05:21:20'),
(6, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-30 05:22:16'),
(7, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-30 18:58:31'),
(8, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-30 20:04:35'),
(9, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 15:37:45'),
(10, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-04 18:06:18'),
(11, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-12 16:58:08'),
(12, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-12 17:40:50'),
(13, 4, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-12 17:45:24'),
(14, 4, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-12 17:53:18'),
(15, 4, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-12 18:00:44'),
(16, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-12 18:06:36'),
(17, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-12 20:34:47'),
(18, 4, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-12 22:03:13'),
(19, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-12 22:04:26'),
(20, 4, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-12 22:05:45'),
(21, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-12 22:13:53'),
(22, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-13 07:37:00'),
(23, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-13 11:36:39'),
(24, 4, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-13 12:38:15'),
(25, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-13 14:21:27'),
(26, 4, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-13 14:42:41'),
(27, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-13 14:52:34'),
(28, 4, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-13 14:57:30'),
(29, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-13 14:59:30'),
(30, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-13 17:00:02'),
(31, 4, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-13 17:31:38'),
(32, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-13 17:35:27'),
(33, 4, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-14 11:10:32'),
(34, 4, '::1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1', '2026-04-14 19:00:11'),
(35, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-14 19:00:48'),
(36, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-14 20:10:54'),
(37, 4, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-14 20:11:28'),
(38, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-14 20:34:02'),
(39, 4, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 08:48:47'),
(40, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 09:06:59'),
(41, 4, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 09:13:56'),
(42, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-18 09:15:38'),
(43, 4, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 11:56:56'),
(44, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 13:23:55'),
(45, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 19:01:34'),
(46, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-20 09:43:11'),
(47, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-20 16:27:43'),
(48, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-20 20:42:23'),
(49, 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-20 21:34:34'),
(50, 4, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-21 08:54:01'),
(51, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-21 09:01:54'),
(52, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-21 14:54:35'),
(53, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-21 19:30:39'),
(54, 4, '::1', 'Mozilla/5.0 (Linux; Android 8.0.0; SM-G955U Build/R16NW) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-21 19:33:01'),
(55, 4, '::1', 'Mozilla/5.0 (Linux; Android 8.0.0; SM-G955U Build/R16NW) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-22 20:02:22'),
(56, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-22 20:30:58'),
(57, 4, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-22 20:34:38'),
(58, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-22 20:47:21'),
(59, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-23 19:27:23'),
(60, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-24 18:39:51'),
(61, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-25 19:14:02'),
(62, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-26 20:09:06'),
(63, 1, '::1', 'Mozilla/5.0 (Linux; Android 8.0.0; SM-G955U Build/R16NW) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-27 19:12:28'),
(64, 4, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-27 19:53:29'),
(65, 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-27 20:01:12'),
(66, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-28 07:49:13'),
(67, 4, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-28 07:54:39'),
(68, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-28 07:55:26'),
(69, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-02 20:23:48');

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

DROP TABLE IF EXISTS `vendors`;
CREATE TABLE IF NOT EXISTS `vendors` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED DEFAULT NULL,
  `company_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('vendor','supplier') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'vendor',
  `status` enum('active','inactive','pending') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`id`, `user_id`, `company_name`, `contact_name`, `email`, `phone`, `address`, `logo`, `type`, `status`, `notes`, `created_at`) VALUES
(1, NULL, 'My Mart', '0766961154', 'info@mymartlk.com', '0766961154', 'sdfsdf', NULL, 'supplier', 'active', 'dsfsdffdsf', '2026-03-30 05:41:02');

-- --------------------------------------------------------

--
-- Table structure for table `wishlists`
--

DROP TABLE IF EXISTS `wishlists`;
CREATE TABLE IF NOT EXISTS `wishlists` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `added_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_wish` (`user_id`,`product_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wishlists`
--

INSERT INTO `wishlists` (`id`, `user_id`, `product_id`, `added_at`) VALUES
(26, 4, 1, '2026-04-22 20:42:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products` ADD FULLTEXT KEY `ft_search` (`name`,`short_desc`,`description`,`tags`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_3` FOREIGN KEY (`variation_id`) REFERENCES `product_variations` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `coupon_usage`
--
ALTER TABLE `coupon_usage`
  ADD CONSTRAINT `coupon_usage_ibfk_1` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `order_returns`
--
ALTER TABLE `order_returns`
  ADD CONSTRAINT `order_returns_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_status_history`
--
ALTER TABLE `order_status_history`
  ADD CONSTRAINT `order_status_history_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payment_transactions`
--
ALTER TABLE `payment_transactions`
  ADD CONSTRAINT `payment_transactions_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `products_ibfk_3` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `product_attribute_values`
--
ALTER TABLE `product_attribute_values`
  ADD CONSTRAINT `product_attribute_values_ibfk_1` FOREIGN KEY (`attribute_id`) REFERENCES `product_attributes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_attribute_values_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_discounts`
--
ALTER TABLE `product_discounts`
  ADD CONSTRAINT `product_discounts_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD CONSTRAINT `product_reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_stock`
--
ALTER TABLE `product_stock`
  ADD CONSTRAINT `product_stock_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_stock_ibfk_2` FOREIGN KEY (`variation_id`) REFERENCES `product_variations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_suppliers`
--
ALTER TABLE `product_suppliers`
  ADD CONSTRAINT `product_suppliers_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_suppliers_ibfk_2` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_variations`
--
ALTER TABLE `product_variations`
  ADD CONSTRAINT `product_variations_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD CONSTRAINT `purchase_orders_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`);

--
-- Constraints for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  ADD CONSTRAINT `purchase_order_items_ibfk_1` FOREIGN KEY (`po_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `purchase_order_items_ibfk_3` FOREIGN KEY (`variation_id`) REFERENCES `product_variations` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD CONSTRAINT `stock_movements_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD CONSTRAINT `user_addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_behavior`
--
ALTER TABLE `user_behavior`
  ADD CONSTRAINT `user_behavior_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_login_activity`
--
ALTER TABLE `user_login_activity`
  ADD CONSTRAINT `user_login_activity_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vendors`
--
ALTER TABLE `vendors`
  ADD CONSTRAINT `vendors_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD CONSTRAINT `wishlists_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlists_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
