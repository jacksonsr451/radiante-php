<?php

namespace Jacksonsr45\RadiantPHP\Http\Message\Errors;

use Exception;

class UnableToDetermineStreamPositionError extends Exception
{
    public function __construct()
    {
        $this->message = 'Unable to determine stream position.';
    }
}
