<?php

/**
 * Table Info
 *
 * @author spool
 */

namespace Database;

class TableInfo {

	/**
	 * @var array Cache of already inspected tables
	 */
	protected static $columnCache = array();
	protected static $primaryKeysCache = array();

	/**
	 * Database
	 * @var DB
	 */
	protected $db = null;

	/**
	 * Construct
	 * 
	 * @param \PDO $connection
	 */
	function __construct(\Database\DB $db) {
		$this->db = $db;
	}

	/**
	 * Get column names as array
	 *
	 * @return array List of column names
	 */
	public function getColumns($tableName) {
		$columns = array();

		foreach ($this->getColumnsInfo($tableName) as $columnInfo) {
			$columns[] = $columnInfo['Field'];
		}

		return $columns;
	}

	/**
	 * Get columns info
	 *
	 * @param bool TRUE to retrieve full info
	 * @return array List of columns info
	 */
	public function getColumnsInfo($tableName, $full = false) {
		// First look into the cache
		if (array_key_exists($tableName, self::$columnCache)) {
			return self::$columnCache[$tableName];
		}

		// Set full statement
		$fullStatement = '';
		if ($full) {
			$fullStatement = 'FULL';
		}

		// Get columns		
		$statement = $this->db->query('SHOW ' . $fullStatement . ' COLUMNS FROM `' . $tableName . '`');
		if (!$statement) {
			throw new \Exception("TABLEINFO: Table '{$tableName}' does not exists.");
		}
		$columns = $statement->fetchAll(\PDO::FETCH_ASSOC);

		// Add to cache
		self::$columnCache[$tableName] = $columns;

		// Return columns
		return $columns;
	}

	/**
	 * Find primary key name
	 * 
	 * @return string Primary key name
	 * @throws \Exception 
	 */
	public function getPrimaryKey($tableName) {
		// First look into the cache
		if (array_key_exists($tableName, self::$primaryKeysCache)) {
			return self::$primaryKeysCache[$tableName];
		}

		// Find primary key
		foreach ($this->getColumnsInfo($tableName) as $column) {
			if (array_key_exists('Key', $column) && strtoupper($column['Key']) == 'PRI') {
				$primaryKey = $column['Field'];

				self::$primaryKeysCache[$tableName] = $primaryKey;
				return $primaryKey;
			}
		}

		throw new \Exception('TABLEINFO: Unable to find primary key.');
	}

}
