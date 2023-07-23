<?php

namespace Jacksonsr45\RadiantPHP\Http\Errors;

use Exception;

class JsonError extends Exception
{
    public function __construct()
    {
        $this->message = 'Erro ao decodificar a resposta JSON!';
    }
}
