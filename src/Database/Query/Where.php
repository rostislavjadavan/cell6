<?php

/**
 * Where part of query
 *
 * @author spool
 */

namespace Database\Query;

abstract class Where {

	protected $db = null;
	protected $table = null;
	protected $whereParams = array();
	protected $limit = NULL;
	protected $offset = NULL;
	protected $orderBy = array();

	public function __construct($table, \Database\DB $db) {
		$this->table = $table;
		$this->db = $db;
	}

	public function where($column, $value, $op = '=') {
		$this->whereParams[] = array('' => array($column, $op, $value));
		return $this;
	}

	public function andWhere($column, $value, $op = '=') {
		$this->whereParams[] = array('AND' => array($column, $op, $value));
		return $this;
	}

	public function orWhere($column, $value, $op = '=') {
		$this->whereParams[] = array('OR' => array($column, $op, $value));
		return $this;
	}

	public function whereIn($column, array $values) {
		$this->whereParams[] = array('' => array($column, 'IN', $values));
		return $this;
	}

	public function andWhereIn($column, array $values) {
		$this->whereParams[] = array('AND' => array($column, 'IN', $values));
		return $this;
	}

	public function orWhereIn($column, array $values) {
		$this->whereParams[] = array('OR' => array($column, 'IN', $values));
		return $this;
	}

	public function BracketOpen() {
		$this->whereParams[] = '(';
		return $this;
	}

	public function orBracketOpen() {
		$this->whereParams[] = 'OR (';
		return $this;
	}

	public function andBracketOpen() {
		$this->whereParams[] = 'AND (';
		return $this;
	}

	public function BracketClose() {
		$this->whereParams[] = ')';
		return $this;
	}

	public function offset($offset) {
		$this->offset = $offset;
		return $this;
	}

	public function limit($limit) {
		$this->limit = $limit;
		return $this;
	}

	public function orderBy($column, $type) {
		if (!in_array(strtoupper($type), array('ASC', 'DESC'))) {
			throw new \Exception('QUERY: Order have to be ASC or DESC');
		}

		$this->orderBy[] = array($column, $type);
		return $this;
	}

	public function build() {
		// Where
		$sql = '';

		if (!empty($this->whereParams)) {
			$sql .= ' WHERE';

			foreach ($this->whereParams as $condition) {
				// Condition
				if (is_array($condition)) {
					$link = key($condition);
					list($column, $op, $value) = current($condition);

					// Quote column
					$column = "`$column`";

					// Quote value
					if (is_array($value)) {
						$tmpValue = array();
						foreach ($value as $val) {
							$tmpValue[] = $this->db->quote($val);
						}
						$value = '(' . implode(',', $tmpValue) . ')';
					} else {
						$value = $this->db->quote($value);
					}

					// Build where condition
					$sql.= " $link $column $op $value";
				} elseif (is_string($condition)) {
					// Bracket or plain string
					$sql.= " $condition";
				}
			}
		}

		// Order By
		if (!empty($this->orderBy)) {
			$sql.= ' ORDER BY';

			$conditions = array();
			foreach ($this->orderBy as $orderCondition) {
				list($column, $type) = $orderCondition;
				$conditions[] = "`$column` $type";
			}

			$sql.= implode(',', $conditions);
		}

		// Limit
		if (!is_null($this->limit) && is_int($this->limit)) {
			$sql.= " LIMIT {$this->limit}";
		}

		// Offset
		if (!is_null($this->offset) && is_int($this->limit)) {
			$sql.= " OFFSET {$this->offset}";
		}


		return $sql;
	}

	public function resetLimit() {
		$this->limit = NULL;
		return $this;
	}

	public function resetOffset() {
		$this->offset = NULL;
		return $this;
	}

}
