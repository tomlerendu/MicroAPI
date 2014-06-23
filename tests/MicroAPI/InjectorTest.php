<?php

use MicroAPI\Injector;

class InjectorTest extends PHPUnit_Framework_TestCase
{
    public function testAddingService()
    {
        $injector = new Injector();
        $injector->addService('test', 'TestService');
        $service = $injector->getService('test');
        $this->assertTrue(get_class($service) === 'TestService');
    }

    public function testAddingServiceWithParams()
    {
        $injector = new Injector();
        $injector->addService('test', 'TestService', ['second'=>'2', 'first'=>'1']);
        $service = $injector->getService('test');
        $this->assertTrue($service->getFirst() === '1' && $service->getSecond() == '2');
    }

    public function testSingleInstanceOfService()
    {
        $injector = new Injector();
        $injector->addService('test', 'TestService');
        $service1 = $injector->getService('test');
        $service2 = $injector->getService('test');
        $this->assertEquals($service1, $service2);
    }

    public function testObjectAsService()
    {
        $injector = new Injector();
        $testService = new TestService();
        $injector->addService('test', $testService);
        $service = $injector->getService('test');
        $this->assertEquals($service, $testService);
    }

    public function testInjectingAnonFunction()
    {
        $injector = new Injector();
        $testService = new TestService(1, 2);
        $injector->addService('test', $testService);

        $testFunction = function($test)
        {
            return $test->getFirst() + $test->getSecond();
        };

        $this->assertEquals($injector->inject($testFunction), 3);
    }

    public function testInjectingFunction()
    {
        $injector = new Injector();
        $testService = new TestService(1, 2);
        $injector->addService('test', $testService);
        $this->assertEquals($injector->inject('testFunction'), 3);
    }

    public function testInjectingMethod()
    {
        $injector = new Injector();
        $testService = new TestService(1, 2);
        $injector->addService('test', $testService);
        $this->assertEquals($injector->inject([new TestClass(), 'testMethod']), 3);
    }
}


//Test function to inject into
function testFunction($test)
{
    return $test->getFirst() + $test->getSecond();
};

//Test class to inject into
class TestClass
{
    public function testMethod($test)
    {
        return $test->getFirst() + $test->getSecond();
    }
}

//Test service
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