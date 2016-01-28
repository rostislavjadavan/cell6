<?php

/**
 * Application
 *
 * @package MVC
 * @author spool
 */

namespace System\MVC;

class Application {

	protected $router = null;
	protected $request = null;

	public function __construct(Router $router, \System\Http\Request $request) {
		$this->router = $router;
		$this->request = $request;
	}

	public function run() {
		$requestPath = $this->request->getRequestPath();

		try {
			$response = $this->router->match($requestPath);
		} catch (\Exception $e) {
			$response = $this->processError($e);
		}

		// Send response to browser
		$this->sendSystemHeaders();
		if ($response instanceof \System\Http\Response) {
			$response->sendHeaders();
			$response->sendContent();
			// Just echo response from controller
		} else {
			echo $response;
		}
	}

	protected function processError(\Exception $e) {
		if (DEBUG_MODE) {
			throw $e;
		} else {
			try {
				return $this->router->get('500')->getResponse();
			} catch (MVCException $e) {
				$content = "<html><head><title>Internal Server Error</title></htead><h1>500</h1><p>Internal Server Error</p>";
				return \System\Core\Container::build('\System\Http\HtmlResponse', array('content' => $content, 'code' => 500));
			}
		}
	}

	protected function sendSystemHeaders() {
		header('X-System: ' . SYSNAME . '_' . SYSVER);
	}

}
