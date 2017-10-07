<?php

/**
 * Routes
 * 
 */
$router = $container->singleton('\Core\Router');

//$router->get('404', 'Main#error404')));
//$router->get('500', 'Main#error500')));

$router->get('homepage', '', 'Main', 'index');
