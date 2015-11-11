<?php
/**
 * This file is part of Gear.
 *
 * @copyright 2015 Loïc Marchand
 * @license http://www.spdx.org/licenses/MIT MIT License
 */

namespace Gear\Core;

use \SplObjectStorage;
use \ArrayAccess;
use \InvalidArgumentException;
use \RuntimeException;

/**
 * Main Dependency Injection Container class.
 */
class DIContainer implements ArrayAccess
{

    /**
     * Values contains on the container.
     *
     * @var array $values
     */
    protected $values = [];

    /**
     * Contains container factories.
     *
     * @var \SplObjectStorage $factories
     */
    protected $factories;

    /**
     * Values hold. You can't modify hold value.
     *
     * @var array $holdValues
     */
    protected $holdValues = [];


    /**
     * Default constructor.
     */
    public function __construct()
    {
        $this->factories = new SplObjectStorage();
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
        return isset($this->values[$offset]);
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
        if ($this->offsetExists($offset) === false) {
            throw new RuntimeException('');
        }

        if (!is_callable($this->values[$offset])) {
            return $this->values[$offset];
        }

        if ($this->factories->contains($this->values[$offset])) {
            return $this->values[$offset]($this); // Instanciate new object.
        }

        $value = $this->values[$offset]($this);
        $this->values[$offset] = $value;
        $this->holdValues[$offset] = true;

        return $value;
    }

    /**
     * Magic method. Access data like an object property.
     *
     * @param string $offset
     *
     * @return mixed
     */
    public function __get($offset)
    {
        return $this->offsetGet($offset);
    }

    /**
     * Set data like an array.
     *
     * @param string $offset
     * @param mixed $value
     *
     * @return void
     *
     * @throw \InvalidArgumentException
     * @throw \RuntimeException
     */
    public function offsetSet($offset, $value)
    {
        if ($offset === null) {
            throw new InvalidArgumentException('Can\'t set empty offset.');
        }

        if (isset($this->holdValues[$offset]) && $this->holdValues[$offset] === true) {
            throw new RuntimeException('Can not override service ' . $offset);
        }

        if ($this->offsetExists($offset)) {
            $this->offsetUnset($offset); // Clear old value.
        }

        $this->values[$offset] = $value;
    }

    /**
     * Magic method. Set data like an object property.
     *
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    public function __set($offset, $value)
    {
        $this->offsetSet($offset, $value);
    }

    /**
     * Unset value.
     *
     * @param string $offset
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->factories[$this->values[$offset]]);
        unset($this->values[$offset], $this->holdValues[$offset]);
    }

    /**
     * Used to generate factory.
     *
     * @param callable $callable
     *
     * @return callable
     *
     * @throw InvalidArgumentException
     */
    public function factory($callable)
    {
        if (!is_object($callable) || !method_exists($callable, '__invoke')) {
            throw new InvalidArgumentException('Only callable can add for factory.');
        }

        $this->factories->attach($callable);
        return $callable;
    }
}
