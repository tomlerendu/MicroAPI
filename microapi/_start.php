<?php

/*
    PSR-4 autoloader
*/

require MICROAPI_PATH . DIRECTORY_SEPARATOR . 'Autoloader.php';
$autoloader = new \MicroAPI\Autoloader();
$autoloader->addNamespace('App', APP_PATH);
$autoloader->addNamespace('MicroAPI', MICROAPI_PATH);

/*
    Load the config file
*/

$config = require APP_PATH . DIRECTORY_SEPARATOR . 'config.php';
\MicroAPI\Database::getInstance()->setConfig($config['database']);
\MicroAPI\Response::getInstance()->setConfig($config['response']);

/*
	Add namespaces to the autoloader
*/

$autoloader->addNamespaces($config['autoloader']);

/*
    Create the router passing the routes to the constructor
*/

$routes = require APP_PATH . DIRECTORY_SEPARATOR . 'routes.php';
new \MicroAPI\Router($routes);