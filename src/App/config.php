<?php

return function($config, $injector)
{

    //Sub directory - If the public directory is not at the domain root set the sub directory it is in here
    $config->set('microapi.subDirectory', '/microapi/src/public');

    //Database
    $config->set('microapi.database.user', 'root');
    $config->set('microapi.database.pass', '');
    $config->set('microapi.database.name', '');
    $config->set('microapi.database.host', 'localhost');
    $config->set('microapi.database.fetch', PDO::FETCH_ASSOC);

    //Response - The default values for a response if one wasn't defined in the call Response::make()
    $config->set('microapi.response.format', '\MicroAPI\Response\JsonResponse');
    $config->set('microapi.response.cacheTime', 0);
    $config->set('microapi.response.headers', []);

};
