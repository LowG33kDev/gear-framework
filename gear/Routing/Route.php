<?php
/**
 * This file is part of Gear.
 *
 * @copyright 2015 Loïc Marchand
 * @license http://www.spdx.org/licenses/MIT MIT License
 */

namespace Gear\Routing;

/**
 * Route class represent a route, with path, and callback called when match this.
 */
class Route
{

    /**
     * Route path.
     *
     * @var string
     */
    protected $path = '';

    /**
     * Route callback.
     *
     * @var callable
     */
    protected $callable = null;

    /**
     * Params matches.
     *
     * @var array
     */
    protected $matches = [];

    /**
     *
     */
    protected $params = [];

    /**
     * Contains HTTP method allowed for this route.
     *
     * @var array
     */
    protected $methods = [];


    /**
     * Default constructor.
     *
     * @param string $path Route path.
     * @param callable $callable Callback used when call this route.
     * @param string|array HTTP Verb or array with HTTP verbs accepted. '*' for all HTTP verbs.
     */
    public function __construct($path, $callable, $methods = 'GET')
    {
        $this->path = trim($path, '/');
        $this->callable = $callable;
        if (is_array($methods)) {
            $this->methods = $methods;
        } else {
            if ($methods === '*') {
                $this->methods = ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'];
            } else {
                $this->methods[] = $methods;
            }
        }
    }

    /**
     * Add regexp test for parameters.
     *
     * @param string|array $params
     * @param string $regexp
     *
     * @return \Gear\Routing\Route Self instance.
     */
    public function with($params, $regexp = '')
    {
        if (is_array($params)) {
            foreach ($params as $param => $reg) {
                $this->with($param, $reg);
            }
        } else {
            $this->params[$params] = str_replace('(', '(?:', $regexp);
        }
        return $this;
    }

    /**
     * Check if url passed mathced with route.
     *
     * @param string $url URL.
     *
     * @return boolean True if url matched, false otherwise.
     */
    public function match($url)
    {
        $this->matches = [];
        $url = trim($url, '/');
        $path = preg_replace_callback('#:([\w]+)#', [$this, 'matchParams'], $this->path);
        if (!preg_match("#^$path$#i", $url, $matches)) {
            return false;
        }
        array_shift($matches);
        $this->matches = $matches;
        return true;
    }

    /**
     * Replace with user regexp for match parameter.
     *
     * @param array $match
     *
     * @return string
     */
    protected function matchParams($match)
    {
        if (isset($this->params[$match[1]])) {
            return '(' . $this->params[$match[1]] . ')';
        }
        return '([^/]+)';
    }

    /**
     * Check if route support request method.
     *
     * @param string $method Method.
     *
     * @return boolean True if route accept this method, false otherwise.
     */
    public function hasMethod($method)
    {
        return in_array($method, $this->methods);
    }

    /**
     * Run route callback.
     *
     * @return mixed Callback return.
     */
    public function call()
    {
        return call_user_func_array($this->callable, $this->matches);
    }

    /**
     * Get matched values.
     *
     * @return array
     */
    public function getMatches()
    {
        return $this->matches;
    }
}
