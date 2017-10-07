<?php

namespace Core;

/**
 * Class Route
 * @package Core
 */
class Route {

    /**
     * @var null
     */
    protected $container = null;

    /**
     *
     * @var string URI
     */
    protected $uri = null;

    /**
     *
     * @var string Regular expression for matching
     */
    protected $routeRegexp;

    /**
     *
     * @var string Parameter format constraint (regular expression)
     */
    protected $paramsConstraints = array();

    /**
     *
     * @var array Default params
     */
    protected $params = array();

    /**
     * Route constructor.
     * @param Container $container
     * @param array $params
     * @param array $paramsConstraints
     */
    public function __construct(Container $container, array $params = array(), array $paramsConstraints = array()) {
        if (array_key_exists('uri', $params)) {
            $this->uri = $params['uri'];
            $this->routeRegexp = $this->buildRouteRegexp($this->uri);
            unset($params['uri']);
        }

        $params['class'] = "\controllers\\" . $params['class'];
        $this->params = $params;
        $this->paramsConstraints = $paramsConstraints;
        $this->container = $container;
    }

    /**
     * Match route against given URI
     *
     * @param string URI
     * @return boolean|array Array with params or false
     */
    public function match($uri) {
        if ($this->uri === null) {
            return false;
        }

        // Check http verb
        if (array_key_exists('requestMethod', $this->params)) {
            $request = $this->container->make('\Core\Request');
            if (strtolower($request->getMethod()) != strtolower($this->params['requestMethod'])) {
                return false;
            }
        }

        $uriTrimmed = trim($uri, '/');

        $matches = array();
        if (!preg_match($this->routeRegexp, $uriTrimmed, $matches)) {
            return false;
        }

        $params = array();
        foreach ($matches as $key => $value) {
            if (is_int($key)) {
                continue;
            }

            $params[$key] = $value;
        }

        foreach ($this->params as $key => $value) {
            if (!array_key_exists($key, $params)) {
                $params[$key] = $value;
            }
        }

        return $params;
    }

    /**
     * Return response
     * @return Response Response
     */
    public function getResponse($params = array()) {
        return $this->container->makeAndInvoke(
            $this->params['class'],
            $this->params['method'],
            array_merge($this->params, $params)
        );
    }

    /**
     * Returns route params (controller, action ...)
     *
     * @return array
     */
    public function getParams() {
        return $this->params;
    }

    /**
     * Create URI for given parameters
     *
     * @param array Params
     * @return string URI
     * @throws \Exception
     */
    public function createUri(array $params = NULL) {
        $defaults = $this->params;

        $compile = function ($portion, $required) use (&$compile, $defaults, $params) {
            $missing = array();

            $pattern = '#(?:' . Route::REGEX_KEY . '|' . Route::REGEX_GROUP . ')#';
            $result = preg_replace_callback($pattern, function ($matches) use (&$compile, $defaults, &$missing, $params, &$required) {
                if ($matches[0][0] === '<') {
                    $param = $matches[1];

                    if (isset($params[$param])) {
                        $required = ($required OR !isset($defaults[$param]) OR $params[$param] !== $defaults[$param]);
                        return $params[$param];
                    }

                    // Add default parameter to this result
                    if (isset($defaults[$param])) {
                        return $defaults[$param];
                    }

                    $missing[] = $param;
                } else {
                    $result = $compile($matches[2], FALSE);

                    if ($result[1]) {
                        $required = TRUE;

                        return $result[0];
                    }
                }
            }, $portion);

            if ($required && $missing) {
                throw new \Exception('Route: Required route parameter not passed: ' . reset($missing));
            }

            return array($result, $required);
        };

        list($uri) = $compile($this->uri, TRUE);

        $uri = preg_replace('#//+#', '/', rtrim($uri, '/'));

        return $uri;
    }

    const REGEX_GROUP = '\(((?:(?>[^()]+)|(?R))*)\)';
    const REGEX_KEY = '<([a-zA-Z0-9_]++)>';
    const REGEX_SEGMENT = '[^/.,;?\n]++';
    const REGEX_ESCAPE = '[.\\+*?[^\\]${}=!|]';

    /**
     * Build regular expression for matching route from given URI
     * @param type $uri
     * @return type
     */
    protected function buildRouteRegexp($uri) {
        $expression = preg_replace('#' . Route::REGEX_ESCAPE . '#', '\\\\$0', $uri);

        if (strpos($expression, '(') !== FALSE) {
            $expression = str_replace(array('(', ')'), array('(?:', ')?'), $expression);
        }

        $regexp = str_replace(array('<', '>'), array('(?P<', '>' . Route::REGEX_SEGMENT . ')'), $expression);

        if (!empty($this->paramsConstraints)) {
            $search = $replace = array();
            foreach ($this->paramsConstraints as $key => $value) {
                $search[] = "<$key>" . Route::REGEX_SEGMENT;
                $replace[] = "<$key>$value";
            }

            $regexp = str_replace($search, $replace, $regexp);
        }

        return '#^' . $regexp . '$#uD';
    }

}
