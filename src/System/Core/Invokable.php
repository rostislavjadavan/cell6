<?php

/**
 * Invokable
 *
 * @package Utils
 * @author spool
 */

namespace System\Core;

class Invokable {

	/**
	 * @var string Class name
	 */
	protected $class = null;

	/**
	 * @var string Method name
	 */
	protected $method = null;

	/**
	 * @var array Params
	 */
	protected $params = array();

	/**
	 * @var object Class instance
	 */
	protected $classInstance = null;

	/**
	 * Setup
	 * 
	 * @param string Class
	 * @param string Method
	 * @param array Params
	 */
	public function __construct($class, $method, $params = array()) {
		$this->class = $class;
		$this->method = $method;
		$this->params = $params;
	}

	/**
	 * Get class name
	 * 
	 * @return string
	 */
	public function getClass() {
		return $this->class;
	}

	/**
	 * Get method name
	 * 
	 * @return string
	 */
	public function getMethod() {
		return $this->method;
	}

	/**
	 * Get params
	 * @return array
	 */
	public function getParams() {
		return $this->params;
	}

	/**
	 * Get class instance
	 * 
	 * @return Class instance
	 */
	public function getInstance() {
		if ($this->classInstance == null) {			
			$this->classInstance = \System\Core\Container::build($this->class);
		}

		return $this->classInstance;
	}

	/**
	 * Run class
	 * 	 
	 * @return Response Class response
	 */
	public function invoke(array $params = array()) {
		// Inspect called method
		$rm = new \ReflectionMethod($this->class, $this->method);

		// Merge params
		$tmpParams = array_merge($this->params, $params);			
		
		// Intersect injected params with called function params
		$args = array();
		foreach ($rm->getParameters() as $rp) {
			foreach ($tmpParams as $paramName => $paramValue) {
				$param = $rp->getName();

				if ($param == $paramName) {
					$args[$paramName] = $paramValue;
				}
			}
		}

		// Invoke class and return reponse
		return $rm->invokeArgs($this->getInstance(), $args);
	}	
}