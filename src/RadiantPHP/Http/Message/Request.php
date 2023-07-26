<?php

namespace Jacksonsr45\RadiantPHP\Http\Message;

use Jacksonsr45\RadiantPHP\Http\Message\Interfaces\RequestInterface;
use Jacksonsr45\RadiantPHP\Http\Message\Interfaces\StreamInterface;
use Jacksonsr45\RadiantPHP\Http\Message\Interfaces\UriInterface;

class Request implements RequestInterface
{
    private string $method;
    private UriInterface $uri;
    private array $headers = [];
    private StreamInterface $body;
    private string $protocolVersion = '1.1';
    private mixed $requestTarget;

    public function __construct(
        string $method,
        UriInterface $uri,
        array $headers = [],
        ?StreamInterface $body = null,
        string $protocolVersion = '1.1'
    ) {
        $this->method = $method;
        $this->uri = $uri;
        $this->headers = $headers;
        $this->body = $body;
        $this->protocolVersion = $protocolVersion;
        $this->requestTarget = $this->calculateRequestTarget();
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function withMethod($method): RequestInterface
    {
        $this->method = $method;
        return $this;
    }

    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    public function withUri(UriInterface $uri, $preserveHost = false): RequestInterface
    {
        $this->uri = $uri;

        if (!$preserveHost) {
            $newHost = $uri->getHost();
            if (!empty($newHost)) {
                $this->headers['Host'] = [$newHost];
            }
        }

        $this->requestTarget = $this->calculateRequestTarget();
        return $this;
    }

    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    public function withProtocolVersion($version): RequestInterface
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

    public function withHeader($name, $value): RequestInterface
    {
        $this->headers[$name] = is_array($value) ? $value : [$value];
        return $this;
    }

    public function withAddedHeader($name, $value): RequestInterface
    {
        if (!$this->hasHeader($name)) {
            return $this->withHeader($name, $value);
        }

        $this->headers[$name][] = $value;
        return $this;
    }

    public function withoutHeader($name): RequestInterface
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

    public function withBody(StreamInterface $body): RequestInterface
    {
        $this->body = $body;
        return $this;
    }

    public function getRequestTarget(): mixed
    {
        return $this->requestTarget;
    }

    public function withRequestTarget($requestTarget): RequestInterface
    {
        $this->requestTarget = $requestTarget;
        return $this;
    }

    private function calculateRequestTarget(): mixed
    {
        $target = $this->uri->getPath();
        if ($this->uri->getQuery() !== '') {
            $target .= '?' . $this->uri->getQuery();
        }
        return $target;
    }
}
