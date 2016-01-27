<?php

/**
 * Session
 *
 * @package Core
 * @author spool
 */

namespace System\Http;

class Session extends \System\Core\ArrayList {

	protected $start = false;

	public function __construct() {
		if (session_status() !== \PHP_SESSION_ACTIVE) {
			if (!session_start()) {
				throw new \System\Core\RuntimeException("Failed to start session");
			}
			parent::__construct($_SESSION);
			$this->start = true;
		}
	}

	public function __destruct() {
		if ($this->start) {
			$this->save();
		}
	}

	public function save() {
		$_SESSION = $this->data;
		session_write_close();
		$this->data = null;
	}

}
