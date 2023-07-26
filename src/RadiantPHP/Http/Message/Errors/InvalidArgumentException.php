<?php

namespace Jacksonsr45\RadiantPHP\Http\Message\Errors;

use Exception;

class InvalidArgumentException extends Exception
{
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
