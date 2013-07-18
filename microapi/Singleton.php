<?php
namespace MicroAPI;

abstract class Singleton
{
	private static $instance = array();
	protected $config;

	final public static function getInstance()
	{
		$calledFrom = get_called_class();

		if(!isset(self::$instance[$calledFrom]))
			self::$instance[$calledFrom] = new $calledFrom();

		return self::$instance[$calledFrom];
	}

	public function setConfig($config)
	{
		$this->config = $config;
	}
	
	protected function __construct()
	{

	}
}