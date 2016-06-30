<?php
namespace TomLerendu\MicroAPI;

class Config
{
    private $defaults = [];
    private $config = [];

    /**
     * Set a key and value in the config service.
     *
     * @param $key - The key the value should be stored under
     * @param $value - The value to store
     */
    public function set($key, $value)
    {
        $this->config[$key] = $value;
    }

    /**
     * Set a default key and value in the config service.
     */
    public function setDefault($key, $value)
    {
        $this->defaults[$key] = $value;
    }

    /**
     * Retrieve a value or default value from the config service using a given key.
     *
     * @param $key - The key the value is stored under
     * @return mixed|null - The value or null if the key doesn't exist
     */
    public function get($key)
    {
        if (isset($this->config[$key])) {
            return $this->config[$key];
        }

        if (isset($this->defaults[$key])) {
            return $this->defaults[$key];
        }

        return null;
    }

    /**
     * Remove a value from the config service
     *
     * @param $key - The key of the value to be removed
     * @return null
     */
    public function remove($key)
    {
        unset($this->config[$key]);
    }
}