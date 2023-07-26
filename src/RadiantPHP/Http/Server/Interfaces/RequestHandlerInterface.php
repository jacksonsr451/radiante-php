<?php

namespace Jacksonsr45\RadiantPHP\Http\Server\Interfaces;

use Jacksonsr45\RadiantPHP\Http\Message\Interfaces\ResponseInterface;
use Jacksonsr45\RadiantPHP\Http\Message\Interfaces\ServerRequestInterface;

interface RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface;
}
