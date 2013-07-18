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
			Response::getInstance()->make(["error"=>"404"]);
		}
	}

	/**
	 * Checks if a route matches a path
	 *
	 * @return True if the route matches the path, false if it doesn't
	 */
	public function matchPath($route, $path)
	{
		/*
		 TODO: Be able to match /SomeRandomCharacters(?)SomeMoreRandomCharacters/ as well as /(?)/
		*/

		$route = explode('/', trim($route, '/'));
		$path = explode('/', trim($path, '/'));

		//If the route and path don't have the same number of sections
		if(count($route) !== count($path))
			return false;

		for($i=0; $i<count($route); $i++)
		{
			//Find out if there's a wildcard in the sectio
			$wildcard = strpos($route[$i], '(?)');

			//If there's is
			if($wildcard !== false)
			{
				if($route[$i] != '(?)')
					return false;

				$params[] = $path[$i];
			}
			//If there's no wildcard make sure the route and path section match
			else if($route[$i] != $path[$i])
				return false;
		}

		//Return the wildcard values
		return $params;
	}
}
