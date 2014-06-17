<?php
return [

    /*
        Sub folder
        ----------

        If the app is not at the root of the domain specify the sub directory it is located in.
        EG: for website.com/api/public would be /api/public.

     */

    'subDirectory' => '',

	/*
		Autoloader
	    ----------

	    Define any prefixes that should be added to the PSR-4 autoloader for external
	    libraries. The format 'prefix' => '/location/on/filesystem/' should be used.
	*/

	'autoloader' => [
		
	],

	/*
		Database
		--------

		Currently only supports MYSQL 
	*/

	'database' => [
		'user' => 'root',
		'pass' => '',
		'name' => '',
		'host' => 'localhost',

		'fetch' => PDO::FETCH_ASSOC
	],


	/*
		Response
		--------

		The default response from the server if one wasn't defined in the response
		method call.
	*/

	'response' => [
		'format' => '\MicroAPI\Response\JsonResponse',
		'cacheTime' => 0,
		'headers' => [
		]
	]

];