<?php

function postName($request, $response)
{
    $data = ['greeting' => 'Hi, ' . $request->getPathWildcard('name')];
    $response->make($data);
};