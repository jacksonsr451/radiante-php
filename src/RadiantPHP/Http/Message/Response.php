<?php

namespace Jacksonsr45\RadiantPHP\Http\Message;

use Jacksonsr45\RadiantPHP\Http\Message\Interfaces\MessageInterface;
use Jacksonsr45\RadiantPHP\Http\Message\Interfaces\ResponseInterface;
use Jacksonsr45\RadiantPHP\Http\Message\Interfaces\StreamInterface;

class Response implements ResponseInterface
{
    private int $statusCode;
    private string $reasonPhrase = '';
    private array $headers = [];
    private StreamInterface $body;
    private string $protocolVersion = '1.1';

    public function __construct()
    {
        $this->statusCode = 200;
        $this->body = new Stream(fopen('php://temp', 'r+'));
        $this->headers = [];
        $this->protocolVersion = '1.1';
        $this->reasonPhrase = '';
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getReasonPhrase(): string
    {
        return $this->reasonPhrase;
    }

    public function withStatus($code, $reasonPhrase = ''): ResponseInterface
    {
        $this->statusCode = $code;
        $this->reasonPhrase = $reasonPhrase;
        return $this;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function hasHeader($name): bool
    {
        return isset($this->headers[$name]);
    }

    public function getHeader($name): array
    {
        return $this->headers[$name] ?? [];
    }

    public function getHeaderLine($name): string
    {
        return implode(', ', $this->headers[$name] ?? []);
    }

    public function withHeader($name, $value): ResponseInterface
    {
        $this->headers[$name] = is_array($value) ? $value : [$value];
        return $this;
    }

    public function withAddedHeader($name, $value): ResponseInterface
    {
        if (!$this->hasHeader($name)) {
            return $this->withHeader($name, $value);
        }

        $this->headers[$name][] = $value;
        return $this;
    }

    public function withoutHeader($name): ResponseInterface
    {
        if (!$this->hasHeader($name)) {
            return $this;
        }

        unset($this->headers[$name]);
        return $this;
    }

    public function getBody(): StreamInterface
    {
        return $this->body;
    }

    public function setStatusCode($code): ResponseInterface
    {
        $this->statusCode = $code;
        return $this;
    }

    public function withBody(StreamInterface $body): MessageInterface
    {
        $this->body = $body;
        return $this;
    }

    public function write(string $content): ResponseInterface
    {
        $body = $this->getBody();
        $body->write($content);

        $this->withBody($body);
        return $this;
    }

    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    public function withProtocolVersion($version): ResponseInterface
    {
        $this->protocolVersion = $version;
        return $this;
    }
}
