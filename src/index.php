<?php

use Jacksonsr45\RadiantPHP\Http\Message\ServerRequest;
use Jacksonsr45\RadiantPHP\Http\Message\Stream;
use Jacksonsr45\RadiantPHP\Http\Message\Uri;
use Jacksonsr45\RadiantPHP\Http\Route;
use Jacksonsr45\RadiantPHP\Http\Router;

require_once __DIR__ . '/../vendor/autoload.php';

Route::registerRouterInstance(new Router());

require_once __DIR__ . '/../src/routes.php';

$router = Route::getRouterInstance();

$scheme = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'];
$port = $_SERVER['SERVER_PORT'];
$path = $_SERVER['REQUEST_URI'] ?? '/';
$query = $_SERVER['QUERY_STRING'] ?? '';
$fragment = '';

$uri = new Uri($scheme, '', $host, $port, $path, $query, $fragment);

$request = new ServerRequest(
    $_SERVER['REQUEST_METHOD'],
    $uri,
    $_SERVER,
    new Stream('php://input'),
);

$response = $router->handleRequest($request);

$body = $response->getBody();

http_response_code($response->getStatusCode());
foreach ($response->getHeaders() as $name => $values) {
    foreach ($values as $value) {
        header("$name: $value", false);
    }
}

if ($body !== null) {
    if ($body->isSeekable()) {
        $body->rewind();
    }

    while (!$body->eof()) {
        echo $body->read(1024);
    }
}
