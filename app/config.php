<?php

/*
	Database
	--------

	Currently only supports MYSQL 
*/

$config['database']['user'] = '';
$config['database']['pass'] = '';
$config['database']['name'] = '';
$config['database']['host'] = '';

//Default fetch format - Array or object
$config['database']['fetch'] = 'ARRAY';

/*
	Response
	--------

	The default response from the server if none was defined.
	JSON, XML or CSV
*/

$config['response']['format'] = 'JSON';