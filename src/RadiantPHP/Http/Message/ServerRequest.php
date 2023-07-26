<?php

namespace Jacksonsr45\RadiantPHP\Http\Message;

use Jacksonsr45\RadiantPHP\Http\Message\Interfaces\ServerRequestInterface;
use Jacksonsr45\RadiantPHP\Http\Message\Interfaces\StreamInterface;
use Jacksonsr45\RadiantPHP\Http\Message\Interfaces\UriInterface;

class ServerRequest extends Request implements ServerRequestInterface
{
    private string $method;
    private UriInterface $uri;
    private array $headers = [];
    private ?StreamInterface $body;
    private string $protocolVersion = '1.1';
    private array $serverParams = [];
    private array $cookieParams = [];
    private array $queryParams = [];
    private array $uploadedFiles = [];
    private mixed $parsedBody;
    private array $attributes = [];
    private string $requestTarget;

    public function __construct(
        string $method,
        UriInterface $uri,
        array $headers = [],
        ?StreamInterface $body = null,
        string $protocolVersion = '1.1',
        array $serverParams = [],
        array $cookieParams = [],
        array $queryParams = [],
        array $uploadedFiles = [],
        mixed $parsedBody = null,
        array $attributes = [],
        string $requestTarget = ''
    ) {
        parent::__construct(
            $method,
            $uri,
            $headers,
            $body,
            $protocolVersion
        );

        $this->method = $method;
        $this->uri = $uri;
        $this->headers = $headers;
        $this->body = $body;
        $this->protocolVersion = $protocolVersion;
        $this->serverParams = $serverParams;
        $this->cookieParams = $cookieParams;
        $this->queryParams = $queryParams;
        $this->uploadedFiles = $uploadedFiles;
        $this->parsedBody = $parsedBody;
        $this->attributes = $attributes;
        $this->requestTarget = $requestTarget;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUri(): UriInterface
    {
        return $this->uri;
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

    public function withHeader($name, $value): ServerRequestInterface
    {
        $this->headers[$name] = is_array($value) ? $value : [$value];
        return $this;
    }

    public function withAddedHeader($name, $value): ServerRequestInterface
    {
        if (!$this->hasHeader($name)) {
            return $this->withHeader($name, $value);
        }

        $this->headers[$name][] = $value;
        return $this;
    }

    public function withoutHeader($name): ServerRequestInterface
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

    public function withBody(StreamInterface $body): ServerRequestInterface
    {
        $this->body = $body;
        return $this;
    }

    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    public function withProtocolVersion($version): ServerRequestInterface
    {
        $this->protocolVersion = $version;
        return $this;
    }

    public function getServerParams(): array
    {
        return $this->serverParams;
    }

    public function getCookieParams(): array
    {
        return $this->cookieParams;
    }

    public function withCookieParams(array $cookies): ServerRequestInterface
    {
        $this->cookieParams = $cookies;
        return $this;
    }

    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    public function withQueryParams(array $query): ServerRequestInterface
    {
        $this->queryParams = $query;
        return $this;
    }

    public function getUploadedFiles(): array
    {
        return $this->uploadedFiles;
    }

    public function withUploadedFiles(array $uploadedFiles): ServerRequestInterface
    {
        $this->uploadedFiles = $uploadedFiles;
        return $this;
    }

    public function getParsedBody(): mixed
    {
        return $this->parsedBody;
    }

    public function withParsedBody($data): ServerRequestInterface
    {
        $this->parsedBody = $data;
        return $this;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getAttribute($name, $default = null): mixed
    {
        return $this->attributes[$name] ?? $default;
    }

    public function withAttribute($name, $value): ServerRequestInterface
    {
        $this->attributes[$name] = $value;
        return $this;
    }

    public function withoutAttribute($name): ServerRequestInterface
    {
        unset($this->attributes[$name]);
        return $this;
    }

    public function getRequestTarget(): string
    {
        return $this->requestTarget;
    }

    public function withRequestTarget($requestTarget): ServerRequestInterface
    {
        $this->requestTarget = $requestTarget;
        return $this;
    }

    public function withMethod($method): ServerRequestInterface
    {
        $this->method = $method;
        return $this;
    }

    public function withUri(UriInterface $uri, $preserveHost = false): ServerRequestInterface
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

    private function calculateRequestTarget(): mixed
    {
        $target = $this->uri->getPath();
        if ($this->uri->getQuery() !== '') {
            $target .= '?' . $this->uri->getQuery();
        }
        return $target;
    }

    public static function fromGlobals(): ServerRequestInterface
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = self::createUriFromGlobals();
        $headers = self::getHeadersFromGlobals();
        $body = new Stream(fopen('php://input', 'r'));
        $protocolVersion = isset(
            $_SERVER['SERVER_PROTOCOL']
        ) ? str_replace('HTTP/', '', $_SERVER['SERVER_PROTOCOL']) : '1.1';

        return new self($method, $uri, $headers, $body, $protocolVersion);
    }

    public static function createUriFromGlobals(): UriInterface
    {
        $scheme = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'];
        $port = $_SERVER['SERVER_PORT'];
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $query = $_SERVER['QUERY_STRING'] ?? '';
        $fragment = '';

        return new Uri($scheme, '', $host, $port, $path, $query, $fragment);
    }

    public static function getHeadersFromGlobals(): array
    {
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) === 'HTTP_') {
                $headerName = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))));
                $headers[$headerName] = $value;
            }
        }
        return $headers;
    }
}
