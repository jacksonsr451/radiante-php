<?php

namespace Jacksonsr45\RadiantPHP\Http;

class Response
{
    private $statusCode;
    private $headers;
    private $body;

    public function __construct($statusCode = 200)
    {
        $this->statusCode = $statusCode;
        $this->headers = [];
        $this->body = '';
    }

    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }

    public function setHeader($name, $value)
    {
        $this->headers[$name] = $value;
    }

    public function setBody($body)
    {
        $this->body = $body;
    }

    public function send()
    {
        http_response_code($this->statusCode);
        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }

        echo $this->body;
    }

    public function sendJson($data)
    {
        $this->setHeader('Content-Type', 'application/json');
        $json = json_encode($data, JSON_UNESCAPED_UNICODE);

        $this->setBody($json);
        $this->send();
    }

    public function getHeaders()
    {
        return $this->headers;
    }
}
