<?php

namespace Jacksonsr45\RadiantPHP\Http;

abstract class Controllers
{
    protected Request $request;
    protected Response $response;

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }
}
