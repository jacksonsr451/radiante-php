<?php

namespace Jacksonsr45\RadiantPHP\Http\Message\Interfaces;

interface RequestInterface extends MessageInterface
{
    public function getRequestTarget();

    public function withRequestTarget($requestTarget);

    public function getMethod();

    public function withMethod($method);

    public function getUri();

    public function withUri(UriInterface $uri, $preserveHost = false);

    public function write(string $data): RequestInterface;

    public function withJsonPayload(string $json): RequestInterface;

    function getJson(): mixed;
}
