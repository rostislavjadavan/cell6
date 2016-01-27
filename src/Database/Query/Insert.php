<?php

/**
 * Insert Query
 *
 */

namespace Database\Query;

class Insert {

	const MODE_RAW = 0;
	const MODE_SAFE = 2;
	const MODE_IGNORE_PRIMARY_KEY = 4;

	protected $db = null;
	protected $table = null;
	protected $values = array();

	public function __construct($table, \Database\DB $db) {
		$this->table = $table;
		$this->db = $db;
	}

	public function value($key, $value) {
		$this->values[$key] = $value;
		return $this;
	}

	public function values(array $values) {
		$this->values = array_merge($this->values, $values);
		return $this;
	}

	public function build($mode = null) {
		// Default mode
		if (is_null($mode)) {
			$mode = Insert::MODE_IGNORE_PRIMARY_KEY | Insert::MODE_SAFE;
		}

		// Insert
		$sql = "INSERT INTO `{$this->table}` ";

		// Prepare columns and values
		$columns = array();
		$values = array();

		foreach ($this->values as $column => $value) {
			$skipColumn = false;

			// Check if this column is primary key
			if ($mode & Insert::MODE_IGNORE_PRIMARY_KEY && $column == $this->db->getTableInfo()->getPrimaryKey($this->table)) {
				$skipColumn = true;
			}
			// Check if this column is in the target table
			if ($mode & Insert::MODE_SAFE && !in_array($column, $this->db->getTableInfo()->getColumns($this->table))) {
				$skipColumn = true;
			}

			// Quote column and value
			if (!$skipColumn) {
				$columns[] = "`$column`";
				$values[] = $this->db->quote($value);
			}
		}

		// Columns
		$sql.= '(' . implode(',', $columns) . ') ';

		// Values
		$sql.= 'VALUES (' . implode(',', $values) . ')';

		return $sql;
	}

	public function __toString() {
		return $this->build();
	}

	public function exec($mode = null) {
		return ($this->db->execute($this->build($mode)) == 1);
	}

}
