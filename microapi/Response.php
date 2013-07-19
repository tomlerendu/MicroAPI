<?php
namespace MicroAPI;

class Response extends Singleton
{
	public function make($responseArray, $options = [])
	{
		//Set the header
		if(isset($options['header']))
			header($options['header']);

		//Figure out what format should be used
		$format = (isset($options['format'])) ? $options['format'] : $this->config['format'];
		$format = strtolower($format);
		call_user_func_array([$this, $format], [$responseArray]);
	}

	public function error($errorNum, $responseArray, $options = [])
	{
		switch($errorNum) 
		{
			case 404:
				header('HTTP/1.0 404 Not Found');
				break;
		}

		$this->make($responseArray, $options);
	}

	private function json($responseArray)
	{
		header('Content-type: application/json');
		echo json_encode($responseArray);
	}

	private function xml($responseArray)
	{
		header('Content-type: text/xml');
		//TODO xml support
	}
}