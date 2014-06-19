<?php

namespace MicroAPI;

class Config
{
    private $config = [];

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