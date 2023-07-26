<?php

namespace Jacksonsr45\RadiantPHP\Http\Server\Errors;

use Exception;

class NoMiddlewareOrFallbackError extends Exception
{
    public function __construct()
    {
        $this->message = 'No middleware or fallback handler found to process the request.';
    }
}
