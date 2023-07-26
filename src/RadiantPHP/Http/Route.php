<?php

namespace Jacksonsr45\RadiantPHP\Http;

class Route
{
    private static $instanceStack = [];

    public static function get(string $path, array $handler): void
    {
        self::addRoute('GET', $path, $handler);
    }

    public static function post(string $path, array $handler): void
    {
        self::addRoute('POST', $path, $handler);
    }

    public static function put(string $path, array $handler): void
    {
        self::addRoute('PUT', $path, $handler);
    }

    public static function delete(string $path, array $handler): void
    {
        self::addRoute('DELETE', $path, $handler);
    }

    public static function patch(string $path, array $handler): void
    {
        self::addRoute('PATCH', $path, $handler);
    }

    public static function group(array $attributes, callable $callback): void
    {
        $router = end(self::$instanceStack);
        $router->group($attributes, $callback);
    }

    private static function addRoute(string $method, string $path, array $handler): void
    {
        $router = end(self::$instanceStack);

        $router->addRoute($method, $path, $handler);
    }

    public static function registerRouterInstance(Router $router): void
    {
        self::$instanceStack[] = $router;
    }

    public static function getRouterInstance(): Router
    {
        return end(self::$instanceStack);
    }
}
