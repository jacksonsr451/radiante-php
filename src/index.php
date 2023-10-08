<?php

use Jacksonsr45\RadiantPHP\ContainerFactory;
use Jacksonsr45\RadiantPHP\Http\Message\Interfaces\ResponseInterface;
use Jacksonsr45\RadiantPHP\Http\Message\Response;
use Jacksonsr45\RadiantPHP\ServerRequestFactory;

use function DI\create;

require_once __DIR__ . '/../vendor/autoload.php';

$container = ContainerFactory::build();

$container->set(ResponseInterface::class, create(Response::class));

$request = ServerRequestFactory::createServerRequest(__DIR__ . '/routes.php', $container);
$response = ServerRequestFactory::handleRequest($request);
ServerRequestFactory::sendHttpResponse($response);
