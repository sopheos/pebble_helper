<?php

namespace Pebble\Helpers;

use Traversable;

/**
 * Data Class
 * Manage data stored in an array
 *
 * @author mathieu
 */
class Data implements \Countable, \IteratorAggregate, \JsonSerializable
{
    /**
     * @var array
     */
    protected $data = [];

    // -------------------------------------------------------------------------

    /**
     * Check if a data exists
     *
     * @param string $name
     * @return boolean
     */
    public function has($name)
    {
        return isset($this->data[$name]);
    }

    // -------------------------------------------------------------------------

    /**
     * Returns data
     *
     * @param string $name
     * @param string $default
     * @return mixed
     */
    public function &get($name, $default = NULL)
    {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        }

        return $default;
    }

    // -------------------------------------------------------------------------

    /**
     * Returns all data
     *
     * @return array
     */
    public function &all()
    {
        return $this->data;
    }

    // -------------------------------------------------------------------------

    /**
     * Set data
     *
     * @param string $name
     * @param mixed $value
     * @return static
     */
    public function set($name, $value)
    {
        $this->data[$name] = $value;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Clean all
     *
     * @return \Pebble\Helpers\Data
     */
    public function clear()
    {
        $this->data = [];

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Import data
     *
     * @param array $data
     * @return \Pebble\Helpers\Data
     */
    public function import(array $data)
    {
        foreach ($data as $k => $v) {
            $this->data[$k] = $v;
        }

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Hydrate data
     *
     * @param array $data
     * @return \Pebble\Helpers\Data
     */
    public function setData(array $data)
    {
        $this->data = $data;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Delete a data
     *
     * @param string $name
     * @return \Pebble\EasyRecord\ERSession
     */
    public function delete($name)
    {
        if (isset($this->data[$name])) {
            unset($this->data[$name]);
        }

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Count the number of data
     */
    public function count(): int
    {
        return count($this->data);
    }

    // -------------------------------------------------------------------------

    /**
     * Returns an external iterator
     *
     * @return \ArrayIterator
     */
    public function getIterator(): Traversable
    {
        return new \ArrayIterator($this->data);
    }

    /**
     * @return array
     */
    public function jsonSerialize(): mixed
    {
        return $this->data;
    }

    // -------------------------------------------------------------------------
}

/* End of file */
