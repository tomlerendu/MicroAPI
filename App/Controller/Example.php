<?php
namespace App\Controller;

class Example extends \MicroAPI\Controller
{
	public function getName($name)
	{
		$response = ['greeting' => 'Hi, ' . $name];
		$this->response->make($response);
	}
}