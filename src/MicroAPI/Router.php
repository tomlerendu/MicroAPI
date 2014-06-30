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
        $route = $this->injector->getService('route');

        if(
            //If the HTTP methods match
            $this->matchMethod($rule['method'], $request->getMethod()) &&
            //If the route matches
            ($wildcards = $this->matchRoute($rule['route'], $request->getPath())) !== false &&
            (
                //If there is no require function
                !isset($rule['require']) ||
                //Or if the require function does not return false
                (isset($rule['require']) && ($require = $this->injector->inject($rule['require'])) !== false)
            )
        )
        {
            //Pass the route wildcards to the route service
            $route->setWildcards($wildcards);
            //Pass the return of the require function ot the route service
            if(isset($rule['require']))
                $route->setRequire($require);

            //If the controller is a method
            if(isset($rule['class']))
            {
                $controller = explode('@', $rule['class']);
                $controllerName = '\\App\\Controller\\' . $controller[0];
                $controllerMethod = $controller[1];
                $this->injector->inject([new $controllerName(), $controllerMethod]);
            }
            //If the controller is a function
            else if(isset($rule['function']))
            {
                //If the location of the function was specified. EG 'filename@functioname'.
                if(($split = strpos($rule['function'], '@')) !== false)
                {
                    $funcFile = substr($rule['function'], 0, $split);
                    $funcName = substr($rule['function'], $split+1);
                }
                //If the location of the function wasn't specified
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
        if($method == 'ANY' || $method == $requestMethod)
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