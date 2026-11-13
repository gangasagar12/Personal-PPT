<?php
namespace App\Core;

class Router {
    private static array $routes = [];

    public static function get(string $path, callable|array $handler): void {
        self::$routes['GET'][$path] = $handler;
    }

    public static function post(string $path, callable|array $handler): void {
        self::$routes['POST'][$path] = $handler;
    }

    public static function dispatch(): void {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

        // Normalize URI
        $uri = rtrim($uri, '/');
        if ($uri === '' || $uri === '/index.php') {
            $uri = '/';
        }

        // Check exact match
        if (isset(self::$routes[$method][$uri])) {
            $handler = self::$routes[$method][$uri];
            self::executeHandler($handler);
            return;
        }

        // Default home fallback if root or index
        if ($uri === '/' || $uri === '') {
            $controller = new \App\Controllers\HomeController();
            $controller->index();
            return;
        }

        // 404 Not Found
        http_response_code(404);
        echo "<h1 style='font-family:sans-serif; text-align:center; margin-top:5rem;'>404 — Page Not Found</h1>";
    }

    private static function executeHandler(callable|array $handler): void {
        if (is_array($handler)) {
            list($class, $action) = $handler;
            $instance = new $class();
            $instance->$action();
        } else {
            call_user_func($handler);
        }
    }
}

