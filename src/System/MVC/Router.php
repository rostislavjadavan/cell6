<?php

/**
 * Router
 *
 * @package MVC
 * @author spool
 */

namespace System\MVC;

class Router {

	protected $routes = array();
	protected $currentRouteName = null;
	protected $currentRoute = null;
	protected $currentRouteResult = null;
	protected $request = null;

	function __construct(\System\Http\Request $request) {
		$this->request = $request;
	}

	public function set($name, Route $route) {
		$this->routes[$name] = $route;
	}

	public function get($name) {
		if (!array_key_exists($name, $this->routes)) {
			throw new MVCException('Route ' . $name . ' not found.');
		}
		return $this->routes[$name];
	}
	
	public function getAll() {
		return $this->routes;
	}

	public function remove($name) {
		unset($this->routes[$name]);
	}

	public function match($uri) {
		$uri = trim($uri);

		// Try to find route
		$route = $this->findRoute($uri);

		// No match => 404
		if ($route === false) {
			try {
				return $this->get('404')->getResponse();
			} catch (MVCException $e) {
				$content = "<html><head><title>Page not found</title></htead><h1>404</h1><p>Page not found</p>";
				return \System\Core\Container::build('\System\Http\HtmlResponse', array('content' => $content, 'code' => 404));
			}
		}

		// Match. First check for canonical url
		if ($this->getCurrentUrl() != $this->request->getRequestUrl()) {
			return \System\Core\Container::build('\System\Http\RedirectResponse', array('url' => $this->getCurrentUrl()));
		}

		// Invoke route
		return $route->getResponse($this->currentRouteResult);
	}

	public function createUri($name, array $params = array()) {
		return $this->get($name)->createUri($params);
	}

	public function createUrl($name, array $params = array()) {
		return $this->request->getBaseUrl() . $this->get($name)->createUri($params);
	}

	public function getCurrentRoute() {
		return $this->currentRoute;
	}
	
	function getCurrentRouteName() {
		return $this->currentRouteName;
	}
	
	public function getCurrentUri() {
		return $this->currentRoute->createUri($this->currentRouteResult);
	}

	public function getCurrentUrl() {
		return $this->request->getBaseUrl() . $this->getCurrentUri();
	}

	protected function findRoute($uri) {
		foreach ($this->routes as $name => $route) {
			$result = $route->match($uri);

			if ($result !== false) {
				$this->currentRoute = $route;
				$this->currentRouteName = $name;
				$this->currentRouteResult = $result;
				return $route;
			}
		}
		return false;
	}

}
