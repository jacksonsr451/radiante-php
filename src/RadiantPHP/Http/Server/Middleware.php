<?php

namespace Jacksonsr45\RadiantPHP\Http\Server;

use Jacksonsr45\RadiantPHP\Http\Message\Interfaces\ResponseInterface;
use Jacksonsr45\RadiantPHP\Http\Message\Interfaces\ServerRequestInterface;
use Jacksonsr45\RadiantPHP\Http\Server\Interfaces\MiddlewareInterface;
use Jacksonsr45\RadiantPHP\Http\Server\Interfaces\RequestHandlerInterface;

class Middleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Your middleware logic goes here
        // For example, you can manipulate the request or response, add headers, etc.

        // Call the next middleware in the queue or the final handler
        return $handler->handle($request);
    }
}
