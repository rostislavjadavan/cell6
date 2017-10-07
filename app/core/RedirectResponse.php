<?php

namespace Core;

/**
 * Class RedirectResponse
 * @package Core
 */
class RedirectResponse extends Response {

    /**
     * @var string Target URL for redirect
     */
    private $url = '';

    /**
     * RedirectResponse constructor.
     * @param Container $container
     * @param string $url
     * @param int $code
     */
    public function __construct(Container $container, $url, $code = 301) {
        $this->container = $container;
        $this->url = $url;
        $this->setCode($code);
    }

    /**
     * Set target URL
     *
     * @param string URL
     */
    public function setUrl($url) {
        $this->url = $url;
    }

    /**
     * Get target URL
     *
     * @return string URL
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * Send HTTP headers
     */
    public function sendHeaders() {
        header($this->protocol . ' ' . $this->code . ' ' . $this->text);
        header('Content-Type: text/html');
        header('Location: ' . $this->getUrl());
    }

}
