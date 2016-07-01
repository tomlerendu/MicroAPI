<?php

namespace MicroAPITests;

use \MicroAPI\Injector\Injector;

class InjectorTest extends \MicroAPITestCase
{
    public function testAddingService()
    {
        $injector = Injector::getInstance();
        $injector->addService(TestService::class);
        $service = $injector->getService(TestService::class);
        $this->assertTrue($service instanceof TestService);
    }

    public function testAddingServiceWithParams()
    {
        $injector = Injector::getInstance();
        $injector->addService(TestService::class, ['foo', 'bar']);
        $service = $injector->getService(TestService::class);
        $this->assertEquals($service->getFirst(), 'foo');
        $this->assertEquals($service->getSecond(), 'bar');
    }

    public function testSingleInstanceOfService()
    {
        $injector = Injector::getInstance();
        $injector->addService(TestService::class);
        $service1 = $injector->getService(TestService::class);
        $service2 = $injector->getService(TestService::class);
        $this->assertEquals($service1, $service2);
    }

    public function testObjectAsService()
    {
        $injector = Injector::getInstance();
        $testService = new TestService();
        $injector->addService($testService);
        $service = $injector->getService(TestService::class);
        $this->assertEquals($service, $testService);
    }

    public function testInjectingAnonFunction()
    {
        $injector = Injector::getInstance();
        $testService = new TestService(1, 2);
        $injector->addService($testService);

        $testFunction = function(TestService $test) {
            return $test->getFirst() + $test->getSecond();
        };

        $this->assertEquals($injector->injectFunction($testFunction), 3);
    }

    public function testInjectingFunction()
    {
        $injector = Injector::getInstance();
        $testService = new TestService(1, 2);
        $injector->addService($testService);
        $this->assertEquals($injector->injectFunction('\TomLerendu\MicroAPITests\testFunction'), 3);
    }

    public function testInjectingMethod()
    {
        $injector = Injector::getInstance();
        $testService = new TestService(1, 2);
        $injector->addService($testService);
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

    public function __construct($first = 1, $second = 2)
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