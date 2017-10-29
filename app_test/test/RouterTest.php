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
        $request = $this->createRequest($container, "/");
        $router = $container->singleton("\Core\Router");
        $router->get('homepage', '', 'Main::index');

        $result = $router->match($request->getRequestPath());
        Assert::assertNotFalse("Router::match", $result);
        Assert::assertEquals("RouterMatchResult::name", 'homepage', $result->getName());
    }

    public function pageWithParamsTest() {
        $container = new \Core\Container();
        $request = $this->createRequest($container, "/page/2017/08");
        $router = $container->singleton("\Core\Router");
        $router->get('archive', 'page/<year>/<month>', 'Main::archive');

        $result = $router->match($request->getRequestPath());
        Assert::assertNotFalse("Router::match", $result);
        Assert::assertEquals("RouterMatchResult::name", 'archive', $result->getName());

        $params = $result->getRequestParams();

        Assert::assertEquals("RequestParams::year", '2017', $params['year']);
        Assert::assertEquals("RequestParams::month", '08', $params['month']);
    }

    public function urlGeneratorTest() {
        $container = new \Core\Container();
        $this->createRequest($container, "/");
        $router = $container->singleton("\Core\Router");
        $router->get('homepage', '', 'Main::index');
        $router->get('page1', 'page1/<param>', 'Main::page1');
        $router->get('page2', 'page2/(<param>)', 'Main::page1');

        Assert::assertEquals("homepage url","http://domain.com/", $router->url('homepage'));
        Assert::assertEquals("page1 url","http://domain.com/page1/1", $router->url('page1', array('param' => 1)));
        Assert::assertEquals("page2 url with param","http://domain.com/page2/1", $router->url('page2', array('param' => 1)));
        Assert::assertEquals("page2 url without param","http://domain.com/page2", $router->url('page2'));
    }

    private function createRequest(\Core\Container $container, $uri) {
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