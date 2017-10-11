<?php

/**
 * Request
 *
 * @package Http
 * @author spool
 */

namespace Core;

class Request {

    /**
     * @var ArrayList Server properties
     */
    public $server = null;

    /**
     * @var ArrayList Query params
     */
    public $query = null;

    /**
     * @var ArrayList Post params
     */
    public $post = null;

    /**
     * @var Files Post files
     */
    public $files = null;

    /**
     * @var ArrayList Cookies
     */
    public $cookies = null;

    /**
     * @var ArrayList Session
     */
    public $session = null;

    /**
     * @var string Base URL
     */
    private $baseUrl = '';

    /**
     * @var string Request path
     */
    private $path = '';

    /**
     * Request constructor.
     * @param Container $container
     */
    public function __construct(Container $container, $server, $query, $post, $files, $cookies, $session) {
        // Init
        $this->server = new ArrayList($server);
        $this->query = new ArrayList($query);
        $this->post = new ArrayList($post);
        $this->files = new ArrayList($files);
        $this->cookies = new ArrayList($cookies);
        $this->session = $session;

        // Extract rewrite base (base directory)
        $rewriteBase = '';
        if ($this->server->is('SCRIPT_NAME')) {
            foreach (explode('/', $this->server->get('SCRIPT_NAME')) as $part) {
                if (strpos($part, '.php') === false) {
                    $rewriteBase = $part;
                }
            }
        }

        // Build base url
        $this->baseUrl = ($this->isHttps() ? 'https://' : 'http://') . rtrim($this->getHost(), '/') . '/';
        if (strlen(trim($rewriteBase)) > 0) {
            $this->baseUrl .= rtrim($rewriteBase, '/') . '/';
        }

        // Get current request path
        $this->path = '';
        if ($this->query->is('url')) {
            $this->path = $this->query->get('url');
        } elseif ($this->server->is('REQUEST_URI')) {
            $uri = $this->server->get('REQUEST_URI');

            // Remove query string from REQUEST_URI
            if (strpos($uri, '?') !== false) {
                $uri = substr($uri, 0, strpos($uri, '?'));
            }

            $this->path = str_replace($rewriteBase, '', $uri);
        }
        $this->path = ltrim($this->path, '/');
    }

    /**
     * Return base URL
     *
     * @return string Base URL
     */
    public function getBaseUrl() {
        return $this->baseUrl;
    }

    /**
     * Return current request method
     *
     * @return string Method name (GET, POST ...)
     */
    public function getMethod() {
        try {
            return $this->server->get('REQUEST_METHOD');
        } catch (\Exception $e) {
            return 'GET';
        }
    }

    /**
     * Return protocol
     *
     * @return string Protocol
     */
    public function getProtocol() {
        return ($this->server->is('SERVER_PROTOCOL') ? $this->server->get('SERVER_PROTOCOL') : 'HTTP/1.0');
    }

    /**
     * Return host
     *
     * @return string Host
     */
    public function getHost() {
        return !$this->server->is('HTTP_HOST') ? false : $this->server->get('HTTP_HOST');
    }

    /**
     * Check if request method is GET
     *
     * @return bool true if it is GET
     */
    public function isGet() {
        return ($this->getMethod() == 'GET');
    }

    /**
     * Get query value(s)
     * If key is null returns all query data
     *
     * @param string Key
     * @return mixed
     */
    public function getQuery($key = null) {
        if ($key == null) {
            return $this->query;
        }

        if ($this->query->is($key)) {
            return $this->query->get($key);
        }

        return null;
    }

    /**
     * Check if request method is POST
     *
     * @return bool true if it is POST
     */
    public function isPost() {
        return ($this->getMethod() == 'POST');
    }

    /**
     * Get post value(s)
     * If key is null returns all post data
     *
     * @param string Key
     * @return mixed
     */
    public function getPost($key = null) {
        if ($key == null) {
            return $this->post;
        }

        if ($this->post->is($key)) {
            return $this->post->get($key);
        }

        return null;
    }

    /**
     * Get cookie(s)
     *
     * @param string $name
     * @return string|array
     */
    public function getCookie($name = null) {
        if ($name == null) {
            return $this->cookies;
        }

        if ($this->cookies->is($name)) {
            return $this->cookies->get($name);
        }

        return null;
    }

    /**
     * Check if it is HTTPS
     *
     * @return bool true if it is HTTPS
     */
    public function isHttps() {
        if (!$this->server->is('HTTPS')) {
            return false;
        } else {
            // IIS specific
            return ($this->server->get('HTTPS') == 'off') ? false : true;
        }
    }

    /**
     * Check if it is Ajax request
     *
     * @return bool true if it is Ajax request
     */
    public function isAjax() {
        try {
            return ($this->server->is('HTTP_X_REQUESTED_WITH') && strtolower($this->server->get('HTTP_X_REQUESTED_WITH')) == 'xmlhttprequest');
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Return request url
     *
     * @return string url
     */
    public function getRequestUrl() {
        $base = trim($this->baseUrl, "/") . "/";
        return $base . $this->path;
    }

    /**
     * Return request path (without domain)
     *
     * @return string path
     */
    public function getRequestPath() {
        return $this->path;
    }

    /**
     * Localhost domains
     *
     * @var string
     */
    private $localhostDomainsWhitelist = array('127.0.0.1', '::1');

    /**
     * Check if system is running on localhost
     *
     * @return boolean
     */
    public function isLocalhost() {
        if (!$this->server->is('REMOTE_ADDR')) {
            return false;
        }
        return (in_array($this->server->get('REMOTE_ADDR'), $this->localhostDomainsWhitelist));
    }

}
