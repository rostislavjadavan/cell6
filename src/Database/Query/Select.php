<?php

/**
 * Select Query
 *
 */

namespace Database\Query;

class Select extends Where {

	protected $columns = array();
	protected $count = null;

	public function columns(array $columns) {
		$this->columns = array_merge($this->columns, $columns);
		return $this;
	}

	public function column($column) {
		$this->columns[] = $column;
	}

	public function count($alias = 'count') {
		$this->count = $alias;
		return $this;
	}

	public function build() {
		// Select
		$sql = 'SELECT ';

		// Count Open
		if (!is_null($this->count)) {
			$sql.= 'COUNT(';
		}

		// Columns
		if (empty($this->columns)) {
			$sql.= '*';
		} else {
			$columns = array();
			foreach ($this->columns as $column) {
				$columns[] = "`$column`";
			}
			$sql.= implode(',', $columns);
		}

		// Count Close
		if (!is_null($this->count)) {
			$sql.= ") AS `{$this->count}`";
		}

		// Table
		$sql.= " FROM `{$this->table}`";

		// Where
		return $sql . ' ' . parent::build();
	}

	public function fetch($mode = \PDO::FETCH_ASSOC) {
		$sql = $this->build();
		$statement = $this->db->query($sql);
		if (!$statement) {
			throw new \Exception('QUERY: Fetch error: ' . print_r($this->db->errorInfo(), true) . " Sql: $sql");
		}

		return $statement->fetch($mode);
	}

	public function fetchAll($mode = \PDO::FETCH_ASSOC) {
		$sql = $this->build();
		$statement = $this->db->query($sql);
		if (!$statement) {
			throw new \Exception('QUERY: FetchAll error: ' . print_r($this->db->getLastError(), true) . " Sql: $sql");
		}

		return $statement->fetchAll($mode);
	}

	public function fetchClass($className) {
		$sql = $this->build();
		$statement = $this->db->query($sql);
		if (!$statement) {
			throw new \Exception('QUERY: FetchModels error: ' . print_r($this->db->getLastError(), true) . " Sql: $sql");
		}
		$statement->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $className);
		return $statement->fetch();
	}

	public function fetchClasses($className) {
		$sql = $this->build();
		$statement = $this->db->query($sql);
		if (!$statement) {
			throw new \Exception('QUERY: FetchModels error: ' . print_r($this->db->getLastError(), true) . " Sql: $sql");
		}

		return $statement->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $className);
	}
	
	public function fetchCount($alias = 'count') {
		$this->count($alias);
		return $this->fetch()[$alias];
	}

	public function __toString() {
		return $this->build();
	}

	public function resetColumns() {
		$this->columns = array();
		return $this;
	}

	public function resetCount() {
		$this->count = null;
		return $this;
	}

}
