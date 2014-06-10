<?php
namespace App\Controller;

class Example
{
	public function getName($request, $response)
	{
		$data = ['greeting' => 'Hi, ' . $request->getPathWildcard('name')];
		$response->make($data);
	}
}