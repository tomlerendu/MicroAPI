<?php

use MicroAPI\Route;

class RouteTest extends PHPUnit_Framework_TestCase
{
    public function testGettingWildcards()
    {
        $wildcards = ['foo'=>'hello', 'bar'=>'world'];
        $route = new Route();
        $route->setWildcards($wildcards);

        $this->assertEquals($route->getWildcards(), $wildcards);
    }

    public function testGettingWildcard()
    {
        $wildcards = ['foo'=>'hello', 'bar'=>'world'];
        $route = new Route();
        $route->setWildcards($wildcards);

        $this->assertTrue($route->getWildcard('foo') === 'hello' && $route->getWildcard('bar') === 'world');
    }

    public function testGettingInvalidWildcard()
    {
        $wildcards = ['foo'=>'hello', 'bar'=>'world'];
        $route = new Route();
        $route->setWildcards($wildcards);

        $this->assertEquals($route->getWildcard('hello'), null);
    }

    public function testGettingRequire()
    {
        $object = new stdClass();

        $route = new Route();
        $route->setRequire($object);

        $this->assertEquals($route->getRequire(), $object);
    }
}
 