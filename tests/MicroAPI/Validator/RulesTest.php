<?php

namespace TomLerendu\MicroAPITests\Validator;

use TomLerendu\MicroAPI\Validator\Validator;

class RulesTest extends \MicroAPITestCase
{
    public function testValidateIn()
    {
        $validator = new Validator(['foo' => 'bar']);
        $validator->addRules('foo', 'in:test,bar');
        $this->assertTrue($validator->isValid());

        $validator = new Validator(['foo' => 'bar']);
        $validator->addRules('foo', 'in:test,other');
        $this->assertFalse($validator->isValid());
    }

    public function testValidateNotIn()
    {
        $validator = new Validator(['foo' => 'bar']);
        $validator->addRules('foo', 'notIn:test,bar');
        $this->assertFalse($validator->isValid());

        $validator = new Validator(['foo' => 'bar']);
        $validator->addRules('foo', 'notIn:test,other');
        $this->assertTrue($validator->isValid());
    }

    public function testValidateEquals()
    {
        $validator = new Validator(['foo' => 'bar']);
        $validator->addRules('foo', 'equals:bar');
        $this->assertTrue($validator->isValid());

        $validator = new Validator(['foo' => 'bar']);
        $validator->addRules('foo', 'equals:other');
        $this->assertFalse($validator->isValid());
    }

    public function testValidateRange()
    {
        $validator = new Validator(['foo' => 13]);
        $validator->addRules('foo', 'range:10,20');
        $this->assertTrue($validator->isValid());

        $validator = new Validator(['foo' => 20]);
        $validator->addRules('foo', 'range:10,20');
        $this->assertTrue($validator->isValid());

        $validator = new Validator(['foo' => 9]);
        $validator->addRules('foo', 'range:10,20');
        $this->assertFalse($validator->isValid());

        $validator = new Validator(['foo' => -2]);
        $validator->addRules('foo', 'range:-10,20');
        $this->assertTrue($validator->isValid());

        $validator = new Validator(['foo' => -2]);
        $validator->addRules('foo', 'range:-10,20');
        $this->assertTrue($validator->isValid());

        $validator = new Validator(['foo' => 'a']);
        $validator->addRules('foo', 'range:10,20');
        $this->assertFalse($validator->isValid());
    }

    public function testValidateLength()
    {
        $validator = new Validator(['foo' => 'abc']);
        $validator->addRules('foo', 'length:3,10');
        $this->assertTrue($validator->isValid());

        $validator = new Validator(['foo' => 'abc']);
        $validator->addRules('foo', 'length:4,10');
        $this->assertFalse($validator->isValid());

        $validator = new Validator(['foo' => 'abcdefghi']);
        $validator->addRules('foo', 'length:3,10');
        $this->assertTrue($validator->isValid());

        $validator = new Validator(['foo' => '']);
        $validator->addRules('foo', 'length:3,10');
        $this->assertFalse($validator->isValid());

        $validator = new Validator(['foo' => null]);
        $validator->addRules('foo', 'length:3,10');
        $this->assertFalse($validator->isValid());

        $validator = new Validator(['foo' => 'abc']);
        $validator->addRules('foo', 'length:3');
        $this->assertTrue($validator->isValid());

        $validator = new Validator(['foo' => 'abcd']);
        $validator->addRules('foo', 'length:3');
        $this->assertFalse($validator->isValid());
    }

    public function testValidateEmail()
    {
        $validator = new Validator(['foo' => 'test@gmail.com']);
        $validator->addRules('foo', 'email');
        $this->assertTrue($validator->isValid());

        $validator = new Validator(['foo' => 'not.an.email.com']);
        $validator->addRules('foo', 'email');
        $this->assertFalse($validator->isValid());
    }

    public function testValidateUrl()
    {
        $validator = new Validator(['foo' => 'http://test.com/test.html']);
        $validator->addRules('foo', 'url');
        $this->assertTrue($validator->isValid());

        $validator = new Validator(['foo' => 'not/a/url']);
        $validator->addRules('foo', 'url');
        $this->assertFalse($validator->isValid());
    }

    public function testValidateRegex()
    {
        $validator = new Validator(['foo' => 'abc']);
        $validator->addRules('foo', 'regex:/abc/');
        $this->assertTrue($validator->isValid());

        $validator = new Validator(['foo' => 'abc']);
        $validator->addRules('foo', 'regex:/ABC/');
        $this->assertFalse($validator->isValid());
    }
}