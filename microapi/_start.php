<?php

/*
    Autoloader
*/

function autoload($className)
{
    //Remove the first back slash 
    $className = ltrim($className, '\\');    

    if(strpos($className, '\\') !== false)
    {
        $classParts = explode('\\', $className, 2);

        $path;
        switch ($classParts[0])
        {
            case 'MicroAPI':
                $path = MICROAPI_PATH;
                break;
            case 'App':
                $path = APP_PATH;
                break;
        }

        $className = str_replace('\\', DIRECTORY_SEPARATOR, $classParts[1]);

        $fileName = basename($className);
        $pathName = strtolower(dirname($className));

        $seperator;
        if($pathName == '.')
        {
            $pathName = '';
            $seperator = '';
        }
        else
            $seperator = DIRECTORY_SEPARATOR;

        require $path . DIRECTORY_SEPARATOR . $pathName . $seperator . $fileName . '.php';
    }
    else
        require $className . '.php';
}

spl_autoload_register('autoload');

//Load the config
require APP_PATH . DIRECTORY_SEPARATOR . 'config.php';

//Pass config variables to the clases
\MicroAPI\Database::getInstance()->setConfig($config['database']);
\MicroAPI\Response::getInstance()->setConfig($config['response']);

//Load the routes
require APP_PATH . DIRECTORY_SEPARATOR . 'routes.php';

//If no routes were set just use an empty array
if(!isset($routes))
    $routes = [];

//Create a router with the routes
new \MicroAPI\Router($routes);