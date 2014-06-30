<?php

namespace MicroAPI;

class Request
{
	private $method;
	private $params;
	private $path;
    private $pathWildcards;
	private $userAgent;

	public function __construct($subDirectory)
	{
		//Store the request method
		$this->method = $_SERVER['REQUEST_METHOD'];

		//Store the data for each request type into the $this->params variable
		switch($this->method)
		{
			case 'GET':     $this->params = $_GET;
                            break;
			case 'POST':    $this->params = $_POST;
                            break;
			case 'PUT':     parse_str(file_get_contents('php://input'), $this->params);
                            break;
			case 'DELETE':  parse_str(file_get_contents('php://input'), $this->params);
				            break;
		}

		//Store the path
        if($subDirectory === '')
		    $this->path = strtok($_SERVER['REQUEST_URI'], '?');
        else
            $this->path = strtok(explode($subDirectory, $_SERVER['REQUEST_URI'], 2)[1], '?');

		//Store the useragent
		$this->userAgent = $_SERVER['HTTP_USER_AGENT'];
	}

	/**
	 * The method the request was using. GET, POST, PUT or DELETE.
	 *
	 * @return The method the request was using.
	 */
	public function getMethod()
	{
		return $this->method;
	}

    /**
     * @param $var
     * @return bool
     */
    public function getParam($var)
	{
		return (isset($this->params[$var])) ? $this->params[$var] : false;
	}

	/**
	 *
	 */
	public function getPath()
	{
		return $this->path;
	}

	/**
	 *
	 */
	public function getPathSections()
	{
		return explode('/', $this->path);
	}

	/**
	 *
	 */
	public function getUserAgent()
	{
		return $this->userAgent;
	}
}