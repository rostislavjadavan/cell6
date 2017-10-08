<?php

namespace Core;

/**
 * Class RESTRoute
 * @package Core
 */
class RESTRoute extends Route {

    /**
     * @var Container|null
     */
    protected $container = null;

	/**
	 * Methods impemented by RESTController
	 * @var array 
	 */
	private $allowedHttpMethods = array('GET', 'POST', 'PUT', 'DELETE');

    /**
     * RESTRoute constructor.
     * @param Container $container
     */
    public function __construct(Container $container) {
        $this->container = $container;
    }

    /**
	 * Return response
	 * @return Response Response
     * @throws \Exception
	 */
	public function getResponse($params = array()) {
		$request = $this->container->make('\Core\Request');
		
		$method = $request->getMethod();
		if (!in_array($method, $this->allowedHttpMethods)) {
			throw new \RuntimeException("RestRoute: $method not implemented");
		}

		return $this->container->makeAndInvoke(
		    $this->params['class'],
            strtolower($method),
            array_merge($this->params, $params)
        );
	}

}
