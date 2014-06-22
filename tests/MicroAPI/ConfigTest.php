<?php

use MicroAPI\Config;

class ConfigTest extends PHPUnit_Framework_TestCase
{
    public function testGettingKey()
    {
        $config = new Config();
        $config->set('test.foo.bar', 'Testing');
        $this->assertEquals($config->get('test.foo.bar'), 'Testing');
    }

    public function testDeletingKey()
    {
        $config = new Config();
        $config->set('test.foo.bar', 'Testing');
        $this->assertTrue(
            $config->remove('test.foo.bar') === 'Testing' &&
            $config->get('test.foo.bar') == null
        );
    }

    public function testOverwritingKey()
    {
        $config = new Config();
        $config->set('test.foo.bar', 'Testing');
        $config->set('test.foo.bar', 'Bar');
        $this->assertEquals($config->get('test.foo.bar'), 'Bar');
    }

    public function testDeletingKeyOnSameArray()
    {
        $config = new Config();
        $config->set('test.foo.bar', 1);
        $config->set('test.foo.foo', 2);
        $config->remove('test.foo.bar');
        $this->assertEquals($config->get('test.foo.foo'), 2);
    }

    public function testGettingArrayOfKeys()
    {
        $config = new Config();
        $config->set('test.foo.bar', 1);
        $config->set('test.foo.foo', 2);
        $this->assertEquals($config->get('test.foo'), ['bar'=>1,'foo'=>2]);
    }

    public function testDeletingArrayOfKeys()
    {
        $config = new Config();
        $config->set('test.foo.bar', 1);
        $config->set('test.foo.foo', 2);
        $config->remove('test.foo');
        $this->assertEquals($config->get('test.foo'), null);
    }
}
 