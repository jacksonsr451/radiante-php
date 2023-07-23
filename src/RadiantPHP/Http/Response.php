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

    public function setStatusCode($statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    public function setHeader($name, $value): void
    {
        $this->headers[$name] = $value;
    }

    public function setBody($body): void
    {
        $this->body = $body;
    }

    public function send(): void
    {
        http_response_code($this->statusCode);
        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }

        echo $this->body;
    }

    public function sendJson($data): void
    {
        $this->setHeader('Content-Type', 'application/json');
        $json = json_encode($data, JSON_UNESCAPED_UNICODE);

        $this->setBody($json);
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getBody(): mixed
    {
        return $this->body;
    }
}
