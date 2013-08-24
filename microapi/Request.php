<?php
namespace MicroAPI;

class Request extends Singleton
{
	private $method;
	private $params;
	private $path;
	private $userAgent;
	private $ipAddress;

	protected function __construct()
	{
		//Store the request method
		$this->method = $_SERVER['REQUEST_METHOD'];

		//Store the data for each request type into the $this->params variable
		switch($this->method)
		{
			case 'GET':
				$this->params = $_GET;
				//Remove the path
				unset($this->params['_path']);
				break;
			case 'POST':
				$this->params = $_POST;
				break;
			case 'PUT':
				parse_str(file_get_contents('php://input'), $this->params);
				break;
			case 'DELETE':
				parse_str(file_get_contents('php://input'), $this->params);
				break;
		}

		//Store the path
		$this->path = (isset($_GET['_path'])) ? $_GET['_path'] : '/';

		//Store the useragent
		$this->userAgent = $_SERVER['HTTP_USER_AGENT'];

		//Store the ip address, store localhost as ipv4 rather than 6
		$this->ipAddress = ($_SERVER['REMOTE_ADDR'] == '::1') ? '127.0.0.1' : $_SERVER['REMOTE_ADDR'];
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

	/**
	 *
	 */
	public function getIpAddress()
	{
		return $this->ipAddress;
	}
}