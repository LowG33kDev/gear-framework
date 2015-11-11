<?php
/**
 * This file is part of Gear.
 *
 * @copyright 2015 Loïc Marchand
 * @license http://www.spdx.org/licenses/MIT MIT License
 */

namespace Gear\Routing;

use \Gear\Routing\Route;
use \Gear\Network\Request;
use \RuntimeException;

/**
 * Represent class route url.
 */
class Router
{

    /**
     * Array contains routes.
     *
     * @var array $routes
     */
    protected $routes = [];


    /**
     * Add new route.
     *
     * @param string $path
     * @param callable $callable
     * @param string|array $methods
     *
     * @return \Gear\Routing\Router Self instance.
     */
    public function addRoute($path, $callable, $methods = 'GET')
    {
        $route = new Route($path, $callable, $methods);
        $this->routes[] = $route;
        return $route;
    }

    /**
     * Find route needed by the request, and call this.
     *
     * @param \Gear\Network\Request $request
     *
     * @return mixed
     *
     * @throw \RuntimeException If route not found.
     */
    public function run(Request $request)
    {
        foreach ($this->routes as $route) {
            if ($route->hasMethod($request->method) && $route->match($request->url)) {
                return $route->call();
            }
        }
        throw new RuntimeException('Route not found');
    }
}
