<?php

use TomLerendu\MicroAPI\Router;

return function(Router $router)
{

    $router->get([
        'route' => '/name/(name)',
        'to' => 'Example@getName',
        'require' => function() {
            return 1 + 1 == 2;
        }
    ]);

};