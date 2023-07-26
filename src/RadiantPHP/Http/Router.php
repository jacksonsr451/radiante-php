<?php

namespace Jacksonsr45\RadiantPHP\Http;

use Jacksonsr45\RadiantPHP\Http\Errors\RuntimeException;
use Jacksonsr45\RadiantPHP\Http\Message\Interfaces\ResponseInterface;
use Jacksonsr45\RadiantPHP\Http\Message\Interfaces\ServerRequestInterface;
use Jacksonsr45\RadiantPHP\Http\Message\Response;

class Router
{
    private array $routes = [];
    private array $routeGroups = [];
    protected array $middleware = [];

    public function addMiddleware($middleware)
    {
        $this->middleware[] = $middleware;
    }

    public function addRoute(string $method, string $path, array $handler): void
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler,
        ];
    }

    public function handleRequest(ServerRequestInterface $request): ResponseInterface
    {
        foreach ($this->middleware as $middleware) {
            $request = $middleware->process($request);
        }

        $path = $request->getUri()->getPath();
        $method = $request->getMethod();

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['path'] === $path) {
                return $this->executeHandler($route['handler'], $request);
            }
        }

        $matchedRoute = $this->matchGroupedRoute($method, $path);
        if ($matchedRoute !== null) {
            $handler = $matchedRoute['handler'];
            return $this->executeHandler($handler, $request);
        }

        $response = new Response();
        $response->write('Not Found');
        return $response->setStatusCode(404);
    }

    private function matchGroupedRoute(string $method, string $path): ?array
    {
        foreach ($this->routeGroups as $group) {
            if ($group['method'] === $method && strpos($path, $group['prefix']) === 0) {
                $adjustedPath = substr($path, strlen($group['prefix']));
                foreach ($group['routes'] as $route) {
                    if ($route['method'] === $method && $route['path'] === $adjustedPath) {
                        return $route;
                    }
                }
            }
        }

        return null;
    }

    public function group(array $attributes, callable $callback): void
    {
        $currentRouteGroups = $this->routeGroups;
        $this->routeGroups[] = $attributes;

        $callback($this);

        $this->routeGroups = $currentRouteGroups;
    }

    private function executeHandler(array $handler, ServerRequestInterface $request): ResponseInterface
    {
        if (is_array($handler) && count($handler) === 2 && is_string($handler[0]) && is_string($handler[1])) {

            $controllerClass = $handler[0];
            $action = $handler[1];

            $controller = new $controllerClass();

            $result = call_user_func([$controller, $action], $request);

            if (is_string($result) || is_array($result)) {
                $response = new Response();
                $response->getBody()->write(json_encode($result));

                $headers = $response->getHeaders();
                if (!isset($headers['Content-Type'])) {
                    $response = $response->withHeader('Content-Type', 'application/json');
                }

                return $response;
            }

            return $result;
        }

        if (is_callable($handler)) {
            return $handler($request);
        }

        throw new RuntimeException('Invalid handler provided.');
    }
}
