<?php

namespace Jacksonsr45\RadiantPHP\Http;

abstract class Controllers
{
    protected Response $response;

    public function __construct()
    {
        $this->response = new Response();
    }
}
