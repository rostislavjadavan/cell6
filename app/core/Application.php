<?php

namespace Core;

/**
 * Class Application
 * @package Core
 */
class Application {

    protected $router = null;
    protected $request = null;
    protected $container = null;

    public function __construct(Router $router, Request $request, Container $container) {
        $this->router = $router;
        $this->request = $request;
        $this->container = $container;
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
        if ($response instanceof Response) {
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
                return $this->router->getRoute('500')->getResponse();
            } catch (MVCException $e) {
                $content = "<html><head><title>Internal Server Error</title></htead><h1>500</h1><p>Internal Server Error</p>";
                return $this->container->make('\Core\HtmlResponse', array('content' => $content, 'code' => 500));
            }
        }
    }

    protected function sendSystemHeaders() {
        header('X-Framework: cell6');
    }

}
