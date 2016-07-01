<?php

namespace MicroAPI\Response;

class XmlResponse implements ResponseInterface
{
    private $responseArray;

    public function __construct($responseArray)
    {
        $this->responseArray = $responseArray;
    }

    public function getHeaders()
    {
        return 'Content-type: text/xml';
    }

    public function getResponse()
    {
        $xmlString = '<?xml version="1.0"?><root>';
        $this->walkArray($xmlString, $this->responseArray);
        $xmlString .= '</root>';

        return $xmlString;
    }

    /**
     * Recursively go through an array converting into a XML string.
     *
     * @param $xmlString A pointer to the string that will be used
     * @param $responseArray A pointer to the data that will be converted into a XML string
     */
    private function walkArray(&$xmlString, &$responseArray)
    {
        foreach($responseArray as $key => $item)
        {
            $xmlString .= '<' . $key . '>';

            if(is_array($item))
                $this->walkArray($xmlString, $item);
            else
                $xmlString .= $item;

            $xmlString .= '</' . $key .'>';
        }
    }
}