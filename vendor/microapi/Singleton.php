<?php
namespace MicroAPI;

abstract class Singleton
{
	private static $instance = array();
	protected $config;

	/**
	 * Returns the only instance of the object and creates one if it doesn't already exist
	 * 
	 * @return The only instance of the object
	 */
	final public static function getInstance()
	{
		$calledFrom = get_called_class();

		if(!isset(self::$instance[$calledFrom]))
			self::$instance[$calledFrom] = new $calledFrom();

		return self::$instance[$calledFrom];
	}

	/**
	 * Passes configuration from the config file into a class
	 */
	public function setConfig($config)
	{
		$this->config = $config;
	}
	
	/**
	 *
	 */
	protected function __construct()
	{

	}
}