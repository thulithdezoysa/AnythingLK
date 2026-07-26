# Prompt: Build a PHP MVC Website Using the AnythingLK Architecture

Use the following architectural blueprint to build a new PHP website from scratch. This is a **custom lightweight PHP MVC framework** — no Laravel, no Symfony, no Composer autoload. Everything is hand-rolled and self-contained.

---

## PROJECT OVERVIEW

Build a `[YOUR PROJECT NAME]` website at `C:\wamp64\www\[ProjectFolder]\` (or your server's web root). The stack is:

- **Backend:** Pure PHP 8.x, no framework
- **Database:** MySQL via MySQLi (manual escaping, no ORM, no prepared statements)
- **Frontend:** Bootstrap 5.3, Font Awesome 6, custom CSS with CSS variables
- **Auth:** PHP session-based with roles
- **Templates:** PHP files with `extract()` — no Twig, no Blade

---

## DIRECTORY STRUCTURE

Create this exact layout:

```
ProjectFolder/
├── index.php                  # Front controller (entry point)
├── db_connect.php             # Db class (MySQLi wrapper)
├── config.ini                 # App config (db, mail, app settings)
├── .htaccess                  # Apache URL rewrite rules
├── core/                      # Framework core classes
│   ├── Router.php
│   ├── Controller.php
│   ├── Auth.php
│   ├── CSRF.php
│   ├── Helper.php
│   ├── Lang.php
│   ├── MailService.php
│   └── RateLimit.php
├── app/
│   ├── controllers/           # {Feature}Controller.php
│   ├── models/                # {Feature}Model.php
│   └── views/
│       ├── layouts/
│       │   └── default.php    # Main HTML wrapper
│       ├── components/        # Reusable partials
│       ├── errors/
│       │   └── 404.php
│       └── {feature}/         # e.g. product/, user/, cart/
├── lang/
│   ├── en.php                 # English strings (key => value array)
│   └── [other langs].php
├── assets/
│   ├── css/
│   │   └── style.css          # Custom styles (Bootstrap overrides + design system)
│   ├── img/
│   └── fonts/
├── uploads/                   # User-uploaded files
└── storage/
    └── rate_limits/           # File-based rate limiting JSONs
```

---

## 1. ENTRY POINT — `index.php`

```php
<?php
define('ROOT', __DIR__);
define('APP',  ROOT . '/app');
define('CORE', ROOT . '/core');

session_start();

// Autoloader: checks core/, then app/controllers/, then app/models/
spl_autoload_register(function($class) {
    $paths = [CORE, APP . '/controllers', APP . '/models'];
    foreach ($paths as $path) {
        $file = $path . '/' . $class . '.php';
        if (file_exists($file)) { require_once $file; return; }
    }
});

require_once ROOT . '/db_connect.php';

// Include all core files
foreach (['Helper','Auth','CSRF','Lang','RateLimit','MailService'] as $c) {
    require_once CORE . "/$c.php";
}
require_once CORE . '/Router.php';
require_once CORE . '/Controller.php';

Lang::init();

$router = new Router('/ProjectFolder'); // Change base path to match your subfolder, or '/' for root

// --- Register routes here ---
$router->get('',               'HomeController',    'index');
$router->get('about',          'PageController',    'about');
$router->get('contact',        'PageController',    'contact');
$router->post('contact',       'PageController',    'contactSubmit');

// Products
$router->get('products',           'ProductController', 'listing');
$router->get('product/{slug}',     'ProductController', 'detail');
$router->get('category/{slug}',    'ProductController', 'category');

// Auth
$router->get('login',          'UserController', 'loginForm');
$router->post('login',         'UserController', 'login');
$router->get('register',       'UserController', 'registerForm');
$router->post('register',      'UserController', 'register');
$router->get('logout',         'UserController', 'logout');
$router->get('account',        'UserController', 'account');

// Cart
$router->get('cart',           'CartController', 'index');
$router->post('cart/add',      'CartController', 'add');
$router->post('cart/update',   'CartController', 'update');
$router->post('cart/remove',   'CartController', 'remove');

// Checkout
$router->get('checkout',             'CheckoutController', 'index');
$router->post('checkout/place-order','CheckoutController', 'placeOrder');
$router->get('order/success/{id}',   'CheckoutController', 'success');

$router->dispatch();
```

---

## 2. DATABASE CLASS — `db_connect.php`

```php
<?php
class Db {
    private static ?mysqli $conn = null;

