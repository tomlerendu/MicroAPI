<?php

return function($request, $response)
{
    $data = ['greeting' => 'Hi, ' . $request->getPathWildcard('name')];
    $response->make($data);
};