<?php

namespace MicroAPI;

class Request
{
    private $method;
    private $params;
    private $path;
    private $userAgent;

    public function __construct($subDirectory='')
    {
        //Store the request method
        $this->method = $_SERVER['REQUEST_METHOD'];

        //Store the get and post params
        //$this->params['GET'] = $_GET;
        $this->params['POST'] = $_POST;

        //Parse the PUT or DELETE params if required
        switch($this->method) {
            case 'PUT':
                parse_str(file_get_contents('php://input'), $this->params['PUT']);
                break;
            case 'DELETE':
                parse_str(file_get_contents('php://input'), $this->params['DELETE']);
                break;
        }

        //Store the path
        if($subDirectory === '')
            $this->path = strtok($_SERVER['REQUEST_URI'], '?');
        else
            $this->path = strtok(explode($subDirectory, $_SERVER['REQUEST_URI'], 2)[1], '?');

        //Store the user agent
        $this->userAgent = $_SERVER['HTTP_USER_AGENT'];
    }

	/**
	 * The HTTP method the request used. GET, POST, PUT or DELETE.
	 *
	 * @return - The method the request was using.
	 */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Get a parameter from the request
     *
     * @param $name - The name of the parameter
     * @param $method string - The HTTP method the param was sent over
     * @return mixed - The parameter if it exists, false if not
     */
    public function getParam($name, $method=null)
    {
        if ($method !== null)
            $method = strtoupper($method);

        //If a valid method was specified
        if ($method !== null && $method === ('GET' || 'POST' || 'PUT' || 'DELETE'))
            return $this->params[$method][$name];

        //If the method is PUT or DELETE and a PUT or DELETE index exists with that name
        elseif (
            ($this->method === 'PUT' && isset($this->params['PUT'][$name])) ||
            ($this->method === 'DELETE' && isset($this->params['DELETE'][$name]))
        )
            return $this->params[$this->method][$name];

        //If a post item exists with that name and GET is not set
        elseif (isset($this->params['POST'][$name]) && $method !== 'GET')
            return $this->params['POST'][$name];

        //If a get item exists with that name
        elseif (isset($this->params['GET'][$name]))
            return $this->params['GET'][$name];

        //If the item doesn't exist
        else
            return false;
    }

    /**
     * Returns the path the user requested
     *
     * @return string - The path the user requested
     */
    public function getPath()
    {
        return $this->path;
    }


    /**
     * Returns the path the user requested as an array
     *
     * @return array - The sections of the path
     */
    public function getPathSections()
    {
        $sections = explode('/', $this->path);

        if ($sections[0] === '')
            array_shift($sections);

        if ($sections[count($sections)-1] === '')
            array_pop($sections);

        return $sections;
    }

    /**
     * Returns the user agent of the browser making the request
     *
     * @return string - The user agent
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }
}