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
    protected $paramsConstraints = [];

    /**
     *
     * @var array Default params
     */
    protected $params = [];

    /**
     * Route constructor.
     * @param Container $container
     * @param array $params
     * @param array $paramsConstraints
     */
    public function __construct(Container $container, array $params = [], array $paramsConstraints = []) {
        if (array_key_exists('uri', $params)) {
            $this->uri = ltrim($params['uri'], "/");
            $this->routeRegexp = $this->buildRouteRegexp($this->uri);
            unset($params['uri']);
        }

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

        $matches = [];
        if (!preg_match($this->routeRegexp, $uriTrimmed, $matches)) {
            return false;
        }

        $params = [];
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
     * @param array $params
     * @return Response Response
     * @throws RuntimeException
     */
    public function getResponse($params = []) {
        if (array_key_exists('class', $this->params)) {
            $targetParts = explode('::', $this->params['class']);
            if (count($targetParts) < 2) {
                throw new RuntimeException("Invalid route target: " . $this->params['class']);
            }
            return $this->container->makeAndInvoke('\controllers\\' . $targetParts[0], $targetParts[1], array_merge($this->params, $params));
        } else if (array_key_exists('view', $this->params)) {
            $view = View::load($this->container, $this->params['view']);
            return $this->container->make('\Core\HtmlResponse', ['content' => $view->render()]);
        } else if (array_key_exists('function', $this->params)) {
            return $this->container->invokeClosure($this->params['function'], array_merge($this->params, $params));
        }
        throw new RuntimeException("No target defined for route " . $this->uri);
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
    public function uri(array $params = null) {
        $defaults = $this->params;

        $compile = function ($portion, $required) use (&$compile, $defaults, $params) {
            $missing = [];

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
                    $result = $compile($matches[2], false);

                    if ($result[1]) {
                        $required = true;

                        return $result[0];
                    }
                }
            }, $portion);

            if ($required && $missing) {
                throw new RuntimeException('Route: Required route parameter not passed \'' . reset($missing) . '\'');
            }

            return [$result, $required];
        };

        list($uri) = $compile($this->uri, true);

        $uri = preg_replace('#//+#', '/', rtrim($uri, '/'));

        return $uri;
    }

    const REGEX_GROUP = '\(((?:(?>[^()]+)|(?R))*)\)';
    const REGEX_KEY = '<([a-zA-Z0-9_]++)>';
    const REGEX_SEGMENT = '[^/,;?\n]++';
    const REGEX_ESCAPE = '[.\\+*?[^\\]${}=!|]';

    /**
     * Build regular expression for matching route from given URI
     * @param type $uri
     * @return type
     */
    protected function buildRouteRegexp($uri) {
        $expression = preg_replace('#' . Route::REGEX_ESCAPE . '#', '\\\\$0', $uri);

        if (strpos($expression, '(') !== false) {
            $expression = str_replace(['(', ')'], ['(?:', ')?'], $expression);
        }

        $regexp = str_replace(['<', '>'], ['(?P<', '>' . Route::REGEX_SEGMENT . ')'], $expression);

        if (!empty($this->paramsConstraints)) {
            $search = $replace = [];
            foreach ($this->paramsConstraints as $key => $value) {
                $search[] = "<$key>" . Route::REGEX_SEGMENT;
                $replace[] = "<$key>$value";
            }

            $regexp = str_replace($search, $replace, $regexp);
        }

        return '#^' . $regexp . '$#uD';
    }

}
