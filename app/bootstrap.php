<?php

/**
 *
 * Setup
 *
 * @author spool
 */
define('DEBUG_MODE', true);
define('PUBDIR', 'public');

define('SYSPATH', dirname(__FILE__));
define('COREPATH', SYSPATH . DIRECTORY_SEPARATOR . 'core');
define('VENDOR', SYSPATH . DIRECTORY_SEPARATOR . 'vendor');

/**
 * Create container, register autoloader and error handler
 */
include(COREPATH . DIRECTORY_SEPARATOR . 'ClassAutoLoader.php');
include(COREPATH . DIRECTORY_SEPARATOR . 'Container.php');

$container = new \Core\Container();

$loader = $container->singleton("\Core\ClassAutoLoader");
$loader->registerAutoloader();

$errorHandler = $container->singleton("\Core\ErrorHandler");
$errorHandler->register(DEBUG_MODE);

/**
 * Register composer autoloader if available
 */
if (file_exists(VENDOR . DIRECTORY_SEPARATOR . 'autoload.php')) {
    include(VENDOR . DIRECTORY_SEPARATOR . 'autoload.php');
}

/**
 * Configuration
 */
require SYSPATH . DIRECTORY_SEPARATOR . 'config.php';


/**
 * Run application
 */
$application = $container->singleton('\Core\Application');
$application->run();
