<?php

// PSR-4 autoloader

require MICROAPI_PATH . DIRECTORY_SEPARATOR . 'Autoloader.php';
$autoloader = new \MicroAPI\Autoloader();
$autoloader->addNamespace('App', APP_PATH);
$autoloader->addNamespace('MicroAPI', MICROAPI_PATH);

// Load the config file

$config = require APP_PATH . DIRECTORY_SEPARATOR . 'config.php';

// Add namespaces to the autoloader

$autoloader->addNamespaces($config['autoloader']);

// Create the dependency injector

$injector = new \MicroAPI\Injector();
$injector->addDependency('injector', $injector);
$injector->addDependency('database', '\MicroAPI\Database', $config['database']);
$injector->addDependency('request', '\MicroAPI\Request', $config['subdirectory']);
$injector->addDependency('response', '\MicroAPI\Response', $config['response']);

// Create the router and load the App routes file

$router = new \MicroAPI\Router($injector);
require APP_PATH . DIRECTORY_SEPARATOR . 'routes.php';