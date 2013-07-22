<?php
namespace MicroAPI;

class Response extends Singleton
{
	public function make($responseArray, $options = [])
	{
		//Possible options..

		if(isset($options['header']))
			header($options['header']);

		if(isset($options['cache']))
		{
			$ttl = gmdate('D, d M Y H:i:s', time() + $options['cache']) . ' GMT';
			header('Expires: ' . $ttl);
			header('Pragma: cache');
			header('Cache-Control: max-age=' . $options['cache']);
		}

		//Figure out what format should be used
		$format = (isset($options['format'])) ? $options['format'] : $this->config['format'];
		$format = strtolower($format);
		call_user_func_array([$this, $format], [$responseArray]);
	}

	public function error($error, $responseArray, $options = [])
	{
		//If the error was given as a number
		if(is_numeric($error))
		{
			switch($error) 
			{
				case 400:
					header('HTTP/1.0 400 Bad Request');
					break;
				case 401:
					header('HTTP/1.0 401 Unauthorized');
					break;
				case 404:
					header('HTTP/1.0 404 Not Found');
					break;
				case 500:
					header('HTTP/1.0 500 Internal Server Error');
					break;
				case 502:
					header('HTTP/1.0 502 Bad Gateway');
					break;
				case 503:
					header('HTTP/1.0 503 Service Unavailable');
					break;
			}
		}
		else
			header($error);

		//Make the response
		$this->make($responseArray, $options);
	}

	private function json($responseArray)
	{
		header('Content-type: application/json');
		echo json_encode($responseArray);
	}

	private function xml($responseArray)
	{
		//TODO XML SUPPORT
		header('Content-type: text/xml');
		echo '<?xml version="1.0"?>';		
	}
}