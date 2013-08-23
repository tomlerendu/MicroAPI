<?php

/*
    PSR-0 Autoloader
    A modified version of https://gist.github.com/Thinkscape/1234504
*/

function autoload($className)
{
    $className = ltrim($className, '\\');
    $namespace = '';
    $fileName;

    if(substr($className, 0, 4) == 'App\\')
    {
        $fileName = APP_PATH . DIRECTORY_SEPARATOR;
        $className = explode('\\', $className, 2)[1];
    }
    else
        $fileName = VENDOR_PATH . DIRECTORY_SEPARATOR;

    if ($lastNsPos = strripos($className, '\\'))
    {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName  .= str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }

    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

    require $fileName;
}

spl_autoload_register('autoload');

/*
    Load the config file
*/

$config = require APP_PATH . DIRECTORY_SEPARATOR . 'config.php';

\MicroAPI\Database::getInstance()->setConfig($config['database']);
\MicroAPI\Response::getInstance()->setConfig($config['response']);

/*
    Load the map
*/

$routes = require APP_PATH . DIRECTORY_SEPARATOR . 'routes.php';

/*
    Create the router
*/

new \MicroAPI\Router($routes);