<?php

namespace Jacksonsr45\RadiantPHP\Http\Message;

use Jacksonsr45\RadiantPHP\Http\Message\Interfaces\UriInterface;

class Uri implements UriInterface
{
    private string $scheme = '';
    private string $userInfo = '';
    private string $host = '';
    private string $port;
    private string $path = '';
    private string $query = '';
    private string $fragment = '';

    public function __construct(
        string $scheme = '',
        string $userInfo = '',
        string $host = '',
        ?string $port = null,
        string $path = '',
        string $query = '',
        string $fragment = ''
    ) {
        $this->scheme = $scheme;
        $this->userInfo = $userInfo;
        $this->host = $host;
        $this->port = $port;
        $this->path = $path;
        $this->query = $query;
        $this->fragment = $fragment;
    }

    public function getScheme(): string
    {
        return $this->scheme;
    }

    public function getAuthority(): string
    {
        $authority = '';

        if (!empty($this->userInfo) || !empty($this->host)) {
            $authority .= $this->userInfo;

            if (!empty($authority)) {
                $authority .= '@';
            }

            $authority .= $this->host;

            if ($this->port !== null) {
                $authority .= ':' . $this->port;
            }
        }

        return $authority;
    }

    public function getUserInfo(): string
    {
        return $this->userInfo;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): string
    {
        return $this->port;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getQuery(): string
    {
        return $this->query;
    }

    public function getFragment(): string
    {
        return $this->fragment;
    }

    public function withScheme($scheme): UriInterface
    {
        $this->scheme = $scheme;
        return $this;
    }

    public function withUserInfo($user, $password = null): UriInterface
    {
        $this->userInfo = $user;
        if ($password !== null) {
            $this->userInfo .= ':' . $password;
        }
        return $this;
    }

    public function withHost($host): UriInterface
    {
        $this->host = $host;
        return $this;
    }

    public function withPort($port): UriInterface
    {
        $this->port = $port;
        return $this;
    }

    public function withPath($path): UriInterface
    {
        $this->path = $path;
        return $this;
    }

    public function withQuery($query): UriInterface
    {
        $this->query = $query;
        return $this;
    }

    public function withFragment($fragment): UriInterface
    {
        $this->fragment = $fragment;
        return $this;
    }

    public function __toString(): string
    {
        $uri = '';

        if (!empty($this->scheme)) {
            $uri .= $this->scheme . ':';
        }

        $authority = $this->getAuthority();
        if (!empty($authority)) {
            $uri .= '//' . $authority;
        }

        if (!empty($this->path)) {
            $uri .= $this->path;
        }

        if (!empty($this->query)) {
            $uri .= '?' . $this->query;
        }

        if (!empty($this->fragment)) {
            $uri .= '#' . $this->fragment;
        }

        return $uri;
    }
}
