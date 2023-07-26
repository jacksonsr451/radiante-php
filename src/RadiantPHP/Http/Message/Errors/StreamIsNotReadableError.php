<?php

namespace Jacksonsr45\RadiantPHP\Http\Message\Errors;

use Exception;

class StreamIsNotReadableError extends Exception
{
    public function __construct()
    {
        $this->message = 'Stream is not readable.';
    }
}
