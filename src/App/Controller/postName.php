<?php

return function($request, $response)
{
    $data = ['greeting' => 'Hi, ' . $request->getPathWildcard(0)];
    $response->make($data);
};