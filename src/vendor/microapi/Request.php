<?php
namespace MicroAPI;

class Request extends Singleton
{
	private $method;
	private $params;
	private $path;
	private $userAgent;

	protected function __construct()
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
		$this->path = $_SERVER['REQUEST_URI'];

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
	 * The parameters sent with the request. GET, POST, PUT or DELETE.
	 *
	 * @return The parameters send with the request.
	 */
	public function getParam($var)
	{
		return (isset($this->data[$var])) ? $this->data[$var] : false;
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