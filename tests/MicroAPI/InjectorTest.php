<?php

namespace TomLerendu\MicroAPITests;

use TomLerendu\MicroAPI\Injector\Injector;

class InjectorTest extends \MicroAPITestCase
{
    public function testAddingService()
    {
        $injector = Injector::getInstance();
        $injector->addService('test', '\TestService');
        $service = $injector->getService('test');
        $this->assertTrue(get_class($service) === 'TestService');
    }

    public function testAddingServiceWithParams()
    {
        $injector = Injector::getInstance();
        $injector->addService('test', '\TestService', ['second'=>'2', 'first'=>'1']);
        $service = $injector->getService('test');
        $this->assertTrue($service->getFirst() === '1' && $service->getSecond() == '2');
    }

    public function testSingleInstanceOfService()
    {
        $injector = Injector::getInstance();
        $injector->addService('test', '\TestService');
        $service1 = $injector->getService('test');
        $service2 = $injector->getService('test');
        $this->assertEquals($service1, $service2);
    }

    public function testObjectAsService()
    {
        $injector = Injector::getInstance();
        $testService = new TestService(1, 2);
        $injector->addService('test', $testService);
        $service = $injector->getService('test');
        $this->assertEquals($service, $testService);
    }

    public function testInjectingAnonFunction()
    {
        $injector = Injector::getInstance();
        $testService = new TestService(1, 2);
        $injector->addService('test', $testService);

        $testFunction = function(TestService $test) {
            return $test->getFirst() + $test->getSecond();
        };

        $this->assertEquals($injector->injectFunction($testFunction), 3);
    }

    public function testInjectingFunction()
    {
        $injector = Injector::getInstance();
        $testService = new TestService(1, 2);
        $injector->addService('test', $testService);
        $this->assertEquals($injector->injectFunction('testFunction'), 3);
    }

    public function testInjectingMethod()
    {
        $injector = Injector::getInstance();
        $testService = new TestService(1, 2);
        $injector->addService('test', $testService);
        $this->assertEquals($injector->injectMethod(new TestClass(), 'testMethod'), 3);
    }
}


//Test function to inject into
function testFunction(TestService $test)
{
    return $test->getFirst() + $test->getSecond();
};

//Test class to inject into
class TestClass
{
    public function testMethod(TestService $test)
    {
        return $test->getFirst() + $test->getSecond();
    }
}

//Test service
class TestService
{
    private $first;
    private $second;

    public function __construct($first, $second)
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