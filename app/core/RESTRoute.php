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
    private $allowedHttpMethods = ['GET', 'POST', 'PUT', 'DELETE'];

    /**
     * Return response
     * @param array $params
     * @return Response Response
     */
    public function getResponse($params = []) {
        $request = $this->container->make('\Core\Request');

        $method = $request->getMethod();
        if (!in_array($method, $this->allowedHttpMethods)) {
            throw new \RuntimeException("RestRoute: $method not implemented");
        }

        return $this->container->makeAndInvoke('\controllers\\' . $this->params['class'], strtolower($method), array_merge($this->params, $params));
    }

}
