MicroAPI
========

MicroAPI is an extremely lightweight PHP framework for creating REST APIs.

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

- App
  - Controller
  - Models
  - Services
- MicroAPI
- public

The directory structure can be changed by editing the constants `MICROAPI_PATH` and `APP_PATH` in `public/const.php` then moving the directories to their corresponding locations. It is advisable to keep application code out of the public directory.

The App directory is where you write your application.

The MicroAPI directory contains the framework. If there are multiple websites using MicroAPI on the same system they can be configured to share one copy of it.

The public directory is where the requests enter the framework. 

### Namespaces and autoloading

MicroAPI follows the PSR-4 standard for autoloading classes.


### Request response cycle

Within the App directory there are three files
1. App/config.php function is executed
2. App/run.php function is executed
3. App/routes.php function is executed - If a route is matched its controller is executed

Config
------

Configuration variables for the application can be defined in App/config.php. By default there are options for the database and response already listed.
It's recommended not to define values that have a key starting with `microapi.`.


Routing
-------

Routes for your application are defined in the `app/routes.php` file. The router will initialise the controller for the first match, once a match has been found the rest of the routes will not be checked.

By default the `app/routes.php` has two sample routes showing how to setup a function and a class.

```php
return function($router)
{

    $router->get([
        'route' => '/name/(name)',
        'class' => 'Example@getName',
        'require' => function() {
            return 1 + 1 == 2;
        }
    ]);

    $router->post([
        'route' => '/name/(name)',
        'function' => 'functionExample@postName',
    ]);

};
```
#### Methods

The router has the methods `get`, `post`, `put`, `delete` which match the corresponding HTTP methods and `any` which will match any HTTP method.

#### Routes and wildcards


#### Extra requirements

The require function allows for any additional requirements to be specified before the route is matched. If anything other than true is returned from the function the route will not be matched.

```php
$router->get([
    'route' => '/user/(id)',
    'function' => 'user@details',
    'require' => function($database, $request) {
        $user = $database->select('SELECT id FROM User WHERE id = ?', $request->getPathWildcard('id'));
        return count($user) === 1;
    }
]);
```

Controllers
-----------

Controllers are dependency injected, they can be either a function or a method on an object.

```php
function myController($request, $response, $database)
{
    $id = $request->getParam('id');
    $results = $database->select('SELECT * FROM Table WHERE id = ?', $id);

    $response->make($results);
}
```

Services
--------

A service is an object that can be injected at various places in the application, there will only one instance of each service per request. By default MicroAPI comes with six services.

### Request

The request object is a representation of the request the user made.

### Responses

Responses are "replies" to a clients request, only one response should be sent per request.

### Database

The database service is built on top of PDO, it has four convenience functions to make accessing the database easier.
For more specific tasks you can access the database directly by calling `getConnection()` which returns the PDO object.

### Injector

### Autoloader

### Config

### Creating a custom service



Models
------

Known issues
------------
