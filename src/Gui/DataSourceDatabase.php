<?php

/**
 * DataSource Database
 *
 * @author spool
 */

namespace Gui;

class DataSourceDatabase {

	protected $select = null;

	public function __construct(\Database\Query\Select $select) {
		$this->select = $select;
	}

	public function getAll() {
		return $this->select->fetchAll();
	}

	public function getCount() {
		$tmpSelect = $this->select;
		return $tmpSelect->fetchCount();
	}

	public function getPage($page, $perPage = 10) {
		$tmpSelect = $this->select;
		return $tmpSelect
						->offset(($page - 1) * $perPage)
						->limit($perPage)
						->fetchAll();
	}

}
