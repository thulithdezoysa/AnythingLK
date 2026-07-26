# UTHRShop — Enterprise E-Commerce Platform

## Setup Instructions

### 1. Database
Import `database.sql` into MySQL:
```bash
mysql -u root -p < database.sql
```

### 2. Configuration
Edit `config.ini` with your database credentials:
```ini
servername = "localhost"
username   = "root"
password   = "your_password"
dbname     = "ecommerce_db"
app_url    = "http://localhost/ecommerce"
```

### 3. Web Server
Point your Apache DocumentRoot to this folder.
Ensure `mod_rewrite` is enabled.

### 4. PHP Requirements
- PHP 8.1+
- MySQLi extension
- GD extension (for WebP image conversion)
- cURL extension (for AI features)

### 5. Admin Access
- URL: http://localhost/ecommerce/admin
- Email: admin@shop.lk
- Password: Admin@12345

⚠️ **Change the admin password immediately after first login!**

### 6. AI Features (Optional)
In `admin/controllers/AdminProductController.php`, replace:
```php
$apiKey = 'YOUR_ANTHROPIC_API_KEY';
```
with your actual Anthropic API key.

## Project Structure
```
ecommerce/
├── index.php              # Front controller
├── database.sql           # Full DB schema + seed data
├── db_connect.php         # DB connection class
├── config.ini             # App configuration
├── .htaccess              # Clean URL routing
├── core/                  # Router, Helper, Auth, Lang, CSRF
├── app/
│   ├── controllers/       # All public controllers
│   ├── models/            # ProductModel, CartModel, OrderModel, etc.
│   └── views/             # All frontend templates
├── admin/                 # Complete admin panel
├── lang/                  # en.php, si.php, ta.php
├── assets/                # CSS, fonts, images
└── uploads/               # Product/banner image uploads
```

## Features
✅ Clean URL routing (.htaccess)
✅ Multi-language (English, Sinhala, Tamil)
✅ User registration/login (AJAX)
✅ Product listing, search (FULLTEXT), detail pages
✅ Shopping cart (guest + user, AJAX)
✅ Checkout + order placement
✅ Stock management (IN/OUT/Adjust)
✅ Purchase Order system
✅ Admin dashboard with charts
✅ AI product description generator
✅ AI chatbot assistant
✅ Behaviour tracking for personalised recommendations
✅ Coupon system
✅ Review system with approval
✅ Vendor/supplier management
✅ CSRF protection on all forms
✅ Password hashing (bcrypt)
✅ Prepared statements (MySQLi real_escape_string)
