<?php
return [

	['Example@getName', '/name/(?)/', 'GET']

];

return function($router)
{
    $router->get('/name/(?)/', 'Example@getName')->if(function(){

    });
};