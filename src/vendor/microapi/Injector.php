<?php

namespace MicroAPI;

use ReflectionParameter;

class Injector
{
    private $services = [];

    public function addDependency($paramName, $service, $config=[])
    {
        $this->services[$paramName] = [$service, $config];
    }

    /**
     * Injects the required services into a class method
     *
     * @param $class - The class the method belongs to
     * @param $method - The method that will be used for injection
     */
    public function inject($class, $method)
    {
        $params = new ReflectionParameter($class, $method);
        $params = $params->getParameters();
    }

    /**
     * Creates the instance of a service with a given param name.
     *
     * @param $paramName - The param name
     */
    private function createService($paramName)
    {

    }
} 