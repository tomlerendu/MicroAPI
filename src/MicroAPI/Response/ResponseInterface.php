<?php

namespace MicroAPI\Response;

interface ResponseInterface
{
    /**
     *
     */
    public function __construct($responseArray);

    /**
     * An array of headers that will be sent with the response
     *
     * @return  - The headers that will be sent with the response
     */
    public function getHeaders();

    /**
     * The response in the form of a string
     *
     * @return - The response string
     */
    public function getResponse();
}