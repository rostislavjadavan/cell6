<?php

/**
 * HTML Response
 *
 * @package Http
 * @author spool
 */

namespace System\Http;

class HtmlResponse extends Response {

	/**
	 * Send HTTP headers
	 */
	public function sendHeaders() {
		header($this->protocol . ' ' . $this->code . ' ' . $this->text);
		header('Content-Type: text/html');
	}

}
