<?php

/**
 * 
 * Setup
 *
 * @author spool
 */
define('SYSPATH', dirname(__FILE__));
define('COREPATH', SYSPATH . DS . 'core');
define('VENDOR', 'vendor');

/**
 * Register autoloader
 */
include(COREPATH . DIRECTORY_SEPARATOR . 'ClassAutoLoader.php');

$loader = new \Core\ClassAutoLoader();
$loader->registerAutoloader();

\Core\Container::add('loader', $loader);
\Core\Container::add('request', new \Core\Request());

/**
 * Register composer autoloader
 */
include(VENDOR . DIRECTORY_SEPARATOR . 'autoload.php');

/**
 * Error handler
 */
$errorHandler = new \Core\ErrorHandler();
$errorHandler->register(DEBUG_MODE);

/**
 * Configuration
 */
require SYSPATH . DS . 'config.php';


/**
 * Run application
 */
$application = \Core\Container::build('\Core\Application');
$application->run();
