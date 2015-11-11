<?php

namespace Gear\Test\Routing;

use \Gear\Routing\Route;
use \Gear\Routing\Router;

class RouterTest extends \PHPUnit_Framework_TestCase
{
    public function testAddRoute()
    {
        $routeExpected = new Route('/', function(){return 'hello';});
        $router = new Router();
        $route = $router->addRoute('/', function(){return 'hello';});
        $this->assertEquals($routeExpected, $route);
        $route = $router->addRoute('/blog', function(){return 'hello';});
        $this->assertNotEquals($routeExpected, $route);
    }
}
