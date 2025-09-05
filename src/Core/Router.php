<?php

namespace App\Core;

use App\Core\RateLimiter;

class Router
{
    private static array $routes = [];
    private string $prefix;
    private RateLimiter $rateLimiter;

    public function __construct($prefix = "")
    {
        $this->prefix = $prefix;
        $this->rateLimiter = new RateLimiter();
    }

        public static function group($prefix, $callback): void
    {
        $callback(new self($prefix));
    }

    public function get($path, $callback): void
    {
        self::$routes[] = ['method' => 'GET', 'path' => $this->prefix . $path, 'callback' => $callback];
    }

    public function post($path, $callback): void
    {
        self::$routes[] = ['method' => 'POST', 'path' => $this->prefix . $path, 'callback' => $callback];
    }

    public function delete($path, $callback): void
    {
        self::$routes[] = ['method' => 'DELETE', 'path' => $this->prefix . $path, 'callback' => $callback];
    }

    public function put($path, $callback): void
    {
        self::$routes[] = ['method' => 'PUT', 'path' => $this->prefix . $path, 'callback' => $callback];
    }

    public function patch($path, $callback): void
    {
        self::$routes[] = ['method' => 'PATCH', 'path' => $this->prefix . $path, 'callback' => $callback];
    }

    private static function createPattern($path)
    {
        return '#^' . preg_replace('/\{[a-zA-Z0-9_]+\}/', '([^/]+)', $path) . '$#';
    }

    public static function match($method, $path)
    {
        foreach (self::$routes as $route) {
            $pattern = self::createPattern($route['path']);
            if ($route['method'] === $method && preg_match($pattern, $path, $matches)) {
                array_shift($matches);
                return ['callback' => $route['callback'], 'params' => $matches];
            }
        }
        return null;
    }

    public static function dispatch($method, $path): void
    {
        $requestUri = strtok($path, '?');
        $ip = $_SERVER['REMOTE_ADDR'];

        $routeInstance = new self();
        if (!$routeInstance->rateLimiter->checkRateLimit($ip)) {
            self::rateLimited();
            return;
        }

        $result = self::match($method, $requestUri);
        if ($result) {
            $callback = $result['callback'];
            $params = $result['params'];
            if (is_array($callback)) {
                $controller = new $callback[0]();
                call_user_func_array([$controller, $callback[1]], $params);
            } else {
                call_user_func_array($callback, $params);
            }
        } else {
            self::notFound();
        }
    }

    public static function notFound(): void
    {
        http_response_code(404);
        echo '<!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>404 Not Found</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
                <style>
                    body {
                        background-color: #f8f9fa;
                    }
                    .error-container {
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        height: 100vh;
                    }
                    .error-content {
                        text-align: center;
                    }
                </style>
            </head>
            <body>
            
            <div class="error-container">
                <div class="error-content">
                    <h1 class="display-1">404</h1>
                    <p class="lead">Oops! Sayfa bulunamadı.</p>
                    <a href="/" class="btn btn-primary">Ana Sayfaya Dön</a>
                </div>
            </div>
            
            </body>
            </html>
            ';
    }

    public static function rateLimited(): void
    {
        http_response_code(429);
        echo '<!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>429 Cooldown</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
                <style>
                    body {
                        background-color: #f8f9fa;
                    }
                    .error-container {
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        height: 100vh;
                    }
                    .error-content {
                        text-align: center;
                    }
                </style>
            </head>
            <body>
            
            <div class="error-container">
                <div class="error-content">
                    <h1 class="display-1">429</h1>
                    <p class="lead">Çok fazla istek gönderdiniz, lütfen bekleyin.</p>
                </div>
            </div>
            
            </body>
            </html>
            ';
    }
}
?>
