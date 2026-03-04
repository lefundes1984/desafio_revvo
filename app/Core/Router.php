<?php

declare(strict_types=1);

namespace App\Core;

use Closure;

class Router
{
    private array $routes = [];

    public function get(string $path, callable|array $handler): self
    {
        return $this->add('GET', $path, $handler);
    }

    public function post(string $path, callable|array $handler): self
    {
        return $this->add('POST', $path, $handler);
    }

    public function put(string $path, callable|array $handler): self
    {
        return $this->add('PUT', $path, $handler);
    }

    public function delete(string $path, callable|array $handler): self
    {
        return $this->add('DELETE', $path, $handler);
    }

    private function add(string $method, string $path, callable|array $handler): self
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => rtrim($path, '/') ?: '/',
            'handler' => $handler,
        ];

        return $this;
    }

    public function dispatch(Request $request): Response
    {
        $currentPath = rtrim($request->getPath(), '/') ?: '/';
        $method = $request->getMethod();

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $pattern = $this->transformPathToRegex($route['path']);
            if (preg_match($pattern, $currentPath, $matches)) {
                $params = $this->extractNamedParameters($matches);

                return $this->invokeHandler($route['handler'], $request, $params);
            }
        }

        return Response::json(['message' => 'Not Found'], 404);
    }

    private function transformPathToRegex(string $path): string
    {
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $path);

        return '#^' . $pattern . '$#';
    }

    private function extractNamedParameters(array $matches): array
    {
        $params = [];
        foreach ($matches as $key => $value) {
            if (!is_string($key)) {
                continue;
            }
            $params[$key] = $value;
        }

        return $params;
    }

    private function invokeHandler(callable|array $handler, Request $request, array $params): Response
    {
        $callable = $handler;

        if (is_array($handler) && is_string($handler[0])) {
            $className = $handler[0];
            $method = $handler[1] ?? '__invoke';
            $instance = new $className();
            $callable = [$instance, $method];
        }

        $reflection = is_array($callable)
            ? new \ReflectionMethod($callable[0], $callable[1])
            : new \ReflectionFunction($callable);

        $arguments = [$request, $params];
        $arguments = array_slice($arguments, 0, $reflection->getNumberOfParameters());

        $result = $callable(...$arguments);

        if ($result instanceof Response) {
            return $result;
        }

        return Response::from($result);
    }
}
