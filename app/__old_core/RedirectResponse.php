<?php

/**
 * Redirect Response
 *
 * @package Http
 * @author spool
 */

namespace Core;

class RedirectResponse extends Response {

	/**
	 * @var string Target URL for redirect
	 */
	private $url = '';

	/**
	 * Init
	 *
	 * @param string Target URL
	 * @param int Response code
	 */
	public function __construct($url, $code = 301) {
		$this->setCode($code);
		$this->url = $url;
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
