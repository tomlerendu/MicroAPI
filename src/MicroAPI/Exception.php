<?php

namespace MicroAPI;

class Exception extends \Exception
{
    public function __construct($message, $code=0, Exception $previous=null)
    {
        $message = 'MicroAPI: ' . $message;
        parent::__construct($message, $code, $previous);
    }
} 