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

A MicroAPI application by default has the following structure.

- app
  - controllers
  - models
  - helpers
- microapi
- public

The app directory is where you write your application. It holds config, routes, controllers, models and helpers.

The microapi directory contains the framework.

The public directory is where the requests are made.

Config
------

### Database

`$config['database']['user']` - The username to connect to the database<br />
`$config['database']['pass']` - The password to connect to the database<br />
`$config['database']['host']` - The address of the MySQL database host<br />
`$config['database']['name']` - The name of the database you are connecting to<br />

If you're not planning on using a database then leave these blank.

### Response

`$config['response']['format']`

Routing
-------

Routes for your application are defined in the /app/routes.php file.

Controllers
-----------

Requests
--------

Responses
---------

Responses are "replys" to a clients request, only one response should be sent per request.

### Responding
`$this->response->make($array, $options)`

### Responding with an error
`$this->response->error(404, $array, $options)`

### Options

Both the `make` and `error` methods take an optional `$options` parameter.

Models
------

Helpers
-------

Database
--------

The database layer is built on top of PDO, it has convinience functions to make accessing the database easier.

### select
### insert
### update
### delete
### query


### Accessing PDO directly

For more specific tasks you can access the PDO object directly by calling `$this->database->getConnection()`.

Known issues
------------
