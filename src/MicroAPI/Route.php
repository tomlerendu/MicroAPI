<?php
namespace MicroAPI;

class Route
{
    private $require = null;
    private $wildcards = null;

    /**
     * Set the wildcards in the route
     *
     * @param $wildcards - The wildcards in the route
     */
    public function setWildcards($wildcards)
    {
        $this->wildcards = array_map('urldecode', $wildcards);
    }

    /**
     * Get the wildcards from the route
     *
     * @return array - An assoc array of the routes wildcards
     */
    public function getWildcards()
    {
        return $this->wildcards;
    }

    /**
     * Get a wildcard from the route
     *
     * @param $name - The name of the wildcard to get
     * @return string - The wildcard if one exists, null if it not
     */
    public function getWildcard($name)
    {
        return isset($this->wildcards[$name]) ? $this->wildcards[$name] : null;
    }

    /**
     * Set the object returned from the routes require function
     *
     * @param $object - The object returned from the routes require function
     */
    public function setRequire($object)
    {
        $this->require = $object;
    }

    /**
     * Get the object returned from the routes require function
     *
     * @return mixed - The object returned from the routes require function
     */
    public function getRequire()
    {
        return $this->require;
    }
}