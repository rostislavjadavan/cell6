<?php

/**
 * DataSource Array
 *
 * @author spool
 */

namespace Gui;

class DataSourceArray {
	protected $data = array();

	public function __construct(array $data) {
		$this->data = $data;
	}
	
	public function getAll() {
		return $this->data;
	}
	
	public function getCount() {
		return count($this->data);
	}
	
	public function getPage($page, $perPage = 10) {
		return array_slice($this->data, ($page - 1) * $perPage, $perPage);
	}
}