<?php

namespace Jacksonsr45\RadiantPHP;

use Jacksonsr45\RadiantPHP\Http\Message\Interfaces\ResponseInterface;
use Jacksonsr45\RadiantPHP\Http\Message\Interfaces\ServerRequestInterface;
use Jacksonsr45\RadiantPHP\Http\Message\ServerRequest;
use Jacksonsr45\RadiantPHP\Http\Message\Stream;
use Jacksonsr45\RadiantPHP\Http\Message\Uri;
use Jacksonsr45\RadiantPHP\Http\Route;
use Jacksonsr45\RadiantPHP\Http\Router;

class ServerRequestFactory
{
    private static $router;

    public static function createServerRequest(string $pathToRoutes = ""): ServerRequestInterface
    {
        $pathToRoutes = $pathToRoutes === "" ? $_ENV["ROUTES_PATH"] : $pathToRoutes;

        Route::registerRouterInstance(new Router());

        require_once $pathToRoutes;

        self::$router = Route::getRouterInstance();

        $scheme = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'];
        $port = $_SERVER['SERVER_PORT'];
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $query = $_SERVER['QUERY_STRING'] ?? '';
        $fragment = '';

        $uri = new Uri($scheme, '', $host, $port, $path, $query, $fragment);

        return new ServerRequest(
            $_SERVER['REQUEST_METHOD'],
            $uri,
            $_SERVER,
            new Stream('php://input'),
        );
    }

    public static function handleRequest(ServerRequestInterface $request): ResponseInterface
    {
        return self::$router->handleRequest($request);
    }

    public static function sendHttpResponse(ResponseInterface $response): void
    {
        http_response_code($response->getStatusCode());
        foreach ($response->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                header("$name: $value", false);
            }
        }

        $body = $response->getBody();
        $output = '';

        if ($body !== null) {
            if ($body->isSeekable()) {
                $body->rewind();
            }

            while (!$body->eof()) {
                $output .= $body->read(1024);
            }
        }

        echo $output;
    }
}
