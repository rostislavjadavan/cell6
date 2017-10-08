<?php

require_once TEST_APP_PATH . "core/RuntimeException.php";
require_once TEST_APP_PATH . "core/ArrayList.php";
require_once TEST_APP_PATH . "core/Container.php";
require_once TEST_APP_PATH . "core/Request.php";
require_once TEST_APP_PATH . "core/Route.php";
require_once TEST_APP_PATH . "core/Router.php";

class RouterTest {

    public function homepageTest() {
        $container = new \Core\Container();
        $request = $this->createRequestFor($container, "/");
        $router = $container->singleton("\Core\Router");
        $router->get('homepage', '', 'Main', 'index');

        $result = $router->match($request->getRequestPath());
        Assert::assertNotFalse("Router::match", $result);
        Assert::assertEquals("RouterMatchResult::name", 'homepage', $result->getName());
    }

    public function pageWithParamsTest() {
        $container = new \Core\Container();
        $request = $this->createRequestFor($container, "/page/2017/08");
        $router = $container->singleton("\Core\Router");
        $router->get('archive', 'page/<year>/<month>', 'Main', 'archive');

        $result = $router->match($request->getRequestPath());
        Assert::assertNotFalse("Router::match", $result);
        Assert::assertEquals("RouterMatchResult::name", 'archive', $result->getName());

        $params = $result->getRequestParams();

        Assert::assertEquals("RequestParams::year", '2017', $params['year']);
        Assert::assertEquals("RequestParams::month", '08', $params['month']);
    }

    private function createRequestFor(\Core\Container $container, $uri) {
        return $container->singleton("\Core\Request", array(
            'server' => array(
                'SCRIPT_NAME' => '/index.php',
                'SERVER_NAME' => "domain.com",
                'HTTP_HOST' => "domain.com",
                'REQUEST_URI' => $uri
            ),
            'query' => array(),
            'post' => array(),
            'files' => array(),
            'cookies' => array(),
            'session' => array()
        ));
    }
}