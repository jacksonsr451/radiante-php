<?php

namespace Jacksonsr45\RadiantPHP\Http\Message\Interfaces;

interface MessageInterface
{
    public function getProtocolVersion(): string;

    public function withProtocolVersion($version): MessageInterface;

    public function getHeaders(): array;

    public function hasHeader($name): bool;

    public function getHeader($name): mixed;

    public function getHeaderLine($name): string;

    public function withHeader($name, $value): MessageInterface;

    public function withAddedHeader($name, $value): MessageInterface;

    public function withoutHeader($name): MessageInterface;

    public function getBody(): StreamInterface;

    public function withBody(StreamInterface $body): MessageInterface;
}
