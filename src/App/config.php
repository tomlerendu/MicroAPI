<?php

return function($config, $injector)
{
    /*
        Sub directory
        -------------

        If the app is not at the root of the domain specify the sub directory it is located in.
        EG: for website.com/api/public would be /api/public.
        For root leave empty.
     */
    $config->set('microapi.subDirectory', '/microapi/src/public');

    /*
		Database
		--------

		Currently only supports MYSQL
	*/
    $config->set('microapi.database.user', 'root');
    $config->set('microapi.database.pass', '');
    $config->set('microapi.database.name', '');
    $config->set('microapi.database.host', 'localhost');
    $config->set('microapi.database.fetch', PDO::FETCH_ASSOC);

    /*
		Response
		--------

		The default response from the server if one wasn't defined in the response
		method call.
	*/
    $config->set('microapi.response.format', '\MicroAPI\Response\JsonResponse');
    $config->set('microapi.response.cacheTime', '0');
    $config->set('microapi.response.headers', []);
};
