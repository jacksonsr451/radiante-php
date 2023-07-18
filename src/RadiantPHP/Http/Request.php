<?php

namespace Jacksonsr45\RadiantPHP\Http;

class Request
{
    private $method;
    private $path;

    public function __construct($method, $path)
    {
        $this->method = $method;
        $this->path = $path;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getPath()
    {
        return $this->path;
    }
}
