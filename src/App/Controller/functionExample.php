<?php

function postName($request, $response, $route)
{
    $data = ['greeting' => 'Hi, ' . $route->getWildcard('name'), 'method' => 'You used ' . $request->getMethod()];
    $response->make($data);
};