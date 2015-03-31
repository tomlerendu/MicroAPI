<?php

// PSR-4 autoloader

require MICROAPI_PATH . DIRECTORY_SEPARATOR . 'Autoloader.php';
$autoloader = new \TomLerendu\MicroAPI\Autoloader();
$autoloader->addNamespace('App', APP_PATH);
$autoloader->addNamespace('TomLerendu\MicroAPI', MICROAPI_PATH);

// Create the dependency injector

$injector = \TomLerendu\MicroAPI\Injector::getInstance();
$config = new \TomLerendu\MicroAPI\Config();
$injector->addService('injector', $injector);
$injector->addService('config', $config);
$injector->addService('autoloader', $autoloader);

// Run the app defined config function

$injector->inject(require(APP_PATH . DIRECTORY_SEPARATOR . 'config.php'));

// Add all required dependencies

$injector->addService('database', '\TomLerendu\MicroAPI\Database', $config->get('microapi.database'));
$injector->addService('request', '\TomLerendu\MicroAPI\Request', ['subDirectory' => $config->get('microapi.subDirectory')]);
$injector->addService('response', '\TomLerendu\MicroAPI\Response', $config->get('microapi.response'));
$injector->addService('route', '\TomLerendu\MicroAPI\Route');

// Run the app defined setup function

$injector->inject(require(APP_PATH . DIRECTORY_SEPARATOR . 'run.php'));

// Create the router and load the App routes file

$router = new \TomLerendu\MicroAPI\Router($injector);
$routeFunction = require(APP_PATH . DIRECTORY_SEPARATOR . 'routes.php');
$routeFunction($router);