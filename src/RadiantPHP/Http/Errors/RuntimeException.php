<?php

namespace Jacksonsr45\RadiantPHP\Http\Errors;

use Exception;

class RuntimeException extends Exception
{
    public function __construct($message = null)
    {
        $this->message = $message;
    }
}
