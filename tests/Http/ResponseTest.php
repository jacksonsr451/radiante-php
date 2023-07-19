<?php

namespace Tests\Http;

use Jacksonsr45\RadiantPHP\Http\Request;
use Jacksonsr45\RadiantPHP\Http\Response;
use PHPUnit\Framework\TestCase;


class ResponseTest extends TestCase
{
    public function testSend()
    {
        $request = $this->createMock(Request::class);

        $response = new Response();
        $response->setHeader('Content-Type', 'text/html');
        $response->send($request);

        $headers = $response->getHeaders();

        $this->assertEquals(['Content-Type' => 'text/html'], $headers);
    }

    public function testSendJson()
    {
        $request = $this->createMock(Request::class);

        $response = new Response();
        $response->sendJson($request, ['message' => 'Hello, world!']);

        $headers = $response->getHeaders();

        $this->assertEquals(['Content-Type' => 'application/json'], $headers);
    }
}
