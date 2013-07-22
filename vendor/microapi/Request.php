<?php
namespace MicroAPI;

class Request extends Singleton
{
	private $method;
	private $params;
	private $path;
	private $pathString;

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
		array_map('htmlspecialchars', $this->params);

		//Store the path
		$this->pathString = (isset($_GET['_path'])) ? $_GET['_path'] : '/';
		$this->path = explode('/', $this->pathString);
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
	 * Provides a way of accessing the request parameters 
	 *
	 */
	public function getParam($var)
	{
		return (isset($this->data[$var])) ? $this->data[$var] : false;
	}

	public function getPath()
	{
		return $this->path;
	}

	public function getPathString()
	{
		return $this->pathString;
	}
}