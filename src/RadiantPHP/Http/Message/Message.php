<?php

namespace Jacksonsr45\RadiantPHP\Http\Message;

use Jacksonsr45\RadiantPHP\Http\Message\Interfaces\MessageInterface;
use Jacksonsr45\RadiantPHP\Http\Message\Interfaces\StreamInterface;

class Message implements MessageInterface
{
    private string $protocolVersion;
    private array $headers = [];
    private StreamInterface $body;

    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    public function withProtocolVersion($version): MessageInterface
    {
        $this->protocolVersion = $version;
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

    public function getHeader($name): mixed
    {
        return $this->headers[$name] ?? [];
    }

    public function getHeaderLine($name): string
    {
        return implode(', ', $this->headers[$name] ?? []);
    }

    public function withHeader($name, $value): MessageInterface
    {
        $this->headers[$name] = is_array($value) ? $value : [$value];
        return $this;
    }

    public function withAddedHeader($name, $value): MessageInterface
    {
        if (!$this->hasHeader($name)) {
            return $this->withHeader($name, $value);
        }

        $this->headers[$name][] = $value;
        return $this;
    }

    public function withoutHeader($name): MessageInterface
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

    public function withBody(StreamInterface $body): MessageInterface
    {
        $this->body = $body;
        return $this;
    }
}
