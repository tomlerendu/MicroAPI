<?php
namespace MicroAPI;

class Router
{
	public function __construct($routes)
	{
		$request = Request::getInstance();

		$foundMatch = false;

		foreach ($routes as $route)
		{
			if($request->getMethod() == $route[0] || 'ALL' == $route[0])
			{
				$matches = $this->matchPath($route[1], $request->getPathString());

				if($matches !== false)
				{
					$class = '\App\Controllers\\' . $route[2];
					$class = new $class();
					call_user_func_array(array($class, $route[3]), $matches);

					$foundMatch = true;
					break;
				}
			}
		}

		if(!$foundMatch)
		{
			//TODO implement proper 404 and default 404 behaviour in config file
			Response::getInstance()->error(404, []);
		}
	}

	/**
	 * Checks if a route matches a path
	 *
	 * @return True if the route matches the path, false if it doesn't
	 */
	public function matchPath($route, $path)
	{
		$route = explode('/', trim($route, '/'));
		$path = explode('/', trim($path, '/'));

		//If the route and path don't have the same number of sections
		if(count($route) !== count($path))
			return false;

		//For each route and path
		for($i=0; $i<count($route); $i++)
		{
			//Find out if there's a wildcard in the section
			$wildcard = strpos($route[$i], '(?)');

			//If there is and its on its own
			if($wildcard !== false && strlen($route[$i]) === 3)
			{
				$params[] = $path[$i];
			}
			//If there is and its bordered by other characters 
			else if($wildcard !== false)
			{
				if(
					substr($route[$i], 0, $wildcard) == substr($path[$i], 0, $wildcard) &&
					substr($path[$i], -strlen(substr($route[$i], $wildcard+3))) == substr($route[$i], $wildcard+3)
				)
				{
					$routeParts = explode('(?)', $route[$i]);
					$param = substr($path[$i], strlen($routeParts[0]));
					$param = substr($param, 0, -strlen($routeParts[1]));
					$params[] = $param;
				}
				else if ($route[$i] != '(?)')
					return false;
			}
			//If there's no wildcard make sure the route and path section match
			else if($route[$i] != $path[$i])
				return false;
		}

		//Return the wildcard values
		return $params;
	}
}
