<?php
return [
	
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

		'fetch' => 'ASSOC'
	],


	/*
		Response
		--------

		The default response from the server if one wasn't defined in the response
		method call.
	*/

	'response' => [
		'format' => '\MicroAPI\Response\JsonResponse'
	]

];