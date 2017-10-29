<?php

namespace Core;

/**
 * Class Router
 * @package Core
 */
class Router {

    protected $container = null;

    protected $routes = array();
    protected $currentRouteName = null;
    protected $currentRoute = null;
    protected $currentRouteResult = null;
    protected $request = null;

    /**
     * Router constructor.
     * @param Container $container
     * @param Request $request
     */
    function __construct(Container $container, Request $request) {
        $this->container = $container;
        $this->request = $request;
    }

    /**
     * @param $name
     * @param $uri
     * @param array $target
     * @param array $paramsConstraints
     * @internal param $class
     * @internal param $method
     */
    public function get($name, $uri, $target, array $paramsConstraints = array()) {
        $params = array_merge(array('uri' => $uri, 'requestMethod' => 'get'), $this->convertTargetToRouteParams($target));
        $this->routes[$name] = $this->container->make("\Core\Route", array('params' => $params, 'paramsConstraints' => $paramsConstraints));
    }

    /**
     * @param $name
     * @param $uri
     * @param $target
     * @param array $paramsConstraints
     * @internal param $class
     * @internal param $method
     */
    public function post($name, $uri, $target, array $paramsConstraints = array()) {
        $params = array_merge(array('uri' => $uri, 'requestMethod' => 'post'), $this->convertTargetToRouteParams($target));
        $this->routes[$name] = $this->container->make("\Core\Route", array('params' => $params, 'paramsConstraints' => $paramsConstraints));
    }

    /**
     * @param $name
     * @param $uri
     * @param $target
     * @param array $paramsConstraints
     * @internal param $class
     * @internal param $method
     */
    public function any($name, $uri, $target, array $paramsConstraints = array()) {
        $params = array_merge(array('uri' => $uri), $this->convertTargetToRouteParams($target));
        $this->routes[$name] = $this->container->make("\Core\Route", array('params' => $params, 'paramsConstraints' => $paramsConstraints));
    }

    /**
     * @param $name
     * @param $uri
     * @param $class
     * @param array $paramsConstraints
     */
    public function rest($name, $uri, $class, array $paramsConstraints = array()) {
        $params = array('uri' => $uri, 'class' => $class );
        $this->routes[$name] = $this->container->make("\Core\RESTRoute", array('params' => $params, 'paramsConstraints' => $paramsConstraints));
    }

    /**
     * @param $target
     * @internal param $class
     * @internal param $method
     */
    public function error404($target) {
        $this->routes['404'] = $this->container->make("\Core\Route", array('params' => $this->convertTargetToRouteParams($target)));
    }

    /**
     * @param $target
     * @internal param $class
     * @internal param $method
     */
    public function error500($target) {
        $this->routes['500'] = $this->container->make("\Core\Route", array('params' => $this->convertTargetToRouteParams($target)));
    }

    /**
     * @param $target
     * @return array
     * @throws RuntimeException
     */
    private function convertTargetToRouteParams($target) {
        if (is_string($target) && strpos($target, "::") > 0) {
            return array('class' => $target);
        }
        elseif (is_string($target)) {
            return array('view' => $target);
        }
        elseif (is_callable($target)) {
            return array('function' => $target);
        }
        throw new RuntimeException("Unknown target for route: ".$this->uri());
    }

    /**
     * @param $name
     * @return mixed
     * @throws RuntimeException
     */
    public function getRoute($name) {
        if (!array_key_exists($name, $this->routes)) {
            throw new RuntimeException('Router: Route ' . $name . ' not found.');
        }
        return $this->routes[$name];
    }

    /**
     * @param $uri
     * @return bool|mixed|RouteMatchResult
     */
    public function match($uri) {
        $uri = trim($uri);
        foreach ($this->routes as $name => $route) {
            $result = $route->match($uri);

            if ($result !== false) {
                return $this->container->make('\Core\RouteMatchResult', array('name' => $name, 'route' => $route, 'requestParams' => $result));
            }
        }
        return false;
    }

    /**
     * @param $name
     * @param array $params
     * @return mixed
     */
    public function uri($name, array $params = array()) {
        return $this->getRoute($name)->uri($params);
    }

    /**
     * @param $name
     * @param array $params
     * @param array $query
     * @return string
     */
    public function url($name, array $params = array(), array $query = array()) {
        return $this->request->getBaseUrl() . $this->getRoute($name)->uri($params) . (!empty($query) ? '?' . http_build_query($query) : '');
    }

}

class RouteMatchResult {
    private $request;
    private $name;
    private $route;
    private $requestParams = array();

    /**
     * RouteMatchResult constructor.
     * @param Request $request
     * @param $name
     * @param $route
     * @param array $requestParams
     * @internal param array $params
     */
    public function __construct(Request $request, $name, $route, array $requestParams) {
        $this->request = $request;
        $this->name = $name;
        $this->route = $route;
        $this->requestParams = $requestParams;
    }

    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getRoute() {
        return $this->route;
    }

    /**
     * @return array
     */
    public function getRequestParams() {
        return $this->requestParams;
    }

    /**
     * Generate uri for this route. Params can be override
     *
     * @param array $params
     * @return mixed
     */
    public function getUri(array $params = array()) {
        return $this->route->uri(array_merge($this->requestParams, $params));
    }

    /**
     * Generate url for this route. Params can be override
     *
     * @param array $params
     * @return string
     */
    public function getUrl(array $params = array()) {
        return $this->request->getBaseUrl() . $this->getUri($params);
    }

    /**
     * Return route output
     *
     * @return mixed
     */
    public function getResponse() {
        return $this->route->getResponse($this->requestParams);
    }
}
