<?php
namespace MicroAPI\Response;

interface ResponseInterface
{
	public function __construct($responseArray);
	public function getHeaders();
	public function getResponse();
}