<?php
return [

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

		The default response from the server if none was defined.
		JSON OR XML
	*/

	'response' => [
		'format' => '\MicroAPI\Response\JsonResponse'
	]

];