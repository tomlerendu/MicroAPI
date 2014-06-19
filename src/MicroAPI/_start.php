<?php

// PSR-4 autoloader

require MICROAPI_PATH . DIRECTORY_SEPARATOR . 'Autoloader.php';
$autoloader = new \MicroAPI\Autoloader();
$autoloader->addNamespace('App', APP_PATH);
$autoloader->addNamespace('MicroAPI', MICROAPI_PATH);

// Create the dependency injector

$injector = new \MicroAPI\Injector();
$config = new \MicroAPI\Config();
$injector->addDependency('injector', $injector);
$injector->addDependency('config', $config);

// Run the app defined config function

$injector->inject(require(APP_PATH . DIRECTORY_SEPARATOR . 'config.php'));

// Add all required dependencies

$injector->addDependency('database', '\MicroAPI\Database', $config->get('microapi.database'));
$injector->addDependency('request', '\MicroAPI\Request', ['subDirectory' => $config->get('microapi.subDirectory')]);
$injector->addDependency('response', '\MicroAPI\Response', $config->get('microapi.response'));
$injector->addDependency('router', '\MicroAPI\Router', ['injector' => $injector]);

// Run the app defined setup function

$injector->inject(require(APP_PATH . DIRECTORY_SEPARATOR . 'run.php'));

// Create the router and load the App routes file

$injector->inject(require(APP_PATH . DIRECTORY_SEPARATOR . 'routes.php'));