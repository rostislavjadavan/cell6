<?php

/**
 * Routes
 * 
 */
$router = $container->singleton('\Core\Router');

//$router->get('404', 'Main#error404')));
//$router->get('500', 'Main#error500')));

$router->get('homepage', '', 'Main', 'index');
$router->get('page1', 'page1', 'Main', 'page1');
