<?php

/**
 * Database
 * 
 */
\System\Core\Container::buildAndAdd('db', '\Database\DB', array(
	'dsn' => 'mysql:host=localhost;dbname=test', 'username' => 'root', 'password' => 'root'
));

/**
 * Routes
 * 
 */
$router = \System\Core\Container::buildAndAdd('router', '\System\MVC\Router');

$router->set('404', new \System\MVC\Route(array('action' => '\Dev\DevController#error404')));
$router->set('500', new \System\MVC\Route(array('action' => '\Dev\DevController#error500')));

$router->set('api', new \System\REST\RESTRoute(array('uri' => 'api', 'class' => '\Dev\RESTController')));

$router->set('db', new \System\MVC\Route(array('uri' => 'db', 'action' => '\Dev\DevController#database')));
$router->set('homepage', new \System\MVC\Route(array('uri' => '(<page>)', 'action' => '\Dev\DevController#homepage')));
