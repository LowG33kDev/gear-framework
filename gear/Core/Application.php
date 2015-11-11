<?php
/**
 * This file is part of Gear.
 *
 * @copyright 2015 Loïc Marchand
 * @license http://www.spdx.org/licenses/MIT MIT License
 */

namespace Gear\Core;

use \Gear\Core\DIContainer;
use \Gear\Network\Request;
use \Gear\Routing\Router;

/**
 * This class is base of application.
 *
 * @property \Gear\Routing\Router $router Application router.
 * @property \Gear\Network\Request $defaultRequest
 * @property string $responseClass
 */
class Application extends DIContainer
{

    /**
     * Default constructor.
     *
     * @param array $settings
     */
    public function __construct(array $settings = [])
    {
        parent::__construct();

        $this->initialize();

        foreach ($settings as $key => $value) {
            $this->offsetSet($key, $value);
        }
    }

    /**
     * This method used to initialize application.
     *
     * When inherit Application, only overload this, the constrcutor called
     * this automatically.
     *
     * @return void
     */
    protected function initialize()
    {
        $this->defaultRequest = $this->factory(function () {
            return new Request();
        });

        $this->router = function () {
            return new Router();
        };

        $this->responseClass = '\\Gear\\Network\\Response';
    }

    /**
     * Run application with request.
     *
     * @param \Gear\Network\Request $request
     *
     * @return mixed The return of called route.
     */
    public function start(Request $request = null)
    {
        if ($request === null || !($request instanceof Request)) {
            $request = $this->defaultRequest;
        }

        $this->router->run($request);
    }

    /**
     * Alias for $app->router->addRoute.
     *
     * @param string $pattern
     * @param callable $route
     * @param string|array $methods
     *
     * @return mixed
     */
    public function addRoute($pattern, $route, $methods = 'GET')
    {
        return $this->router->addRoute($pattern, $route, $methods);
    }
}
