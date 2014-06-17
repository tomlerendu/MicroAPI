<?php

use MicroAPI\Injector;

class InjectorTest extends PHPUnit_Framework_TestCase
{
    public function testAddingDependency()
    {
        $injector = new Injector();
        $injector->addDependency('test', 'TestService');
        $service = $injector->getService('test');
        $this->assertTrue(get_class($service) === 'TestService');
    }

    public function testAddingDependencyWithParams()
    {
        $injector = new Injector();
        $injector->addDependency('test', 'TestService', ['second'=>'2', 'first'=>'1']);
        $service = $injector->getService('test');
        $this->assertTrue($service->getFirst() === '1' && $service->getSecond() == '2');
    }

    public function testSingleInstanceOfService()
    {
        $injector = new Injector();
        $injector->addDependency('test', 'TestService');
        $service1 = $injector->getService('test');
        $service2 = $injector->getService('test');
        $this->assertEquals($service1, $service2);
    }

    public function testObjectAsService()
    {
        $injector = new Injector();
        $testService = new TestService();
        $injector->addDependency('test', $testService);
        $service = $injector->getService('test');
        $this->assertEquals($service, $testService);
    }
}

class TestService
{
    private $first;
    private $second;

    public function __construct($first='foo', $second='bar')
    {
        $this->first = $first;
        $this->second = $second;
    }

    public function getFirst()
    {
        return $this->first;
    }

    public function getSecond()
    {
        return $this->second;
    }
}