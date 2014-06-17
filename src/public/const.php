<?php

/*
	Path to the directory containing the App and MicroAPI directories.

	The only things dependant on MAIN_PATH are APP_PATH and MICROAPI_PATH
	so it can be removed if you decide to go for an alternate directory
	structure.
*/
define('MAIN_PATH', realpath(dirname(dirname(__FILE__))));

/*
	The path to the App directory
*/
define('APP_PATH', MAIN_PATH . DIRECTORY_SEPARATOR . 'App');

/*
	The path to the vendor directory
*/
define('MICROAPI_PATH', MAIN_PATH . DIRECTORY_SEPARATOR . 'MicroAPI');

