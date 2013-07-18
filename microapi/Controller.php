<?php
namespace MicroAPI;

class Controller
{
	//The clients request
	protected $request;
	//The applications response
	protected $response;
	//Database layer
	protected $database;

	/**
	 * Constructor. Create references to commonly used objects
	 */
	public function __construct()
	{
		$this->request = Request::getInstance();
		$this->response = Response::getInstance();
		$this->database = Database::getInstance();

	}

	
}