    protected function connect(): mysqli {
        if (!self::$conn) {
            $cfg = parse_ini_file(ROOT . '/config.ini', true)['Database'];
            self::$conn = new mysqli($cfg['servername'], $cfg['username'], $cfg['password'], $cfg['dbname']);
            self::$conn->set_charset('utf8mb4');
        }
        return self::$conn;
    }

    public function query(string $sql): mysqli_result|bool {
        return $this->connect()->query($sql);
    }

    public function select(string $sql): array {
        $result = $this->query($sql);
        if (!$result) return [];
        $rows = [];
        while ($row = $result->fetch_assoc()) $rows[] = $row;
        return $rows;
    }

    public function selectOne(string $sql): ?array {
        $result = $this->query($sql);
        if (!$result) return null;
        return $result->fetch_assoc() ?: null;
    }

    public function insert(string $table, array $data): int|false {
        $cols = implode(',', array_keys($data));
        $vals = implode(',', array_map(fn($v) => $this->escape($v), array_values($data)));
        $this->query("INSERT INTO `$table` ($cols) VALUES ($vals)");
        return $this->connect()->insert_id ?: false;
    }

    public function update(string $table, array $data, string $where): bool {
        $set = implode(',', array_map(fn($k,$v) => "`$k`=" . $this->escape($v), array_keys($data), $data));
        return (bool)$this->query("UPDATE `$table` SET $set WHERE $where");
    }

    public function delete(string $table, string $where): bool {
        return (bool)$this->query("DELETE FROM `$table` WHERE $where");
    }

    public function escape(mixed $value): string {
        if ($value === null) return 'NULL';
        if (is_bool($value)) return $value ? '1' : '0';
        if (is_int($value) || is_float($value)) return (string)$value;
        return "'" . $this->connect()->real_escape_string((string)$value) . "'";
    }
}
```

---

## 3. ROUTER — `core/Router.php`

```php
<?php
class Router {
    private array $routes = [];
    private string $base;

    public function __construct(string $basePath = '') {
        $this->base = rtrim($basePath, '/');
    }

    public function get(string $pattern, string $controller, string $action): void {
        $this->routes[] = ['GET', $pattern, $controller, $action];
    }

    public function post(string $pattern, string $controller, string $action): void {
        $this->routes[] = ['POST', $pattern, $controller, $action];
    }

    public function dispatch(): void {
        $uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri    = rawurldecode(rtrim(substr($uri, strlen($this->base)), '/'));
        $uri    = $uri === '' ? '' : ltrim($uri, '/');
        $method = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes as [$routeMethod, $pattern, $controller, $action]) {
            if ($routeMethod !== $method) continue;
            $regex = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $pattern);
            if (preg_match('#^' . $regex . '$#', $uri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                $obj = new $controller();
                $obj->$action($params);
                return;
            }
        }

        http_response_code(404);
        $ctrl = new Controller();
        $ctrl->view('errors/404', [], 'default');
    }
}
```

---

## 4. BASE CONTROLLER — `core/Controller.php`

```php
<?php
class Controller extends Db {

    public function view(string $viewPath, array $data = [], string $layout = 'default'): void {
        // Merge shared data available in every view
        $data = array_merge([
            'currentLang'  => Lang::current(),
            'csrfToken'    => CSRF::token(),
            'authUser'     => Auth::user(),
            'cartCount'    => $this->getCartCount(),
            'pageTitle'    => setting('site_name', 'My Site'),
        ], $data);

        extract($data);

        ob_start();
        require APP . '/views/' . $viewPath . '.php';
        $content = ob_get_clean();

        require APP . '/views/layouts/' . $layout . '.php';
    }

    public function json(array $data, int $code = 200): void {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public function redirect(string $path): void {
        header('Location: ' . url($path));
        exit;
    }

    protected function getCartCount(): int {
        $uid  = Auth::id();
        $sid  = session_id();
        $cond = $uid ? "user_id=$uid" : "session_id='" . $this->connect()->real_escape_string($sid) . "'";
        $row  = $this->selectOne("SELECT SUM(quantity) as cnt FROM cart WHERE $cond");
        return (int)($row['cnt'] ?? 0);
    }
}
```

---

## 5. AUTH CLASS — `core/Auth.php`

```php
<?php
class Auth {
    private static int $timeout = 7200; // 2 hours

