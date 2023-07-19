<?php

namespace Tests\Http;

use Jacksonsr45\RadiantPHP\Http\Request;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function testGetMethod()
    {
        $method = 'GET';
        $path = '/example';

        $request = new Request($method, $path);

        $this->assertEquals($method, $request->getMethod());
    }

    public function testGetPath()
    {
        $method = 'POST';
        $path = '/example';

        $request = new Request($method, $path);

        $this->assertEquals($path, $request->getPath());
    }
}
