<?php

/**
 * Error Handler
 *
 * @package Core
 * @author spool
 */

namespace System\Core;

class ErrorHandler {

	/**
	 *
	 * @var array Define for what type of errors to call exception handler in shutdown function
	 */
	private $shutdownErrors = array(E_PARSE, E_ERROR, E_USER_ERROR);

	/**
	 *
	 * @var array List of errors that will be processed by standard PHP error handler
	 */
	private $disallowedErrors = array(E_NOTICE);

	/**
	 * Register all necessary handlers to catch all possible error types
	 */
	public function register($debugMode = true) {
		if ($debugMode) {
			error_reporting(E_ALL);
			ini_set('display_errors', '1');
			set_exception_handler(array($this, 'exceptionHandler'));
			set_error_handler(array($this, 'errorHandler'));

			// Register shutdown function to catch fatal errors
			register_shutdown_function(array($this, 'shutdownHandler'));
		} else {
			error_reporting(0);
			ini_set('display_errors', '0');

			// TODO: log exceptions to file
		}
	}

	/*
	 * Exception handler
	 * 
	 * Called on uncaught exception
	 * 
	 * @param Exception Uncaught exception
	 */

	public function exceptionHandler(\Exception $e) {
		$this->renderException($e);
		return TRUE;
	}

	/**
	 * Error handler
	 * 
	 * Called when error happens.
	 * 
	 * @param int Error type (E_ERROR, E_NOTICE etc.)
	 * @param string Error message
	 * @param string Path to file where error occurs
	 * @param int Line number
	 */
	public function errorHandler($code, $error, $file = NULL, $line = NULL) {
		if (error_reporting() && !in_array($code, $this->disallowedErrors)) {
			throw new \ErrorException($error, $code, 0, $file, $line);

			// Return TRUE to bypass standard PHP error handler
			return TRUE;
		}

		return FALSE;
	}

	/**
	 * Shutdown function
	 * 
	 * Purpose is to catch fatal errors because it is not possible using
	 * error handler.
	 */
	public function shutdownHandler() {
		$error = error_get_last();

		if (error_reporting() && $error && in_array($error['type'], $this->shutdownErrors)) {
			ob_clean();
			$this->renderException(new \ErrorException($error['message'], $error['type'], 0, $error['file'], $error['line']));
		}

		exit(1);
	}

	private function renderException(\Exception $exception) {
		$out = <<< EOD
<html>
	<head>
		<meta charset="utf-8" />
		<title>Error</title>
		<style type="text/css">
			body { font-family: "Consolas", Lucida Console, Courier; font-size: 14px; margin: 0; padding: 0 }
			.error-message { padding: 11px 22px; background-color: #fdf5ce; }
			.error-message h1 { font-size: 31px; color: #d52627 }
			.error-message h2 { font-weight: normal; font-size: 19px }
			.trace { margin: 11px 22px }
			.trace pre { padding: 6px 11px; border: 1px solid #ddd }
		</style>		
	</head>
	<body>
		<div class="error-message">			
			<h1>ERROR {$exception->getCode()}: {$exception->getMessage()}</h1>
			<h2>File {$exception->getFile()} on line {$exception->getLine()}.</h2>
		</div>		
		<div class="trace">			
			<h3>Trace</h3>
			<pre>{$exception->getTraceAsString()}</pre>		
		</div>		
	</body>
</html>				
EOD;
		echo $out;
	}

}
