<?php

/**
 * Update Query
 *
 */

namespace Database\Query;

class Update extends Where {

	const MODE_RAW = 0;
	const MODE_SAFE = 2;

	protected $values = array();

	public function value($key, $value) {
		$this->values[$key] = $value;
		return $this;
	}

	public function values(array $values) {
		$this->values = array_merge($this->values, $values);
		return $this;
	}

	public function build($mode = null) {
		// Mode
		if (is_null($mode)) {
			$mode = Update::MODE_SAFE;
		}

		// Safe mode limit
		if ($mode == Update::MODE_SAFE && is_null($this->limit)) {
			$this->limit(1);
		}

		// Update
		$sql = "UPDATE `{$this->table}`";

		// Set
		$sql.=" SET ";

		$pairs = array();
		foreach ($this->values as $column => $value) {
			$skipColumn = false;

			// Check if field exists in the target table
			if ($mode & Update::MODE_SAFE && !in_array($column, $this->db->getTableInfo()->getColumns($this->table))) {
				$skipColumn = true;
			}

			// Quote column and value
			if (!$skipColumn) {
				$pairs[] = "`$column` = " . $this->db->quote($value);
			}
		}

		$sql.= implode(',', $pairs);

		// Where
		$sql.= " " . parent::build();

		return $sql;
	}

	public function __toString() {
		return $this->build();
	}

	public function exec($mode = null) {
		return ($this->db->execute($this->build($mode)) == 1);
	}

}
