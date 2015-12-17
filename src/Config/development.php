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

$router->set('login', new \System\MVC\Route(array('uri' => 'login', 'action' => '\Dev\DevController#login')));
$router->set('gotologin', new \System\MVC\Route(array(
	'uri' => 'gotologin', 'action' => '\Dev\DevController#gotologin',
	'hook.postMatch' => new \System\Core\Invokable('\Dev\Auth', 'checkLogin', array('roles' => 'admin, user'))
)));

$router->set('view', new \System\MVC\Route(array('uri' => 'view', 'action' => '\Dev\DevController#view')));

$router->set('pure.dev', new \System\MVC\Route(array('uri' => 'pure/dev', 'action' => '\Pure\Controller\DevController#index')));

$router->set('homepage', new \System\MVC\Route(array('uri' => '(<page>)', 'action' => '\Dev\DevController#homepage')));