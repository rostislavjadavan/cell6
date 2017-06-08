<?php

/**
 * 
 * Setup
 *
 * @author spool
 */
define('SYSPATH', dirname(__FILE__));
define('COREPATH', SYSPATH . DS . 'System' . DS . 'Core');
define('CONFIGPATH', SYSPATH . DS . 'Config');

/**
 * Register autoloader
 */
include(COREPATH . DIRECTORY_SEPARATOR . 'ClassAutoLoader.php');

$loader = new \System\Core\ClassAutoLoader();
$loader->registerAutoloader();

\System\Core\Container::add('loader', $loader);

/**
 * Error handler
 */
$errorHandler = new \System\Core\ErrorHandler();
$errorHandler->register(DEBUG_MODE);

/**
 * Configuration
 */
\System\Core\Container::add('request', new \System\Http\Request());

if (\System\Core\Container::get('request')->isLocalhost()) {
	require CONFIGPATH . DS . 'development.php';
} else {
	require CONFIGPATH . DS . 'production.php';
}

/**
 * Run application
 */
$application = \System\Core\Container::build('\System\MVC\Application');
$application->run();
