<?php

return function($router)
{

    $router->get([
        'route' => '/name/(name)',
        'to' => 'Example@getName',
        'require' => function() {
            return 1 + 1 == 2;
        }
    ]);

};