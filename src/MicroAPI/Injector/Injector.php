<?php
namespace TomLerendu\MicroAPI\Injector;

use ReflectionMethod;
use TomLerendu\MicroAPI\Exception;

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
    private $serviceParams = [];

    /**
     * Add a service that the injector can inject into functions.
     *
     * @param mixed $service The service, either a fully qualified class name (::class) or the actual service object
     * @param array $constructorArgs Parameters to be passed to the service when it is initialised
     */
    public function addService($service, array $constructorArgs = [])
    {
        //If the service has been initialised
        if (is_object($service)) {
            $serviceClass = get_class($service);
            $this->services[$serviceClass] = $service;
        }
        //If the service hasn't been initialised
        else {
            $this->serviceParams[$service] = $constructorArgs;
        }
    }

    /**
     * Injects the required services into a given objects method.
     *
     * @param mixed $object - The object the method belongs to
     * @param string $methodName The method that will be used for injection
     * @return mixed The return value of the function that was injected
     */
    public function injectMethod($object, string $methodName)
    {
        $reflection = new ReflectionMethod($object, $methodName);
        $params = $reflection->getParameters();
        $dependencies = $this->getDependencies($params);

        return call_user_func_array([$object, $methodName], $dependencies);
    }

    /**
    * Injects the required services into a function.
    *
    * @param mixed $functionName The name of the function that will be used for injection
    * @return mixed The return value of the function that was injected
    */
    public function injectFunction($functionName)
    {
        $reflection = new \ReflectionFunction($functionName);
        $params = $reflection->getParameters();
        $dependencies = $this->getDependencies($params);

        return call_user_func_array($functionName, $dependencies);
    }

    /**
     * Generates an array of services using an array of parameters.
     *
     * @param array $params The names of the services required
     * @return array An array of services
     * @throws Exception
     */
    private function getDependencies($params): array
    {
        $dependencies = [];

        foreach ($params as $param)
        {
            if ($param->getClass() !== null) {
                $dependencies[] = $this->getService($param->getClass()->name);
            } else {
                $name = $param->name;
                throw new Exception("Could not inject dependencies. Missing type hint from param '" . $name . "'.");
            }
        }

        return $dependencies;
    }

    /**
     * Gets the corresponding service for a given parameter.
     *
     * @param mixed $fqn The services fully qualified class name
     * @return mixed The service or null if it doesn't exist
     * @throws Exception Throws an exception of ther service couldn't be found or created
     */
    public function getService($fqn)
    {
        //If the service exists
        if (isset($this->services[$fqn])) {
            return $this->services[$fqn];
        }

        //If the service instance hasn't been created yet
        if (isset($this->serviceParams[$fqn])) {

            $service = new $fqn(...$this->serviceParams[$fqn]);
            $this->services[$fqn] = $service;
            unset($this->serviceParams[$fqn]);

            return $service;
        }

        throw new Exception("Could not inject service '" . $fqn . "' into method.");
    }
} 