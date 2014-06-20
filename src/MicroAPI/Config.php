<?php

namespace MicroAPI;

class Config
{
    private $config = [];

    /**
     * Set a key and value in the config service
     *
     * @param $key - The key the value should be stored under
     * @param $value - The value to store
     */
    public function set($key, $value)
    {
        $key = explode('.', $key);
        $ref = &$this->config;

        for($i=0; $i<count($key); $i++)
        {
            $keyPart = $key[$i];

            if($i === count($key)-1)
                $ref[$keyPart] = $value;
            if(!isset($ref[$keyPart]))
                $ref[$keyPart] = [];

            $ref = &$ref[$keyPart];
        }
    }

    /**
     * Retrieve a value from the config service using a given key
     *
     * @param $key - The key the value is stored under
     * @return mixed|null - The value or null if the key doesn't exist
     */
    public function get($key)
    {
        $key = explode('.', $key);
        $ref = &$this->config;

        foreach($key as $keyPart)
        {
            if(isset($ref[$keyPart]))
                $ref = &$ref[$keyPart];
            else
                return null;
        }

        return $ref;
    }

    /**
     * Remove a value from the config service
     *
     * @param $key - The key of the value to be removed
     * @return mixed|null - The value that was removed or null if the key doesn't exist
     */
    public function remove($key)
    {
        $key = explode('.', $key);
        $ref = &$this->config;

        for($i=0; $i<count($key); $i++)
        {
            $keyPart = $key[$i];

            if($i === count($key)-1)
            {
                $value = $ref[$keyPart];
                unset($ref[$keyPart]);
                return $value;
            }
            else if(isset($ref[$keyPart]))
                $ref = &$ref[$keyPart];
            else
                return null;
        }
    }
}