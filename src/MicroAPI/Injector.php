<?php
namespace TomLerendu\MicroAPI;

use ReflectionFunction;
use ReflectionMethod;
use ReflectionClass;
use Exception;

class Injector
{
    /**
     * Returns the single instance of the injector.
     *
     * @return Injector
     */
    public static function getInstance()
    {
        static $instance = null;
        if ($instance === null) {
            $instance = new Injector();
        }
        return $instance;
    }

    private function __construct() {}

    private $services = [];

    /**
     * Add a service that the injector can inject into functions.
     *
     * @param $paramName - The name of the service
     * @param $service - The service, either a class name (string) or the actual service
     * @param array $constructorArgs - Parameters to be passed to the service when it is initialised
     */
    public function addService($paramName, $service, $constructorArgs=null)
    {
        //If the service hasn't been initialised
        if (is_string($service))
            $this->services[$paramName] = [$service, $constructorArgs];
        //If the service has been initialised
        else
            $this->services[$paramName] = $service;
    }

    /**
     * Injects the required services into a given objects method
     *
     * @param $object - The object the method belongs to
     * @param $methodName - The method that will be used for injection
     *
     * @return mixed - The return value of the function that was injected
     */
    private function inject($object, $methodName)
    {
        $reflection = new ReflectionMethod($object, $methodName);
        $params = $reflection->getParameters();
        $dependencies = $this->getDependencies($params);

        return call_user_func_array([$object, $methodName], $dependencies);
    }

    /**
     * Generates an array of services using an array of parameters
     *
     * @param $params - The names of the services required
     * @return array - An array of services
     * @throws \Exception
     */
    private function getDependencies($params)
    {
        $dependencies = [];
        foreach ($params as $param)
        {
            if (isset($this->services[$param->name])) {
                $dependencies[] = $this->getService($param->name);
            } else
                throw new Exception("Could not inject service '" . $param->name . "'.");
        }

        return $dependencies;
    }

    /**
     * Gets the corresponding service for a given parameter
     *
     * @param $paramName - The parameter to get the service for
     * @return mixed - The service or null if the service doesn't exist
     */
    public function getService($paramName)
    {
        //If the service exists
        if (isset($this->services[$paramName])) {
            //If the service instance hasn't been created yet
            if (is_array($this->services[$paramName])) {
                $service = $this->services[$paramName];
                //If the service has parameters in the constructor
                if ($service[1] !== null) {
                    //Get the arguments the constructor takes
                    $class = new ReflectionClass($service[0]);
                    $constructorArguments = $class->getConstructor()->getParameters();
                    //Create an array of parameters
                    $constructorParams = [];
                    foreach ($constructorArguments as $constructorArgument)
                    {
                        $constructorParams[$constructorArgument->getName()] =
                            (isset($service[1][$constructorArgument->getName()])) ?
                                $service[1][$constructorArgument->getName()] : null;
                    }
                    $service = $class->newInstanceArgs($constructorParams);
                }
                //If the service does not have parameters in the constructor
                else
                    $service = new $service[0]();
                $this->services[$paramName] = $service;
            }

            return $this->services[$paramName];
        }

        return null;
    }
} 