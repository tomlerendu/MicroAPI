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
            ($wildcards = $this->matchRoute($rule['route'], $request->getPath())) !== false &&
            ((isset($rule['require']) && $this->injector->inject($rule['require'])) || !isset($rule['require']))
        )
        {
            $request->setPathWildcards($wildcards);

            //If the controller is a method
            if(isset($rule['object']))
            {
                $controller = explode('@', $rule['object']);
                $controllerName = '\\App\\Controller\\' . $controller[0];
                $controllerMethod = $controller[1];
                $this->injector->inject([$controllerName, $controllerMethod]);
            }
            //If the controller is a function
            else if(isset($rule['function']) && is_array($rule['function']))
            {
                if(is_array($rule['function']))
                {
                    $funcFile = $rule['function'][0];
                    $funcName = $rule['function'][1];
                }
                else
                {
                    $funcFile = $rule['function'];
                    $funcName = $rule['function'];
                }

                require_once APP_PATH . DIRECTORY_SEPARATOR . 'Controller' . DIRECTORY_SEPARATOR . $funcFile . '.php';
                $this->injector->inject($funcName);
            }

            $this->matchedRule = true;
        }
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

		//For each route and path part
		for($i=0; $i<count($route); $i++)
		{
            $regex = preg_match_all('(\(.*?\))', $route[$i], $routeParams);
            $routeParams = $routeParams[0];

            //If there are wildcards
            if($regex)
            {
                $quotedRouteParams = array_map('preg_quote', $routeParams);
                $matchPattern = preg_quote($route[$i]);
                $matchPattern = '/^' . str_replace($quotedRouteParams, '(.*?)', $matchPattern) . '$/';

                if(preg_match($matchPattern, $requestRoute[$i], $matchedParams))
                {
                    for($i=0; $i<count($routeParams); $i++)
                        $params[trim($routeParams[$i], '()')] = $matchedParams[$i+1];
                }
            }
            //If there's no wildcard make sure the route and path section match
            else if($route[$i] !== $requestRoute[$i])
                return false;
		}

		//Return the wildcard values
		return $params;
	}
}