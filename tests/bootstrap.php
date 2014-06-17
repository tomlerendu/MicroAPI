<?php

require 'src/public/const.php';

require MICROAPI_PATH . '/Autoloader.php';

$autoloader = new \MicroAPI\Autoloader();
$autoloader->addNamespace('App', APP_PATH);
$autoloader->addNamespace('MicroAPI', MICROAPI_PATH);