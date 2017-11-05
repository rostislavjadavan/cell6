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

    protected $routeMatchResult = null;

    /**
     * Application constructor.
     *
     * @param Router $router
     * @param Request $request
     * @param Container $container
     */
    public function __construct(Router $router, Request $request, Container $container) {
        $this->router = $router;
        $this->request = $request;
        $this->container = $container;
    }

    /**
     * Run application. Main entry point.
     */
    public function run() {
        try {
            $requestPath = $this->request->getRequestPath();
            $response = $this->getResponse($requestPath);
        } catch (RuntimeException $e) {
            $response = $this->processInternalServerError($e);
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

    /**
     * Get response for path
     *
     * @param $requestPath
     * @return mixed|Response
     */
    protected function getResponse($requestPath) {
        $this->routeMatchResult = $result = $this->router->match($requestPath);
        if ($result == false) {
            return $this->processPageNotFound();
        }

        if ($result->getUrl() != $this->request->getRequestUrl()) {
            return $this->container->make('\Core\RedirectResponse', ['url' => $result->getUrl()]);
        }

        return $result->getResponse();
    }

    /**
     * Return current route name, route and request params
     *
     * @return RouteMatchResult
     */
    public function getRouteMatchResult() {
        return $this->routeMatchResult;
    }


    /**
     * 404 Page not Found
     *
     * @return mixed|Response
     */
    protected function processPageNotFound() {
        try {
            $response = $route = $this->router->getRoute('404')->getResponse();
            $response->setCode(404);
            return $response;
        } catch (RuntimeException $e) {
            $content = "<html><head><title>Page not Found</title></htead><h1>500</h1><p>The requested URL was not found on this server.</p>";
            return $this->container->make('\Core\HtmlResponse', ['content' => $content, 'code' => 404]);
        }
    }

    /**
     * 500 Internal Server Error
     *
     * @param \Exception $e
     * @return mixed|Response
     * @throws \Exception
     */
    protected function processInternalServerError(\Exception $e) {
        if (DEBUG_MODE) {
            throw $e;
        } else {
            try {
                $response = $route = $this->router->getRoute('500')->getResponse();
                $response->setCode(500);
                return $response;
            } catch (RuntimeException $e) {
                $content = "<html><head><title>Internal Server Error</title></htead><h1>500</h1><p>Internal Server Error</p>";
                return $this->container->make('\Core\HtmlResponse', ['content' => $content, 'code' => 500]);
            }
        }
    }

    /**
     * Send system identification headers
     */
    protected function sendSystemHeaders() {
        header('X-Framework: cell6');
    }
}
