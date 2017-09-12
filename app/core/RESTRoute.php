<?php

/**
 * REST Route
 *
 * @package MVC
 * @author spool
 */

namespace Core;

class RESTRoute extends Route {
	
	/**
	 * Methods impemented by RESTController
	 * @var array 
	 */
	private $allowedHttpMethods = array('GET', 'POST', 'PUT', 'DELETE');
	
	/**
	 * Return response
	 * @return Response Response
     * @throws \Exception
	 */
	public function getResponse($params = array()) {
		$request = Container::get('request');
		
		$method = $request->getMethod();
		if (!in_array($method, $this->allowedHttpMethods)) {
			throw new \Exception("RESTROUTE: $method not implemented");
		}
		
		$this->params['method'] = strtolower($method);
		$class = Container::build('\Core\Invokable', array_merge($this->params, $params));
		return $class->invoke($params);
	}

}
