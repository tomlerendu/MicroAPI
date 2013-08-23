<?php
namespace MicroAPI;

class Router
{
	public function __construct($routes)
	{
		$request = Request::getInstance();
		$match = false;

		//For each controller
		foreach($routes as $controller => $rules)
		{
			//If a single path was given
			if(!is_array($rules))
				$rules = ['path'=>$rules];

			//If short hand [path, method] was given
			//Check if the array is not an assoc array
			if(array_key_exists(0, $rules) && array_key_exists(1, $rules))
				$rules = ['path'=>$rules[0], 'method'=>$rules[1]];

			$match = $this->processRules($rules, $request);

			if($match !== false)
			{
				//Create the controller
				$controller = explode('@', $controller);
				$controllerName = '\App\Controllers\\' . $controller[0];
				$controllerMethod = $controller[1];
				$controller = new $controllerName();
				call_user_func_array([$controller,$controllerMethod], $match);
				//Break the loop as a suitable controller was found
				break;
			}
		}

		//If no matches were found
		if($match === false)
			Response::getInstance()->error(404);
	}

	public function processRules($rules, $request)
	{
		//The wildcards to be returned
		$wildcards = [];

		foreach($rules as $ruleType => $ruleValue)
		{
			$rulePassed;

			switch($ruleType) 
			{
				case 'path':
					$rulePassed = $this->matchPath($ruleValue, $request->getPath());
					break;
				case 'method':
					$rulePassed = $this->matchMethod($ruleValue, $request->getMethod());
					break;
			}

			//If the rule failed
			if($rulePassed === false)
				return false;
			//If the rule passed
			else
			{
				//For each new wildcard found
				foreach($rulePassed as $item)
					//Add it to the wildcards array
					$wildcards[] = $item;
			}
		}

		return $wildcards;
	}

	/**
	 *
	 *
	 */
	private function matchMethod($method, $requestMethod)
	{
		if(strtolower($method) == strtolower($requestMethod))
			return [];

		return false;
	}

	/**
	 * Checks if a predefined path matches the requested path
	 *
	 * @param $path
	 * @param $requestPath
	 *
	 * @return The values of the wildcards if the path is matched, false if it doesn't
	 */
	private function matchPath($path, $requestPath)
	{
		$path = explode('/', trim($path, '/'));
		$requestPath = explode('/', trim($requestPath, '/'));
		
		$params = [];

		//If the route and path don't have the same number of sections
		if(count($path) !== count($requestPath))
			return false;

		//For each route and path
		for($i=0; $i<count($path); $i++)
		{
			//Find out if there's a wildcard in the section
			$wildcard = strpos($path[$i], '(?)');

			//If there is and its on its own
			if($wildcard !== false && strlen($path[$i]) === 3)
			{
				$params[] = $requestPath[$i];
			}
			//If there is and its bordered by other characters 
			else if($wildcard !== false)
			{
				if(
					substr($path[$i], 0, $wildcard) == substr($requestPath[$i], 0, $wildcard) &&
					substr($requestPath[$i], -strlen(substr($path[$i], $wildcard+3))) == substr($path[$i], $wildcard+3)
				)
				{
					$routeParts = explode('(?)', $path[$i]);
					$param = substr($requestPath[$i], strlen($routeParts[0]));
					$param = substr($param, 0, -strlen($routeParts[1]));
					$params[] = $param;
				}
				else if ($path[$i] != '(?)')
					return false;
			}
			//If there's no wildcard make sure the route and path section match
			else if($path[$i] != $requestPath[$i])
				return false;
		}

		//Return the wildcard values
		return $params;
	}
}