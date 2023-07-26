<?php

namespace Jacksonsr45\RadiantPHP\Http\Message\Errors;

use Exception;

class StreamIsDetachedError extends Exception
{
    public function __construct()
    {
        $this->message = 'Stream is detached.';
    }
}
