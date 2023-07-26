<?php

namespace Jacksonsr45\RadiantPHP\Http\Server;

use Jacksonsr45\RadiantPHP\Http\Message\Interfaces\ResponseInterface;
use Jacksonsr45\RadiantPHP\Http\Message\Interfaces\ServerRequestInterface;
use Jacksonsr45\RadiantPHP\Http\Server\Errors\NoMiddlewareOrFallbackError;
use Jacksonsr45\RadiantPHP\Http\Server\Interfaces\RequestHandlerInterface;

class RequestHandler implements RequestHandlerInterface
{
    private array $middlewareQueue = [];
    private $fallbackHandler;

    public function __construct(callable $fallbackHandler = null)
    {
        $this->fallbackHandler = $fallbackHandler;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if (empty($this->middlewareQueue)) {
            return $this->executeFallbackHandler($request);
        }

        $middleware = array_shift($this->middlewareQueue);

        return $middleware->process($request, $this);
    }

    public function pushMiddleware(callable $middleware): void
    {
        $this->middlewareQueue[] = $middleware;
    }

    public function executeFallbackHandler(ServerRequestInterface $request): ResponseInterface
    {
        if ($this->fallbackHandler !== null) {
            $handler = $this->fallbackHandler;
            return $handler($request);
        }

        throw new NoMiddlewareOrFallbackError();
    }
}
