<?php

/*
	Routes are defined in the following format

	HTTP method  - GET, POST, PUT, DELETE
				 - All

	URL to match - Use the wildcard (?) which represents one or more of any character

	Class        - Must map to a class in the \App\Controllers namespace
	
	Method       - Method in class to be called, if none is set index() will be called
*/

$routes = [
	['GET', 'name/(?)/', 'example', 'getName'],
];
