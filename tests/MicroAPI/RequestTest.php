<?php

use MicroAPI\Request;

class RequestTest extends PHPUnit_Framework_TestCase
{
    public function testGettingMethod()
    {
        global $_SERVER;
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['REQUEST_URI'] = '/';
        $_SERVER['HTTP_USER_AGENT'] = '';

        $request = new Request();
        $this->assertEquals($request->getMethod(), 'POST');
    }

    public function testGettingPath()
    {
        global $_SERVER;
        $_SERVER['REQUEST_URI'] = '/foo/bar/';
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['HTTP_USER_AGENT'] = '';

        $request = new Request();
        $this->assertEquals($request->getPath(), '/foo/bar/');
    }

    public function testGettingPathWithSubDir()
    {
        global $_SERVER;
        $_SERVER['REQUEST_URI'] = '/foo/bar/?hello=world';
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['HTTP_USER_AGENT'] = '';

        $request = new Request('/foo');
        $this->assertEquals($request->getPath(), '/bar/');
    }

    public function testGettingPathSections()
    {
        global $_SERVER;
        $_SERVER['REQUEST_URI'] = '/foo/bar/?hello=world';
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['HTTP_USER_AGENT'] = '';

        $request = new Request();
        $this->assertEquals($request->getPathSections(), ['foo', 'bar']);
    }

    public function testGettingUserAgent()
    {
        global $_SERVER;
        $_SERVER['REQUEST_URI'] = '/foo/';
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)';

        $request = new Request();
        $this->assertEquals($request->getUserAgent(), 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
    }

    public function testGettingPostParam()
    {
        global $_SERVER, $_POST;
        $_SERVER['REQUEST_URI'] = '';
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['HTTP_USER_AGENT'] = '';
        $_POST['name'] = 'Foo';

        $request = new Request();
        $this->assertTrue($request->getParam('name') === 'Foo' && $request->getParam('name', 'POST') === 'Foo');
    }

    public function testGettingGetParam()
    {
        global $_SERVER, $_GET;
        $_SERVER['REQUEST_URI'] = '';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['HTTP_USER_AGENT'] = '';
        $_GET['name'] = 'Bar';

        $request = new Request();
        $this->assertTrue($request->getParam('name') === 'Bar' && $request->getParam('name', 'GET') === 'Bar');
    }

    public function testGettingUndefinedParam()
    {
        global $_SERVER, $_GET;
        $_SERVER['REQUEST_URI'] = '';
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['HTTP_USER_AGENT'] = '';

        $request = new Request();
        $this->assertTrue(
            $request->getParam('name') === false
            && $request->getParam('name', 'GET') === false
            && $request->getParam('name', 'POST') === false
        );
    }

    public function testDuplicateNameParam()
    {
        global $_SERVER, $_GET, $_POST;
        $_SERVER['REQUEST_URI'] = '';
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['HTTP_USER_AGENT'] = '';
        $_GET['name'] = 'FOO';
        $_POST['name'] = 'BAR';


        $request = new Request();
        $this->assertTrue(
            $request->getParam('name') === 'BAR'
            && $request->getParam('name', 'GET') === 'FOO'
            && $request->getParam('name', 'POST') === 'BAR'
        );
    }
}
 