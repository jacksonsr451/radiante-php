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

    public function setStatusCode($statusCode): Response
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function setHeader($name, $value): Response
    {
        $this->headers[$name] = $value;
        return $this;
    }

    public function setBody($body): Response
    {
        $this->body = $body;
        return $this;
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
