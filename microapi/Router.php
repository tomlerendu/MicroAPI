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
			$rulePassed = false;

			switch($ruleType) 
			{
				case 'path':
					$rulePassed = $this->matchWildcards($ruleValue, $request->getPath(), '/');
					break;
				case 'method':
					$rulePassed = $this->matchMethod($ruleValue, $request->getMethod());
					break;
				case 'ip':
					$rulePassed = $this->matchIp($ruleValue, $request->getIpAddress());
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
	 * @param $ip
	 * @param $requestIp
	 * 
	 * @return The values of the wildcards if the method is matched, false if it doesn't
	 */
	private function matchIp($ip, $requestIp)
	{
		$ipV4 = (stripos($ip, ':') === false);
		$requestIpV4 = (stripos($requestIp, ':') === false);

		//If one is ip v4 and one is v6
		if($ipV4 !== $requestIpV4)
			return false;
		//If they are both ip v4
		else if($ipV4)
			return $this->matchWildcards($ip, $requestIp, '.');
		//If they are both ip v6
		else
		{
			$ip = inet_ntop(inet_pton($ip));
			$requestIp = inet_ntop(inet_pton($requestIp));
			return $this->matchWildcards($ip, $requestIp, ':');
		}
	}

	/**
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
	private function matchWildcards($pattern, $subject, $delimiter = false)
	{
		if($delimiter !== false)
		{
			$pattern = explode($delimiter, trim($pattern, $delimiter));
			$subject = explode($delimiter, trim($subject, $delimiter));
		}

		$params = [];

		//If the route and path don't have the same number of sections
		if(count($pattern) !== count($subject))
			return false;

		//For each route and path
		for($i=0; $i<count($pattern); $i++)
		{
			//Find out if there's a wildcard in the section
			$wildcard = strpos($pattern[$i], '(?)');

			//If there is and its on its own
			if($wildcard !== false && strlen($pattern[$i]) === 3)
			{
				$params[] = $subject[$i];
			}
			//If there is and its bordered by other characters 
			else if($wildcard !== false)
			{
				if(
					substr($pattern[$i], 0, $wildcard) == substr($subject[$i], 0, $wildcard) &&
					substr($subject[$i], -strlen(substr($pattern[$i], $wildcard+3))) == substr($pattern[$i], $wildcard+3)
				)
				{
					$routeParts = explode('(?)', $pattern[$i]);
					$param = substr($subject[$i], strlen($routeParts[0]));
					$param = substr($param, 0, -strlen($routeParts[1]));
					$params[] = $param;
				}
				else if ($pattern[$i] != '(?)')
					return false;
			}
			//If there's no wildcard make sure the route and path section match
			else if($pattern[$i] != $subject[$i])
				return false;
		}

		//Return the wildcard values
		return $params;
	}
}