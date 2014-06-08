<?php
namespace App\Controller;

class Example
{
	public function getName($name)
	{
		$response = ['greeting' => 'Hi, ' . $name];
		$this->response->make($response);
	}
}