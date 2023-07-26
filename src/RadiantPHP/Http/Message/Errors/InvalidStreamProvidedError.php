<?php

namespace Jacksonsr45\RadiantPHP\Http\Message\Errors;

use Exception;

class InvalidStreamProvidedError extends Exception
{
    public function __construct()
    {
        $this->message = 'Invalid stream provided.';
    }
}
