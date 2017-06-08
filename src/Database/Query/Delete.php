<?php

/**
 * Delete Query
 *
 */

namespace Database\Query;

class Delete extends Where {

	public function build() {
		// Delete
		$sql = "DELETE FROM `{$this->table}`";

		// Where
		$sql.= parent::build();

		return $sql;
	}

	public function exec() {
		return ($this->db->execute($this->build()) == 1);
	}

}
