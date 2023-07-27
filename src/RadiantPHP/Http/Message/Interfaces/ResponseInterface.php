<?php

namespace Jacksonsr45\RadiantPHP\Http\Message\Interfaces;

interface ResponseInterface extends MessageInterface
{
    public function getStatusCode(): int;

    public function setStatusCode($code): ResponseInterface;

    public function withStatus($code, $reasonPhrase = '');

    public function getReasonPhrase();
}
