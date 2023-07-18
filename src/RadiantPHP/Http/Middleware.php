<?php

namespace Jacksonsr45\RadiantPHP\Http;

abstract class Middleware
{
    public function handle($request, $response, $next)
    {
        // Lógica do middleware usando o atributo
        $next($request, $response);
    }
}
