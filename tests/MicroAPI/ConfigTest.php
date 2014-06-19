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
}
 