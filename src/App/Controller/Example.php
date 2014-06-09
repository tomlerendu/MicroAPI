<?php
namespace App\Controller;

class Example
{
	public function getName($request, $response)
	{
		$data = ['greeting' => 'Hi, ' . $request->getPathWildcard(0)];
		$response->make($data);
	}
}