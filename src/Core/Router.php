<?php

declare(strict_types=1);

namespace Src\Core;

use Src\App\Enums\HttpStatus;

class Router
{
    private array $routes = [];
    private string $basePath;

    public function __construct(?string $basePath = null)
    {
        if ($basePath === null) {
            $basePath = $this->detectBasePath();
        } elseif (str_contains($basePath, 'http')) {
            $basePath = parse_url($basePath, PHP_URL_PATH) ?? '';
        }

        $this->basePath = rtrim($basePath, '/');
    }

    public function get(string $path, array $action, array $middleware = []): void
    {
        $this->routes[] = ['GET', $path, $action, $middleware];
    }

    public function post(string $path, array $action, array $middleware = []): void
    {
        $this->routes[] = ['POST', $path, $action, $middleware];
    }

    public function run(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $this->getUri();

        foreach ($this->routes as [$routeMethod, $routePath, $action, $middleware]) {
            if ($routeMethod !== $method) {
                continue;
            }

            $params = $this->match($routePath, $uri);

            if ($params !== false) {
                $this->runMiddlewares($middleware);
                echo $this->call($action, $params);
                return;
            }
        }

        http_response_code(HttpStatus::NOT_FOUND->value);
        echo \Src\App\Http\Controllers\ErrorController::notFound();
    }

    private function detectBasePath(): string
    {
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $basePath = dirname($scriptName);
        $basePath = str_replace('\\', '/', $basePath);
        $basePath = preg_replace('#/public.*#', '', $basePath);
        return rtrim($basePath, '/');
    }

    private function getUri(): string
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        if ($this->basePath) {
            $uri = substr($uri, strlen($this->basePath)) ?: '/';
        }

        // Se o usuário acessar com /public/ na URL, removemos para que a rota funcione normalmente
        if (str_starts_with($uri, '/public')) {
            $uri = substr($uri, 7) ?: '/';
        }

        return $uri;
    }

    private function runMiddlewares(array $middlewares): void
    {
        foreach ($middlewares as $middlewareClass) {
            $middleware = Container::get($middlewareClass);
            $middleware->handle();
        }
    }

    private function match(string $route, string $uri): array|false
    {
        if ($route === $uri) {
            return [];
        }

        $pattern = preg_replace('/\{(\w+)\}/', '([^/]+)', $route);

        if (preg_match('#^' . $pattern . '$#', $uri, $values)) {
            preg_match_all('/\{(\w+)\}/', $route, $keys);

            $params = [];
            foreach ($keys[1] as $i => $key) {
                $params[$key] = $values[$i + 1];
            }
            return $params;
        }

        return false;
    }

    private function call(array $action, array $params): string
    {
        [$controller, $method] = $action;

        $instance = Container::get($controller);

        $result = empty($params)
            ? $instance->$method()
            : $instance->$method(...array_values($params));

        return $result ?? '';
    }
}
