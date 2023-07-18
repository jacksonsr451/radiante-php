<?php

namespace Jacksonsr45\RadiantPHP\Http;

use Exception;

class Route
{
    private $wildcards;
    private $params;
    private $method;
    private $path;
    private $middlewares;
    private $controller;
    private $action;
    private $paransKeys;

    public function __construct($wildcards, $method, $path, $middlewares, $controller, $action)
    {
        $this->wildcards = $wildcards;
        $this->method = $method;
        $this->path = $path;
        $this->middlewares = $middlewares;
        $this->controller = $controller;
        $this->action = $action;
        $this->params = [];
        $this->paransKeys = [];
    }

    public function match($request)
    {
        $pattern = $this->convertPathToPattern($this->path);
        $path = trim($request->getPath(), '/');

        if ($request->getMethod() !== $this->method) {
            return false;
        }

        $wildcards = str_replace('/', '\/', $pattern);

        if (preg_match("/^$wildcards$/", trim($path, "/"))) {
            $explodeUri = explode("/", $path);
            $explodePattern = explode("/", $pattern);
            $parans = array_diff($explodeUri, $explodePattern);

            foreach ($parans as $key => $value) {
                $this->params[$this->paransKeys[$key]] = $value;
            }

            return true;
        }

        return false;
    }

    private function convertPathToPattern($path)
    {
        foreach (explode('/', ltrim($path, '/')) as $key => $value) {
            if (str_contains($value, '<')) {
                $result = explode(":", $value);
                $values[$key] = "<:" . $result[1];
                $this->paransKeys[$key] = str_replace('<', '', $result[0]);
                if (str_contains($values[$key], '<:string>')) {
                    $values[$key] = $this->wildcards['<:string>'];
                }
                if (str_contains($values[$key], '<:number>')) {
                    $values[$key] = $this->wildcards['<:number>'];
                }
                if (str_contains($values[$key], '<:any>')) {
                    $values[$key] = $this->wildcards['<:any>'];
                }
            } else {
                $values[$key] = $value;
            }
        }

        return ltrim(implode('/', $values), "/");
    }

    public function execute($request, $response)
    {
        $middlewares = $this->middlewares;
        $params = $this->params;
        try {
            $next = function ($request, $response) use (&$middlewares, &$next, $params) {
                if (!empty($middlewares)) {
                    $middleware = new (array_shift($middlewares))();
                    $middleware->handle($request, $response, $next);
                } else {
                    $controller = $this->controller;
                    $action = $this->action;
                    $instance = new $controller();

                    if (method_exists($instance, $action)) {
                        $instance->$action(...$params);
                    }
                }
            };

            $next($request, $response);
        } catch (Exception $e) {
            $response->sendJson(['error' => $e->getMessage()]);
            die();
        }
    }
}
