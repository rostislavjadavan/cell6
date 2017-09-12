<?php

/**
 * Routes
 * 
 */
$router = \Core\Container::buildAndAdd('router', '\Core\Router');

$router->set('404', new \Core\Route(array('action' => 'Main#error404')));
$router->set('500', new \Core\Route(array('action' => 'Main#error500')));

$router->set('homepage', new \Core\Route(array('uri' => '', 'action' => 'Main#index')));
