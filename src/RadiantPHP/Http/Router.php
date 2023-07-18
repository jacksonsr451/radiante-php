<?php

namespace Jacksonsr45\RadiantPHP\Http;

class Router
{
    private $wildcards;
    private $routes;
    private $prefix;

    public function __construct()
    {
        $this->wildcards = [
            "<:number>" => '[0-9]+',
            "<:string>" => '[a-zA-Z]+',
            "<:any>" => '[a-zA-Z0-9\-]+',
        ];
        $this->routes = [];
    }

    public function add($method, $path, $controller, $action, $middlewares = [])
    {
        $path = $this->initPathWithPrefix($path);
        $this->routes[] = new Route($this->wildcards, $method, $path, $middlewares, $controller, $action);
    }

    private function initPathWithPrefix($path)
    {
        if ($path === "/") {
            $path = $this->prefix;
        } else {
            $path = $this->prefix . $path;
        }
        return $path;
    }

    public function group($prefix, $callback)
    {
        $this->prefix = $prefix;

        if (is_callable($callback)) {
            call_user_func($callback, $this);
        }
    }

    public function handleRequest($request)
    {
        $response = new Response();
        $matchedRoute = null;

        foreach ($this->routes as $route) {
            if ($route->match($request)) {
                $matchedRoute = $route;
                break;
            }
        }

        if ($matchedRoute) {
            $matchedRoute->execute($request, $response);
        } else {
            $response->setStatusCode(404);
            $response->setBody('404 - Página não encontrada');
        }

        $response->send();
    }
}
