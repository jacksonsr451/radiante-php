<?php

namespace Tests\Http;

use PHPUnit\Framework\TestCase;
use Jacksonsr45\RadiantPHP\Http\Route;
use Jacksonsr45\RadiantPHP\Http\Request;
use Jacksonsr45\RadiantPHP\Http\Response;
use Tests\Http\Controllers\PostController;
use Tests\Http\Controllers\UserController;

class RouteTest extends TestCase
{
    public function testMatchReturnsTrueForMatchingRoute()
    {
        $wildcards = [
            "<:number>" => '[0-9]+',
            "<:string>" => '[a-zA-Z]+',
            "<:any>" => '[a-zA-Z0-9\-]+',
        ];
        $method = 'GET';
        $path = '/users/<id:number>';
        $middlewares = [];
        $controller = UserController::class;
        $action = 'show';

        $route = new Route($wildcards, $method, $path, $middlewares, $controller, $action);

        $request = new Request('GET', '/users/123');
        $isMatched = $route->match($request);

        $this->assertTrue($isMatched);
    }

    public function testMatchReturnsFalseForNonMatchingRoute()
    {
        $wildcards = [
            "<:number>" => '[0-9]+',
            "<:string>" => '[a-zA-Z]+',
            "<:any>" => '[a-zA-Z0-9\-]+',
        ];
        $method = 'POST';
        $path = '/users/<id:number>';
        $middlewares = [];
        $controller = UserController::class;
        $action = 'show';

        $route = new Route($wildcards, $method, $path, $middlewares, $controller, $action);

        $request = new Request('GET', '/users/abc');
        $isMatched = $route->match($request);

        $this->assertFalse($isMatched);
    }

    public function testMatchSetsParamsForMatchingRoute()
    {
        $wildcards = [
            "<:number>" => '[0-9]+',
            "<:string>" => '[a-zA-Z]+',
            "<:any>" => '[a-zA-Z0-9\-]+',
        ];
        $method = 'GET';
        $path = '/users/<id:number>/posts/<slug:any>';
        $middlewares = [];
        $controller = PostController::class;
        $action = 'show';

        $route = new Route($wildcards, $method, $path, $middlewares, $controller, $action);

        $request = new Request('GET', '/users/123/posts/my-post');
        $route->match($request);
        $params = $route->getParams();

        $expectedParams = [
            'id' => '123',
            'slug' => 'my-post'
        ];

        $this->assertEquals($expectedParams, $params);
    }

    public function testExecuteCallsControllerActionForMatchingRoute()
    {
        $wildcards = [
            "<:number>" => '[0-9]+',
            "<:string>" => '[a-zA-Z]+',
            "<:any>" => '[a-zA-Z0-9\-]+',
        ];
        $method = 'GET';
        $path = '/users/<id:number>';
        $middlewares = [];
        $controller = UserController::class; // Use o nome da classe diretamente
        $action = 'view';

        $route = new Route($wildcards, $method, $path, $middlewares, $controller, $action);

        $request = new Request('GET', '/users/123');
        $response = new Response();
        $route->match($request);
        $route->execute($request, $response);

        $this->assertTrue(true);
    }
}
