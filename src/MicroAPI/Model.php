<?php

namespace MicroAPI;

use MicroAPI\Injector;

class Model
{
    /**
     * Access a service from the injector
     *
     * @param $serviceName - The name of the service
     * @return mixed
     */
    public function getService($serviceName)
    {
        return Injector::getInstance()->getService($serviceName);
    }
} 