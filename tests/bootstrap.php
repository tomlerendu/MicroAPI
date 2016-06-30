<?php

echo "Starting MicroAPI tests.";

$path = __DIR__ . '/../vendor/autoload.php';
$path = realpath($path);
require_once($path);
require_once('MicroAPITestCase.php');