<?php

// PSR-4 autoloader

require MICROAPI_PATH . DIRECTORY_SEPARATOR . 'Autoloader.php';
$autoloader = new \MicroAPI\Autoloader();
$autoloader->addNamespace('App', APP_PATH);
$autoloader->addNamespace('MicroAPI', MICROAPI_PATH);

// Create the dependency injector

$injector = \MicroAPI\Injector\Injector::getInstance();
$config = new \MicroAPI\Config();
$injector->addService($injector);
$injector->addService($config);
$injector->addService($autoloader);

// Run the app defined config function

$injector->injectFunction(require(APP_PATH . DIRECTORY_SEPARATOR . 'config.php'));

// Add all required dependencies

$injector->addService(MicroAPI\Database::class, $config->get('microapi.database'));
$injector->addService(MicroAPI\Request::class, ['subDirectory' => $config->get('microapi.subDirectory')]);
$injector->addService(MicroAPI\Response::class, $config->get('microapi.response'));
$injector->addService(MicroAPI\Route::class);

// Run the app defined setup function

$injector->injectFunction(require(APP_PATH . DIRECTORY_SEPARATOR . 'run.php'));

// Create the router and load the App routes file

$router = new \MicroAPI\Router($injector);
$routeFunction = require(APP_PATH . DIRECTORY_SEPARATOR . 'routes.php');
$routeFunction($router);