<?php

return function($router)
{

    $router->get([
        'route' => '/name/(name)',
        'class' => 'Example@getName',
        'require' => function() {
            return 1 + 1 == 2;
        }
    ]);

    $router->post([
        'route' => '/name/(name)',
        'function' => 'functionExample@postName',
        'require' => function() {
            return 1 + 1 == 2;
        }
    ]);

};