    public static function login(array $user): void {
        session_regenerate_id(true);
        $_SESSION['user_id']       = $user['id'];
        $_SESSION['user_name']     = $user['full_name'];
        $_SESSION['user_email']    = $user['email'];
        $_SESSION['user_role']     = $user['role'];
        $_SESSION['last_activity'] = time();
    }

    public static function logout(): void {
        session_unset();
        session_destroy();
    }

    public static function check(): bool {
        if (empty($_SESSION['user_id'])) return false;
        if (time() - ($_SESSION['last_activity'] ?? 0) > self::$timeout) {
            self::logout();
            return false;
        }
        $_SESSION['last_activity'] = time();
        return true;
    }

    public static function user(): ?array {
        return self::check() ? [
            'id'    => $_SESSION['user_id'],
            'name'  => $_SESSION['user_name'],
            'email' => $_SESSION['user_email'],
            'role'  => $_SESSION['user_role'],
        ] : null;
    }

    public static function id(): ?int   { return self::check() ? (int)$_SESSION['user_id'] : null; }
    public static function role(): ?string { return $_SESSION['user_role'] ?? null; }
    public static function isAdmin(): bool { return self::role() === 'admin'; }
    public static function isCustomer(): bool { return self::role() === 'customer'; }

    public static function requireLogin(string $redirect = 'login'): void {
        if (!self::check()) {
            header('Location: ' . url($redirect));
            exit;
        }
    }
}
```

---

## 6. CSRF CLASS — `core/CSRF.php`

```php
<?php
class CSRF {
    public static function token(): string {
        if (empty($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf_token'];
    }

    public static function field(): string {
        return '<input type="hidden" name="_csrf" value="' . self::token() . '">';
    }

    public static function verify(string $token): bool {
        return hash_equals(self::token(), $token);
    }

    public static function check(): void {
        $token = $_POST['_csrf'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        if (!self::verify($token)) {
            http_response_code(403);
            die('CSRF token mismatch.');
        }
    }
}
```

---

## 7. HELPER CLASS — `core/Helper.php`

```php
<?php
class Helper {
    private static array $settingsCache = [];

    public static function baseUrl(): string {
        $cfg = parse_ini_file(ROOT . '/config.ini', true)['Application'] ?? [];
        return rtrim($cfg['app_url'] ?? '', '/');
    }

    public static function url(string $path = ''): string {
        return self::baseUrl() . ($path ? '/' . ltrim($path, '/') : '');
    }

    public static function asset(string $path): string {
        return self::baseUrl() . '/assets/' . ltrim($path, '/');
    }

    public static function sanitize(mixed $input): string {
        return htmlspecialchars(strip_tags(trim((string)$input)), ENT_QUOTES, 'UTF-8');
    }

    public static function validEmail(string $email): bool {
        return (bool)filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public static function hashPassword(string $password): string {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }

    public static function verifyPassword(string $password, string $hash): bool {
        return password_verify($password, $hash);
    }

    public static function slug(string $text): string {
        return strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', $text), '-'));
    }

    public static function paginate(int $total, int $perPage, int $page): array {
        $totalPages = max(1, (int)ceil($total / $perPage));
        $page       = max(1, min($page, $totalPages));
        return [
            'total'       => $total,
            'per_page'    => $perPage,
            'current_page'=> $page,
            'total_pages' => $totalPages,
            'offset'      => ($page - 1) * $perPage,
            'has_prev'    => $page > 1,
            'has_next'    => $page < $totalPages,
        ];
    }

    public static function timeAgo(string $datetime): string {
        $diff = time() - strtotime($datetime);
        if ($diff < 60)   return 'just now';
        if ($diff < 3600) return (int)($diff/60) . ' minutes ago';
        if ($diff < 86400)return (int)($diff/3600) . ' hours ago';
        return (int)($diff/86400) . ' days ago';
    }

    public static function truncate(string $text, int $len = 100): string {
        return mb_strlen($text) > $len ? mb_substr($text, 0, $len) . '...' : $text;
    }

    public static function setting(string $key, mixed $default = null): mixed {
        if (empty(self::$settingsCache)) {
            $db   = new Db();
            $rows = $db->select("SELECT `key`, `value` FROM settings");
            foreach ($rows as $r) self::$settingsCache[$r['key']] = $r['value'];
        }
        return self::$settingsCache[$key] ?? $default;
    }

    public static function uploadImage(array $file, string $dir): string|false {
        $allowed = ['image/jpeg','image/png','image/webp','image/gif'];
        if (!in_array($file['type'], $allowed)) return false;
        $ext  = pathinfo($file['name'], PATHINFO_EXTENSION);
        $name = uniqid() . '.' . $ext;
        $dest = ROOT . '/uploads/' . $dir . '/' . $name;
        if (!is_dir(dirname($dest))) mkdir(dirname($dest), 0755, true);
        return move_uploaded_file($file['tmp_name'], $dest) ? $name : false;
    }
}

// Global shorthand functions
function e(mixed $s): string        { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function url(string $p = ''): string { return Helper::url($p); }
function asset(string $p): string   { return Helper::asset($p); }
function setting(string $k, mixed $d = null): mixed { return Helper::setting($k, $d); }
function __(string $key, array $replace = []): string { return Lang::get($key, $replace); }
```

---

## 8. LANGUAGE CLASS — `core/Lang.php`

```php
<?php
class Lang {
    private static string $current = 'en';
    private static array  $strings = [];
    private static array  $supported = ['en'];

    public static function init(): void {
        $lang = $_GET['lang'] ?? $_SESSION['lang'] ?? $_COOKIE['lang'] ?? 'en';
        if (!in_array($lang, self::$supported)) $lang = 'en';
        self::$current = $lang;
        $_SESSION['lang'] = $lang;
        setcookie('lang', $lang, time() + 86400 * 30, '/');
        $file = ROOT . "/lang/$lang.php";
        self::$strings = file_exists($file) ? require $file : [];
    }

    public static function current(): string { return self::$current; }

    public static function get(string $key, array $replace = []): string {
        $parts = explode('.', $key);
        $val   = self::$strings;
        foreach ($parts as $p) $val = is_array($val) ? ($val[$p] ?? null) : null;
        $str = is_string($val) ? $val : $key;
        foreach ($replace as $k => $v) $str = str_replace(':' . $k, $v, $str);
        return $str;
    }
}
```

---

## 9. RATE LIMIT — `core/RateLimit.php`

```php
<?php
class RateLimit {
    public static function check(string $action, int $max, int $window): void {
        $ip   = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $file = ROOT . '/storage/rate_limits/' . md5($action . $ip) . '.json';
        if (!is_dir(dirname($file))) mkdir(dirname($file), 0755, true);

        $data = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
        $now  = time();

        $data['attempts'] = array_filter($data['attempts'] ?? [], fn($t) => $now - $t < $window);
        if (count($data['attempts']) >= $max) {
            http_response_code(429);
            die(json_encode(['success' => false, 'message' => 'Too many attempts. Please wait.']));
        }

        $data['attempts'][] = $now;
        file_put_contents($file, json_encode($data));
    }
}
```

---

## 10. CONFIG FILE — `config.ini`

```ini
[Database]
servername = "localhost"
username   = "root"
password   = ""
dbname     = "your_db_name"

[Application]
app_name = "Your Site Name"
app_url  = "http://localhost/ProjectFolder"
app_env  = "development"

[Email]
mail_host       = "smtp.gmail.com"
mail_port       = 587
mail_user       = ""
mail_pass       = ""
mail_encryption = "tls"
mail_from_address = "noreply@yoursite.com"
mail_from_name    = "Your Site Name"
```

---

## 11. HTACCESS — `.htaccess`

```apache
Options -Indexes
RewriteEngine On
RewriteBase /ProjectFolder/

# Block direct access to sensitive files
RewriteRule ^config\.ini$    - [F,L]
RewriteRule ^db_connect\.php$ - [F,L]

# Route all non-file, non-directory requests to index.php
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
```

---

## 12. CONTROLLER PATTERN — `app/controllers/ExampleController.php`

```php
<?php
class ExampleController extends Controller {
    private ExampleModel $model;

    public function __construct() {
        parent::__construct();
        $this->model = new ExampleModel();
    }

    // GET route action
    public function listing(array $params = []): void {
        $page  = (int)($_GET['page'] ?? 1);
        $total = $this->model->count();
        $pag   = Helper::paginate($total, 12, $page);
        $items = $this->model->getAll($pag['offset'], $pag['per_page']);

        $this->view('example/listing', compact('items', 'pag'));
    }

    // POST route action (AJAX)
    public function store(array $params = []): void {
        CSRF::check();
        RateLimit::check('example_store', 10, 300);

        $name = Helper::sanitize($_POST['name'] ?? '');
        if (!$name) {
            $this->json(['success' => false, 'message' => 'Name required.'], 422);
        }

        $id = $this->model->create(['name' => $name]);
        $this->json(['success' => true, 'id' => $id]);
    }
}
```

---

## 13. MODEL PATTERN — `app/models/ExampleModel.php`

```php
<?php
class ExampleModel extends Db {

    public function getAll(int $offset = 0, int $limit = 12): array {
        return $this->select("SELECT * FROM example WHERE status='active' LIMIT $limit OFFSET $offset");
    }

    public function getBySlug(string $slug): ?array {
        $slug = $this->connect()->real_escape_string($slug);
        return $this->selectOne("SELECT * FROM example WHERE slug='$slug'");
    }

    public function count(): int {
        $row = $this->selectOne("SELECT COUNT(*) as cnt FROM example WHERE status='active'");
        return (int)($row['cnt'] ?? 0);
    }

    public function create(array $data): int|false {
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->insert('example', $data);
    }
}
```

---

## 14. LAYOUT — `app/views/layouts/default.php`

```php
<!DOCTYPE html>
<html lang="<?= e($currentLang) ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($pageTitle ?? setting('site_name', 'My Site')) ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
</head>
<body>

  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
      <a class="navbar-brand fw-bold" href="<?= url('') ?>"><?= e(setting('site_name', 'My Site')) ?></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="nav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="<?= url('') ?>"><?= __('nav.home') ?></a></li>
          <li class="nav-item"><a class="nav-link" href="<?= url('products') ?>"><?= __('nav.products') ?></a></li>
          <?php if ($authUser): ?>
            <li class="nav-item"><a class="nav-link" href="<?= url('account') ?>"><?= e($authUser['name']) ?></a></li>
            <li class="nav-item"><a class="nav-link" href="<?= url('logout') ?>"><?= __('nav.logout') ?></a></li>
          <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="<?= url('login') ?>"><?= __('nav.login') ?></a></li>
          <?php endif; ?>
          <li class="nav-item">
            <a class="nav-link" href="<?= url('cart') ?>">
              <i class="fas fa-shopping-cart"></i>
              <?php if ($cartCount > 0): ?>
                <span class="badge bg-danger"><?= $cartCount ?></span>
              <?php endif; ?>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- MAIN CONTENT -->
  <main>
    <?= $content ?>
  </main>

  <!-- FOOTER -->
  <footer class="bg-dark text-white py-4 mt-5">
    <div class="container text-center">
      <p class="mb-0">&copy; <?= date('Y') ?> <?= e(setting('site_name', 'My Site')) ?>. All rights reserved.</p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
```

---

## 15. VIEW PATTERN — `app/views/example/listing.php`

```php
<div class="container py-5">
  <h1 class="mb-4"><?= __('example.title') ?></h1>

  <div class="row g-4">
    <?php foreach ($items as $item): ?>
      <div class="col-md-4">
        <div class="card h-100 shadow-sm">
          <div class="card-body">
            <h5 class="card-title"><?= e($item['name']) ?></h5>
            <p class="card-text"><?= e(Helper::truncate($item['description'], 100)) ?></p>
            <a href="<?= url('example/' . $item['slug']) ?>" class="btn btn-primary">View</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- Pagination -->
  <?php if ($pag['total_pages'] > 1): ?>
    <nav class="mt-4">
      <ul class="pagination justify-content-center">
        <?php if ($pag['has_prev']): ?>
          <li class="page-item"><a class="page-link" href="?page=<?= $pag['current_page'] - 1 ?>">Previous</a></li>
        <?php endif; ?>
        <?php for ($i = 1; $i <= $pag['total_pages']; $i++): ?>
          <li class="page-item <?= $i === $pag['current_page'] ? 'active' : '' ?>">
            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
          </li>
        <?php endfor; ?>
        <?php if ($pag['has_next']): ?>
          <li class="page-item"><a class="page-link" href="?page=<?= $pag['current_page'] + 1 ?>">Next</a></li>
        <?php endif; ?>
      </ul>
    </nav>
  <?php endif; ?>
</div>
```

---

## 16. FORM WITH CSRF AND AJAX — pattern used in views

```php
<form id="myForm" method="POST" action="<?= url('example/store') ?>">
  <?= CSRF::field() ?>
  <div class="mb-3">
    <label class="form-label">Name</label>
    <input type="text" name="name" class="form-control" required>
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>

<script>
document.getElementById('myForm').addEventListener('submit', async function(e) {
  e.preventDefault();
  const res  = await fetch(this.action, { method: 'POST', body: new FormData(this) });
  const data = await res.json();
  if (data.success) {
    alert('Saved!');
  } else {
    alert(data.message || 'Error');
  }
});
</script>
```

---

## 17. CSS DESIGN SYSTEM — `assets/css/style.css` (start with this)

```css
:root {
  --brand:       #E63946;   /* Change to your brand color */
  --brand-dark:  #c1121f;
  --text:        #212529;
  --text-muted:  #6c757d;
  --border:      #dee2e6;
  --bg:          #f8f9fa;
  --white:       #ffffff;
  --shadow:      0 2px 8px rgba(0,0,0,.08);
  --radius:      8px;
  --transition:  .2s ease;
}

* { box-sizing: border-box; }

body {
  font-family: 'Segoe UI', system-ui, sans-serif;
  color: var(--text);
  background: var(--white);
}

.btn-primary {
  background: var(--brand);
  border-color: var(--brand);
}
.btn-primary:hover {
  background: var(--brand-dark);
  border-color: var(--brand-dark);
}

a { color: var(--brand); }
a:hover { color: var(--brand-dark); }

.card {
  border-radius: var(--radius);
  border: 1px solid var(--border);
  box-shadow: var(--shadow);
  transition: box-shadow var(--transition);
}
.card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.12); }
```

---

## 18. LANGUAGE FILE — `lang/en.php`

```php
<?php
return [
  'nav' => [
    'home'     => 'Home',
    'products' => 'Products',
    'login'    => 'Login',
    'logout'   => 'Logout',
    'register' => 'Register',
    'account'  => 'My Account',
    'cart'     => 'Cart',
  ],
  'auth' => [
    'login_title'    => 'Sign In',
    'register_title' => 'Create Account',
    'email'          => 'Email Address',
    'password'       => 'Password',
    'login_btn'      => 'Sign In',
    'register_btn'   => 'Create Account',
  ],
  'example' => [
    'title' => 'Browse Items',
  ],
];
```

---

## 19. DATABASE SETUP — Minimum required tables

```sql
CREATE TABLE users (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  full_name  VARCHAR(100) NOT NULL,
  email      VARCHAR(150) UNIQUE NOT NULL,
  password   VARCHAR(255) NOT NULL,
  role       ENUM('admin','customer') DEFAULT 'customer',
  status     TINYINT DEFAULT 1,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE settings (
  `key`   VARCHAR(100) PRIMARY KEY,
  `value` TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO settings VALUES
  ('site_name',   'Your Site Name'),
  ('brand_color', '#E63946'),
  ('site_logo',   '');
```

---

## CONVENTIONS TO FOLLOW

| Concern          | Convention |
|------------------|------------|
| Controller naming | `{Feature}Controller.php` |
| Model naming      | `{Feature}Model.php` |
| View path         | `app/views/{feature}/{action}.php` |
| Route registration| In `index.php` only |
| DB queries        | Raw SQL in models, manual `$this->escape()` or `real_escape_string()` |
| Form submission   | Always `CSRF::check()` first in POST actions |
| User input        | Always `Helper::sanitize()` for text |
| Output in views   | Always `e($var)` for untrusted data |
| Redirects         | `$this->redirect('path')` — never `header()` directly |
| AJAX responses    | `$this->json(['success' => bool, ...])` |
| Asset URLs        | `asset('css/style.css')` — never hardcoded paths |
| Route URLs        | `url('products')` — never hardcoded paths |

---

## CHECKLIST WHEN STARTING A NEW PROJECT

- [ ] Copy and adapt `index.php`, `db_connect.php`, `config.ini`, `.htaccess`
- [ ] Copy all `core/` classes
- [ ] Create `app/controllers/`, `app/models/`, `app/views/layouts/`, `app/views/errors/`
- [ ] Create `assets/css/style.css` with design variables
- [ ] Create `lang/en.php` with navigation strings
- [ ] Create database and update `config.ini`
- [ ] Run the minimum SQL (`users` + `settings` tables)
- [ ] Create `HomeController.php` and `app/views/home/index.php`
- [ ] Test that `http://localhost/ProjectFolder/` loads
- [ ] Add features by creating Model + Controller + View triplets
- [ ] Register every new route in `index.php`
