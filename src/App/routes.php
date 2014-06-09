<?php

$router->get([
    'route' => '/name/(?)/',
    'object' => 'Example@getName',
    'require' => function() {
        return 1 + 1 == 2;
    }
]);

$router->post([
    'route' => '/name/(?)/',
    'function' => 'postName',
    'require' => function() {
        return 1 + 1 == 2;
    }
]);