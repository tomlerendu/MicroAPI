<?php

namespace MicroAPI;

class Response
{
    private $format;
    private $cacheTime;
    private $headers;

    public function __construct($format, $cacheTime, $headers)
    {
        $this->format = $format;
        $this->cacheTime = $cacheTime;
        $this->headers = $headers;
    }

    public function make($responseArray, $options=[])
    {
        //Set any default headers
        if (count($this->headers) != 0)
            $this->setHeaders($this->headers);
        //Set any custom headers
        if (isset($options['header']))
            $this->setHeaders($options['header']);

        //Set the cache time
        $cacheTime = isset($options['cacheTime']) ? $options['cacheTime'] : $this->cacheTime;
        if($cacheTime !== 0) {
            $ttl = gmdate('D, d M Y H:i:s', time() + $cacheTime) . ' GMT';
            $this->setHeaders([
                'Expires: ' . $ttl,
                'Pragma: cache',
                'Cache-Control: max-age=' . $cacheTime
            ]);
        }

        $responseMaker = (isset($options['format'])) ? $options['format'] : $this->format;
        $responseMaker = new $responseMaker($responseArray);

        $this->setHeaders($responseMaker->getHeaders());
        echo $responseMaker->getResponse();
    }

    public function error($error, $responseArray = [], $options = [])
    {
        //If the error is a number
        if (is_numeric($error))
            $error = $this->getHttpString($error);
		
        $this->setHeaders($error);

        //Make the response if one was passed
        if (count($responseArray) !== 0)
            $this->make($responseArray, $options);
    }

    public function redirect($to, $options = [])
    {
        $this->setHeaders('Location: ' . $to);
    }

    private function setHeaders($headers)
    {
        if (!is_array($headers))
            header($headers);
        else {
            foreach($headers as $header)
                header($header);
        }
    }

    private function getHttpString($code)
    {
        switch($code) {
            case 400:
                return 'HTTP/1.0 400 Bad Request';
            case 401:
                return 'HTTP/1.0 401 Unauthorized';
            case 404:
                return 'HTTP/1.0 404 Not Found';
            case 500:
                return 'HTTP/1.0 500 Internal Server Error';
            case 502:
                return 'HTTP/1.0 502 Bad Gateway';
            case 503:
                return 'HTTP/1.0 503 Service Unavailable';
        }

        return '';
    }
}