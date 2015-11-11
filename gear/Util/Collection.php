<?php
/**
 * This file is part of Gear.
 *
 * @copyright 2015 Loïc Marchand
 * @license   http://www.spdx.org/licenses/MIT MIT License
 */

namespace Gear\Util;

use \ArrayAccess;
use \Iterator;
use \Countable;

/**
 * Represents a set of data accessible with array notation or object notation.
 */
class Collection implements ArrayAccess, Iterator, Countable
{

    /**
     * Set of data
     *
     * @var array $data
     */
    protected $data = [];


    /**
     * Default constructor with default data.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->setData($data);
    }

    /**
     * Clear collection data.
     *
     * @return void
     */
    public function clear()
    {
        $this->data = [];
    }

    /**
     * Get collection data.
     *
     * @return array Collection data.
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set collection data.
     *
     * @param array $data
     *
     * @return void
     */
    public function setData(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * Collection keys.
     *
     * @return array All the keys.
     */
    public function keys()
    {
        return array_keys($this->data);
    }

    /**
     * Magic method. Access data like an object property.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        return $this->offsetGet($key);
    }

    /**
     * Magic method. Set data like an object property.
     *
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    public function __set($key, $value)
    {
        $this->offsetSet($key, $value);
    }

    /**
     * Check if $key exists.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function __isset($key)
    {
        return isset($this->data[$key]);
    }

    /**
     * Unset collection value.
     *
     * @param string $key
     *
     * @return void
     */
    public function __unset($key)
    {
        unset($this->data[$key]);
    }

    /**
     * Check if $offset exists.
     *
     * @param string $offset
     *
     * @return mixed
     */
    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    /**
     * Access data like an array
     *
     * @param string $offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return (isset($this->data[$offset]) ? $this->data[$offset] : null);
    }

    /**
     * Set data like an array.
     *
     * @param string $offset
     * @param mixed $value
     *
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if ($offset === null) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    /**
     * Unset collection value.
     *
     * @param string $offset
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    /**
     * Return current collection value.
     *
     * @return mixed
     */
    public function current()
    {
        return current($this->data);
    }

    /**
     * Return collection current key.
     *
     * @return string
     */
    public function key()
    {
        return key($this->data);
    }

    /**
     * Move to next collection value.
     *
     * @return void
     */
    public function next()
    {
        next($this->data);
    }

    /**
     * Move to first collection value.
     *
     * @return void
     */
    public function rewind()
    {
        reset($this->data);
    }

    /**
     * Check if key is valid.
     *
     * @return boolean True if key is valid, false otherwise.
     */
    public function valid()
    {
        $key = $this->key();
        return ($key !== false && $key !== null);
    }

    /**
     * Count number of element.
     *
     * @return integer
     */
    public function count()
    {
        return count($this->data);
    }
}
