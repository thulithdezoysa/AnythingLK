<?php
// ============================================================
// ROUTER — Clean URL routing engine
// Supports: GET, POST | Dynamic slugs {param} | Regex
// ============================================================
class Router {

    private array $routes = [];
    private string $basePath = '';

    public function __construct(?string $basePath = null) {
        if ($basePath !== null) {
            $this->basePath = $basePath;
        } else {
            $scriptDir = dirname($_SERVER['SCRIPT_NAME']);
            $this->basePath = ($scriptDir === '/' || $scriptDir === '\\') ? '' : $scriptDir;
        }
    }

    // --------------------------------------------------------
    // Register routes
    // --------------------------------------------------------
    public function get(string $pattern, string $controller, string $method): void {
        $this->add('GET', $pattern, $controller, $method);
    }

    public function post(string $pattern, string $controller, string $method): void {
        $this->add('POST', $pattern, $controller, $method);
    }

    private function add(string $httpMethod, string $pattern, string $controller, string $action): void {
        // Convert {param} placeholders to named capture groups
        $regex = preg_replace('/\{([a-zA-Z_]+)\}/', '(?P<$1>[^/]+)', $pattern);
        $this->routes[] = [
            'method'     => $httpMethod,
            'pattern'    => '#^' . $regex . '$#',
            'controller' => $controller,
            'action'     => $action,
        ];
    }

    // --------------------------------------------------------
    // Dispatch the request
    // --------------------------------------------------------
    public function dispatch(): void {
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri        = $this->getUri();

        foreach ($this->routes as $route) {
            if ($route['method'] !== $httpMethod) continue;

            if (preg_match($route['pattern'], $uri, $matches)) {
                // Extract named params
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                $controllerName = $route['controller'];
                $action         = $route['action'];

                // Load controller if not already loaded
                if (!class_exists($controllerName)) {
                    $controllerFile = APP . '/controllers/' . $controllerName . '.php';
                    if (!file_exists($controllerFile)) {
                        $this->error(500, "Controller not found: $controllerName");
                        return;
                    }
                    require_once $controllerFile;
                }

                $ctrl = new $controllerName();

                if (!method_exists($ctrl, $action)) {
                    $this->error(500, "Action not found: $controllerName::$action");
                    return;
                }

                $ctrl->$action($params);
                return;
            }
        }

        // No route matched → 404
        $this->error(404);
    }

    // --------------------------------------------------------
    // Get clean URI
    // --------------------------------------------------------
    private function getUri(): string {
        $uri = $_SERVER['REQUEST_URI'];

        // Strip base path
        if ($this->basePath && str_starts_with($uri, $this->basePath)) {
            $uri = substr($uri, strlen($this->basePath));
        }

        // Strip query string
        if (($pos = strpos($uri, '?')) !== false) {
            $uri = substr($uri, 0, $pos);
        }

        return trim($uri, '/');
    }

    // --------------------------------------------------------
    // Error pages
    // --------------------------------------------------------
    private function error(int $code, string $message = ''): void {
        http_response_code($code);
        $view = APP . "/views/errors/{$code}.php";
        if (file_exists($view)) {
            require $view;
        } else {
            echo "<h1>Error $code</h1><p>$message</p>";
        }
    }
}
