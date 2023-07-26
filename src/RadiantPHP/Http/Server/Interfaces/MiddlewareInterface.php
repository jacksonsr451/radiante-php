<?php

namespace Jacksonsr45\RadiantPHP\Http\Server\Interfaces;

use Jacksonsr45\RadiantPHP\Http\Message\Interfaces\ResponseInterface;
use Jacksonsr45\RadiantPHP\Http\Message\Interfaces\ServerRequestInterface;
use Jacksonsr45\RadiantPHP\Http\Server\Interfaces\RequestHandlerInterface;

interface MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface;
}
