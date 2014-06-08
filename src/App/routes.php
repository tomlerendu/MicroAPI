<?php

$router->get('/name/(?)/', 'Example@getName', function(){ return 1 + 1 == 2; });