<?php

namespace TomLerendu\MicroAPITests\Validator;

use TomLerendu\MicroAPI\Validator\Validator;

class ValidatorTest extends \MicroAPITestCase
{
    public function testAddingRule()
    {
        $validator = new Validator();
        $validator->addRules('test', 'equals:hello');
        $this->assertEquals($validator->getRules('test'), ['equals:hello']);
    }

    public function testAddingRules()
    {
        $validator = new Validator();

        $validator->addRules('test', ['equals:hello', 'equals:world']);
        $this->assertEquals($validator->getRules('test'), ['equals:hello', 'equals:world']);

        $validator->addRules('test', ['equals:foo']);
        $this->assertEquals($validator->getRules('test'), ['equals:hello', 'equals:world', 'equals:foo']);
    }

    public function testResetRules()
    {
        $validator = new Validator();
        $validator->addRules('test', 'equals:hello');
        $this->assertEquals($validator->getRules('test'), ['equals:hello']);
        $validator->resetRules('test');
        $this->assertEquals($validator->getRules('test'), []);
    }

    public function testValidating()
    {
        $validator = new Validator(['test' => 'abc']);
        $validator->addRules('test', 'equals:abc');
        $this->assertTrue($validator->isValid());

        $validator = new Validator(['test' => 'abc']);
        $validator->addRules('test', 'equals:def');
        $this->assertFalse($validator->isValid());
    }
}