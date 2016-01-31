<?php

/**
 * REST Route
 *
 * @package MVC
 * @author spool
 */

namespace System\REST;

class RESTRoute extends \System\MVC\Route {
	
	/**
	 * Methods impemented by RESTController
	 * @var array 
	 */
	private $allowedHttpMethods = array('GET', 'POST', 'PUT', 'DELETE');
	
	/**
	 * Return response
	 * @return Response Response
	 */
	public function getResponse($params = array()) {
		$request = \System\Core\Container::get('request');
		
		$method = $request->getMethod();
		if (!in_array($method, $this->allowedHttpMethods)) {
			throw new \Exception("RESTROUTE: $method not implemented");
		}
		
		$this->params['method'] = strtolower($method);
		$class = \System\Core\Container::build('\System\Core\Invokable', array_merge($this->params, $params));
		return $class->invoke($params);
	}

}
