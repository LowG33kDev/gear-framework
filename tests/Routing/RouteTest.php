<?php

namespace Gear\Test\Routing;

use \Gear\Routing\Route;

class RouteTest extends \PHPUnit_Framework_TestCase
{
    public function testCall()
    {
        $route = new Route('/', function(){return 'hello';});
        $this->assertEquals('hello', $route->call());

        $route = new Route('/blog/:name', function($name){return 'hello ' . $name;});
        $route->match('/blog/john');
        $this->assertEquals('hello john', $route->call());
    }

    public function testMethods()
    {
        $route = new Route('/', function(){return 'hello';});
        $this->assertTrue($route->hasMethod('GET'));
        $this->assertFalse($route->hasMethod('POST'));
        $this->assertFalse($route->hasMethod('PUT'));
        $this->assertFalse($route->hasMethod('DELETE'));

        $route = new Route('/', function(){return 'hello';}, '*');
        $this->assertTrue($route->hasMethod('GET'));
        $this->assertTrue($route->hasMethod('POST'));
        $this->assertTrue($route->hasMethod('PUT'));
        $this->assertTrue($route->hasMethod('DELETE'));
    }

    public function testMatch()
    {
        $route = new Route('/', function(){return 'hello';});
        $this->assertTrue($route->match('/'));
        $route = new Route('/blog', function(){return 'hello';});
        $this->assertTrue($route->match('/blog'));
    }

    public function testMatchWith()
    {
        $route = new Route('/blog/:id-:slug', function($id, $slug){return 'hello';});
        $route->with(['id' => '[0-9]+']);
        $this->assertTrue($route->match('/blog/12-my-title'));
        $this->assertEquals('12', $route->getMatches()[0]);
        $this->assertFalse($route->match('/blog/my-title-12'));
        $this->assertEquals(0, count($route->getMatches()));
    }
}
