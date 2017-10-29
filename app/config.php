<?php

/**
 * Routes
 * 
 */
$router = $container->singleton('\Core\Router');

$router->error404('Main::error404');
$router->error500('Main::error500');

$router->get('homepage', '/',  'Main::index');
$router->get('page1', '/page1', 'Main::page1');
$router->get('page2', '/page2/<name>', 'Main::page2');
$router->get('view', '/view', 'page');
$router->get('func', '/func', function() {
    return 'func';
});

$router->rest("api-endpoint", '/api', 'Rest');