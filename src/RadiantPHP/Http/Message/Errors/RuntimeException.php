<?php

namespace Jacksonsr45\RadiantPHP\Http\Message\Errors;

use Exception;

class RuntimeException extends Exception
{
    public function __construct(string $message)
    {
        $this->message = $message;
    }
}
