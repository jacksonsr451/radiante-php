<?php

namespace Jacksonsr45\RadiantPHP\Http;

use Jacksonsr45\RadiantPHP\Http\Errors\JsonError;

class Request
{
    private $method;
    private $path;

    public function __construct($method, $path)
    {
        $this->method = $method;
        $this->path = $path;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getJson()
    {
        $url = $this->path;

        $contextOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ];

        $responseJson = file_get_contents($url, false, stream_context_create($contextOptions));

        $data = json_decode($responseJson, true); // true para obter um array associativo

        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new JsonError();
        }

        return $data;
    }
}
