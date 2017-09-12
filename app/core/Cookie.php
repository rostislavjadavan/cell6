<?php

/**
 * Cookie
 *
 * @author spool
 */

namespace Core;

class Cookie {

	/**
	 * @var string Name
	 */
	private $name = NULL;

	/**
	 * @var string Value
	 */
	private $value = NULL;

	/**
	 * @var int Expire time
	 */
	private $expire = 0;

	/**
	 * @var string Parg
	 */
	private $path = '/';

	/**
	 * Init
	 * 
	 * @param string Name
	 * @param string Value
	 */
	public function __construct($name, $value) {
		$this->name = $name;
		$this->value = $value;
	}

	/**
	 * Get name
	 * 
	 * @return string Name
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Get value
	 * 
	 * @return string Value
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * Set value
	 * 
	 * @param string Value
     * @return $this
	 */
	public function setValue($value) {
		$this->value = $value;
		return $this;
	}

	/**
	 * Get path
	 * 
	 * @return string Path
	 */
	public function getPath() {
		return $this->path;
	}

	/**
	 * Set path
	 * 
	 * @param string Path
     * @return $this
	 */
	public function setPath($path) {
		$this->path = $path;
		return $this;
	}

	/**
	 * Get expire time
	 * 
	 * @return int Expire time
	 */
	public function getExpire() {
		return $this->expire;
	}

	/**
	 * Set expire time
	 * @param int Expire time
     * @return $this
	 */
	public function setExpire($expire) {
		$this->expire = time() + $expire;
		return $this;
	}

	/**
	 * Create cookie
	 * 
	 * @return bool TRUE on success
	 */
	public function create() {
		return setcookie($this->getName(), $this->getValue(), $this->getExpire(), $this->getPath());
	}

}
