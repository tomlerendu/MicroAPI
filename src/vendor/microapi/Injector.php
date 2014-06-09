<?php

namespace MicroAPI;

use ReflectionFunction;
use ReflectionMethod;
use Exception;

class Injector
{
    private $services = [];

    /**
     * @param $paramName
     * @param $service
     * @param array $config
     */
    public function addDependency($paramName, $service, $config=null)
    {
        if(is_string($service))
            $this->services[$paramName] = [$service, $config];
        else
            $this->services[$paramName] = $service;
    }

    /**
     * Injects the required services into a class method
     *
     * @param $className - The class the method belongs to
     * @param $methodName - The method that will be used for injection
     *
     * @throws Exception
     */
    public function injectMethod($className, $methodName)
    {
        $reflection = new ReflectionMethod($className, $methodName);
        $params = $reflection->getParameters();
        $dependencies = $this->getDependencies($params);

        call_user_func_array([new $className, $methodName], $dependencies);
    }

    public function injectFunction($functionName)
    {
        $reflection = new ReflectionFunction($functionName);
        $params = $reflection->getParameters();
        $dependencies = $this->getDependencies($params);

        call_user_func_array($functionName, $dependencies);
    }

    private function getDependencies($params)
    {
        $dependencies = [];
        foreach($params as $param)
        {
            if(isset($this->services[$param->name]))
            {
                $dependencies[] = $this->getService($param->name);
            }
            else
                throw new Exception("Could not inject service '" . $param->name . "'.");
        }

        return $dependencies;
    }

    /**
     * @param $paramName
     * @return mixed
     */
    public function getService($paramName)
    {
        //If the service exists
        if(isset($this->services[$paramName]))
        {
            //If the service instance hasn't been created yet
            if(is_array($this->services[$paramName]))
            {
                $service = $this->services[$paramName];
                if($service[1] !== null)
                    $service = new $service[0]($service[1]);
                else
                    $service = new $service[0]();
                $this->services[$paramName] = $service;
            }

            return $this->services[$paramName];
        }

        return null;
    }
} 