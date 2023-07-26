<?php

namespace Jacksonsr45\RadiantPHP\Http\Message\Errors;

use Exception;

class StreamIsNotSeekableError extends Exception
{
    public function __construct()
    {
        $this->message = 'Stream is not seekable.';
    }
}
