<?php

namespace Tests\Http;

use Jacksonsr45\RadiantPHP\Http\Router;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    public function testAddRoute()
    {
        $router = new Router();

        $method = 'GET';
        $path = '/example';
        $controller = 'ExampleController';
        $action = 'index';
        $middlewares = ['auth', 'log'];

        $router->add($method, $path, $controller, $action, $middlewares);

        $routes = $this->getPrivateProperty($router, 'routes');
        $this->assertCount(1, $routes);

        $route = $routes[0];
        $this->assertEquals($method, $this->getPrivateProperty($route, 'method'));
        $this->assertEquals($path, $this->getPrivateProperty($route, 'path'));
        $this->assertEquals($middlewares, $this->getPrivateProperty($route, 'middlewares'));
        $this->assertEquals($controller, $this->getPrivateProperty($route, 'controller'));
        $this->assertEquals($action, $this->getPrivateProperty($route, 'action'));
    }

    public function testGroup()
    {
        $router = new Router();

        $prefix = '/api';

        $router->group($prefix, function ($router) {
            $this->assertInstanceOf(Router::class, $router);
            $this->assertEquals('/api', $this->getPrivateProperty($router, 'prefix'));
        });
    }

    private function getPrivateProperty($object, $property)
    {
        $reflection = new \ReflectionClass($object);
        $property = $reflection->getProperty($property);
        $property->setAccessible(true);
        return $property->getValue($object);
    }
}
