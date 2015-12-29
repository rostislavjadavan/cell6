<?php

/**
 * ArrayList
 *
 * @author spool
 */

namespace System\Core;

class ArrayList implements \ArrayAccess, \Countable {

	/**
	 * @var array Data (key, value)
	 */
	private $data = array();

	/**
	 * Init
	 * 
	 * @param array Initial data
	 */
	public function __construct($data = array()) {
		$this->data = $data;
	}

	/**
	 * Return all data
	 *
	 * @return array Data
	 */
	public function getAll() {
		return $this->data;
	}

	/**
	 * Add value to list
	 * 
	 * @param mixed Value
	 */
	public function add($value) {
		$this->data[] = $value;
	}

	/**
	 * Get value of given key
	 *
	 * @param string Key
	 * @return mixed Value
	 */
	public function get($key) {
		if ($this->offsetExists($key)) {
			return $this->data[$key];
		}
		else
			throw new \Exception("Key $key doesn't exists.");
	}

	/**
	 * Set value
	 *
	 * @param string Key
	 * @param mixed Value
	 */
	public function set($key, $value) {
		$this->data[$key] = $value;
	}

	/**
	 * Unset value
	 *
	 * @param string Key
	 */
	public function delete($key) {
		unset($this->data[$key]);
	}

	/**
	 * Check if key is in list
	 *
	 * @param string Key
	 * @return bool TRUE if key is in list
	 */
	public function is($key) {
		return array_key_exists($key, $this->data);
	}

	/**
	 * Check if list is empty
	 *
	 * @return bool TRUE if list is empty
	 */
	public function isEmpty() {
		return empty($this->data);
	}
	
	/**
	 * Clear all data	 
	 */
	public function clear() {
		$this->data = array();
	}

	/**
	 * Return number of elements in list
	 *
	 * @return int Number of elements
	 */
	public function count() {
		return count($this->data);
	}

	/**
	 * Check if offset is in list
	 *
	 * @param string Key
	 * @return bool TRUE if key is in list
	 */
	public function offsetExists($offset) {
		return array_key_exists($offset, $this->data);
	}

	/**
	 * Get value of given key
	 *
	 * @param string Key
	 * @return mixed Value
	 */
	public function offsetGet($offset) {
		return $this->data[$offset];
	}

	/**
	 * Set value
	 *
	 * @param string Key
	 * @param mixed Value
	 */
	public function offsetSet($offset, $value) {
		$this->data[$offset] = $value;
	}

	/**
	 * Unset value
	 *
	 * @param string Key	 
	 */
	public function offsetUnset($offset) {
		unset($this->data[$offset]);
	}

	/**
	 * Get value of given key
	 *
	 * @param string Key
	 * @return mixed Value
	 */
	public function __get($name) {
		return $this->data[$name];
	}

	/**
	 * Set value
	 *
	 * @param string Key
	 * @param mixed Value
	 */
	public function __set($name, $value) {
		$this->data[$name] = $value;
	}

	/**
	 * Check if key of given name is in list
	 *
	 * @param string Key
	 * @return bool TRUE if key is in list
	 */
	public function __isset($name) {
		return array_key_exists($name, $this->data);
	}

}