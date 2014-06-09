<?php
namespace MicroAPI;

class Router
{
    private $injector;
    private $matchedRule = false;

	public function __construct($injector)
	{
        $this->injector = $injector;
	}

    public function any($rule)
    {
        $rule['method'] = 'ANY';
        $this->route($rule);
    }

    public function get($rule)
    {
        $rule['method'] = 'GET';
        $this->route($rule);
    }

    public function post($rule)
    {
        $rule['method'] = 'POST';
        $this->route($rule);
    }

    public function put($rule)
    {
        $rule['method'] = 'PUT';
        $this->route($rule);
    }

    public function delete($rule)
    {
        $rule['method'] = 'DELETE';
        $this->route($rule);
    }

    private function route($rule)
    {
        //Don't process any rules when the correct rule has already been found
        if($this->matchedRule)
            return;

        $request = $this->injector->getService('request');

        if(
            $this->matchMethod($rule['method'], $request->getMethod()) &&
            $wildcards = $this->matchRoute($rule['route'], $request->getPath())
        )
        {
            $request->setPathWildcards($wildcards);

            //If the controller is a method
            if(isset($rule['object']))
            {
                $controller = explode('@', $rule['object']);
                $controllerName = '\\App\\Controller\\' . $controller[0];
                $controllerMethod = $controller[1];
                $this->injector->injectMethod($controllerName, $controllerMethod);
            }
            //If the controller is a function
            else if(isset($rule['function']))
            {
                $function = require APP_PATH . DIRECTORY_SEPARATOR . 'Controller' . DIRECTORY_SEPARATOR . $rule['function'] . '.php';
                $this->injector->injectFunction($function);
            }

            $this->matchedRule = true;
        }

        //todo: make sure the requirement is satisfied before loading the controller
    }

    /**
     * @param $method
     * @param $requestMethod
     * @return bool
     */
    private function matchMethod($method, $requestMethod)
	{
        if($requestMethod == 'any' || $method == $requestMethod)
            return true;

		return false;
	}

    /**
     * @param $route
     * @param $requestRoute
     * @return array|bool
     */
    private function matchRoute($route, $requestRoute)
	{
        $route = explode('/', trim($route, '/'));
        $requestRoute = explode('/', trim($requestRoute, '/'));

		$params = [];

		//If the route and request path don't have the same number of sections
		if(count($route) !== count($requestRoute))
			return false;

		//For each route and path
		for($i=0; $i<count($route); $i++)
		{
			//Find out if there's a wildcard in the section
			$wildcard = strpos($route[$i], '(?)');

			//If there is and its on its own
			if($wildcard !== false && strlen($route[$i]) === 3)
			{
				$params[] = $requestRoute[$i];
			}
			//If there is and its bordered by other characters 
			else if($wildcard !== false)
			{
				if(
					substr($route[$i], 0, $wildcard) == substr($requestRoute[$i], 0, $wildcard) &&
					substr($requestRoute[$i], -strlen(substr($route[$i], $wildcard+3))) == substr($route[$i], $wildcard+3)
				)
				{
					$routeParts = explode('(?)', $route[$i]);
					$param = substr($requestRoute[$i], strlen($routeParts[0]));
					$param = substr($param, 0, -strlen($routeParts[1]));
					$params[] = $param;
				}
				else if ($route[$i] != '(?)')
					return false;
			}
			//If there's no wildcard make sure the route and path section match
			else if($route[$i] != $requestRoute[$i])
				return false;
		}

		//Return the wildcard values
		return $params;
	}
}