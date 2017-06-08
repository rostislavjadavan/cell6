<?php

/**
 * Database
 * 
 */
\System\Core\Container::buildAndAdd('db', '\Database\DB', array(
	//'dsn' => 'mysql:host=localhost;dbname=cell6_test', 'username' => 'root', 'password' => 'root'
    'dsn' => 'mysql:host=localhost;dbname=naradi-arco1510', 'username' => 'root', 'password' => 'root'
));

/**
 * Routes
 * 
 */
$router = \System\Core\Container::buildAndAdd('router', '\System\MVC\Router');

$router->set('404', new \System\MVC\Route(array('action' => '\Dev\DevController#error404')));
$router->set('500', new \System\MVC\Route(array('action' => '\Dev\DevController#error500')));

$router->set('admin', new \System\MVC\Route(array('uri' => 'admin', 'action' => '\Admin\DevController#index')));
$router->set('admin-grid', new \System\MVC\Route(array('uri' => 'admin/grid', 'action' => '\Admin\DevController#grid')));
$router->set('admin-form', new \System\MVC\Route(array('uri' => 'admin/form', 'action' => '\Admin\DevController#form')));

$router->set('admin-api-griddata', new \System\REST\RESTRoute(array('uri' => 'admin/api/griddata', 'class' => '\Admin\Api\GridDataController')));

$router->set('api', new \System\REST\RESTRoute(array('uri' => 'api', 'class' => '\Dev\RESTController')));

$router->set('db', new \System\MVC\Route(array('uri' => 'db', 'action' => '\Dev\DevController#database')));
$router->set('homepage', new \System\MVC\Route(array('uri' => '(<page>)', 'action' => '\Dev\DevController#homepage')));
