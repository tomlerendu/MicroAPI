<?php

namespace MicroAPITests;

use \MicroAPI\Config;

class ConfigTest extends \MicroAPITestCase
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
        $config->remove('test.foo.bar');
        $this->assertEquals($config->get('test.foo.bar'), null);
    }

    public function testOverwritingKey()
    {
        $config = new Config();

        $config->set('test.foo.bar', 'Testing');
        $config->set('test.foo.bar', 'Bar');
        $this->assertEquals($config->get('test.foo.bar'), 'Bar');
    }

    public function testSettingDefaultKey()
    {
        $config = new Config();
        $this->assertEquals($config->get('test.foo.bar'), null);

        $config->setDefault('test.foo.bar', 'Testing');
        $this->assertEquals($config->get('test.foo.bar'), 'Testing');

        $config->set('test.foo.bar', 'NewValue');
        $this->assertEquals($config->get('test.foo.bar'), 'NewValue');

        $config->remove('test.foo.bar');
        $this->assertEquals($config->get('test.foo.bar'), 'Testing');
    }
}
 