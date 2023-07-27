<?php

namespace Jacksonsr45\RadiantPHP\Http\Message\Interfaces;

interface ResponseInterface extends MessageInterface
{
    public function getStatusCode();

    public function withStatus($code, $reasonPhrase = '');

    public function getReasonPhrase();

    public function write(string $content): ResponseInterface;

    public function withJson(string $json): ResponseInterface;

    function getJson(): mixed;
}
