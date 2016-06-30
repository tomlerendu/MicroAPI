<?php
namespace App\Controller;

class Example
{
    public function getName($request, $response, $route)
    {
        $data = ['greeting' => 'Hi, ' . $route->getWildcard('name'), 'method' => 'You used ' . $request->getMethod()];
        $response->make($data);
    }
}