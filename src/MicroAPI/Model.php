<?php
namespace TomLerendu\MicroAPI;

trait Model
{
    /**
     * Access a service from the injector
     *
     * @param $serviceName - The name of the service
     * @return mixed
     */
    public static function _service($serviceName)
    {
        return Injector::getInstance()->getService($serviceName);
    }
} 