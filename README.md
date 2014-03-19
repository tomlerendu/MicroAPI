1.0 documentation draft. 
This project isn't finished and parts won't work as the documentation specifies. 

MicroAPI
========

MicroAPI is an extremely lightweight PHP framework for creating API's. 

Requirements
------------

- Apache 2.0+
  - mod_rewrite enabled 
- PHP 5.4+

Downloading and Installing
--------------------------


Structure
---------

### File system

A MicroAPI application by default has the following structure. 

- app
  - controllers
  - models
- microapi
- public

The directory structure can be changed by editing the constants `MICROAPI_PATH` and `APP_PATH` in `public/index.php` then moving the directories to their corresponding locations. It is advisable to keep code out of the public directory. 

All application code should be stored in the app directory. 

The microapi directory contains the framework. If there are multiple websites using MicroAPI on the same system they can share one copy of it. 

The public directory is where the requests enter the framework. 

### Namespaces and autoloading

MicroAPI follows the PSR-4 standard for autoloading classes.

Config
------

### Autoloader



### Database

Currently MicroAPI only supports MySQL although this will change in the future.

### Response



Routing
-------

Routes for your application are defined in the /app/routes.php file.



Controllers
-----------

There is one controller per request. For convenience controllers have references to the request, response and database objects.

<table>
	<tr>
		<th>Class</th>
		<th>Reference</th>
	</tr>
	<tr>
		<td>Request</td>
		<td>$this->request</td>
	</tr>
	<tr>
		<td>Response</td>
		<td>$this->response</td>
	</tr>
	<tr>
		<td>Database</td>
		<td>$this->database</td>
	</tr>
</table> 

Requests
--------

The request object is a representation of the request the user made.


Responses
---------

Responses are "replies" to a clients request, only one response should be sent per request.

### Responding
`$this->response->make($array, $options)`

### Responding with an error
`$this->response->error(404, $array, $options)`

### Options

Both the `make` and `error` methods take an optional `$options` parameter.

Models
------

Database
--------

The database layer is built on top of PDO, it has convinience functions to make accessing the database easier.

### select
### insert
### update
### delete
### query


### Accessing PDO directly

For more specific tasks you can access the database directly by calling `getConnection()` which returns the PDO object.

Known issues
------------

- A controller and method combination cannot be added to the routes file more than once
- Models implementation is not finished