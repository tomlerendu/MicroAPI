<?php

namespace MicroAPI\Response;

class JsonResponse implements ResponseInterface
{
    private $responseArray;

    public function __construct($responseArray)
    {
        $this->responseArray = $responseArray;
    }

    public function getHeaders()
    {
        return 'Content-type: application/json';
    }

    public function getResponse()
    {
        return json_encode($this->responseArray);
    }
}