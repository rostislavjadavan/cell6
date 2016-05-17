<?php

/**
 * DB
 *
 * @author spool
 */

namespace Database;

class DB {

	/**
	 * PDO handle
	 * 
	 * @var PDO
	 */
	private $pdo = null;

	/**
	 * Table info
	 * @var TableInfo 
	 */
	private $tableInfo = null;

	/**
	 * Query log
	 * 
	 * @var type 
	 */
	protected $queryLog = array();

	/**
	 * Create database connection
	 * 
	 * @param type $dsn
	 * @param type $username
	 * @param type $password
	 * @param type $charset
	 * @throws \Exception
	 */
	public function __construct($dsn, $username, $password, $charset = 'utf8') {
		try {
			$this->pdo = new \PDO($dsn, $username, $password);
			$this->pdo->query('SET NAMES ' . $charset);
			$this->tableInfo = new TableInfo($this);
		} catch (\PDOException $e) {
			throw new \Exception("Cannot connect to database: {$e->getMessage()}");
		}
	}

	/**
	 * Return PDO handle
	 * 
	 * @return PDO
	 */
	public function getPDOConnection() {
		return $this->pdo;
	}

	/**
	 * Return Table Info
	 * 
	 * @return TableInfo
	 */
	public function getTableInfo() {
		return $this->tableInfo;
	}

	/**
	 * Get extended information about last error
	 * 
	 * @return type
	 */
	public function getLastError() {
		return $this->pdo->errorInfo();
	}

	/**
	 * Run native PDO query
	 * 
	 * @param type $statement
	 * @return type
	 */
	public function query($statement) {
		$this->logQuery($statement);
		return $this->pdo->query($statement);
	}

	/**
	 * Run native PDO query and return number of affected rows
	 * 
	 * @param type $statement
	 * @return type
	 */
	public function execute($statement) {
		$this->logQuery($statement);
		return $this->pdo->exec($statement);
	}

	/**
	 * Select statement
	 * 
	 * @param string Table name
	 * @return \Query\Select 
	 */
	public function select($table) {
		return new \Database\Query\Select($table, $this);
	}

	/**
	 * Insert statement
	 * 
	 * @param string Table name
	 * @return \Query\Insert 
	 */
	public function insert($table) {
		return new \Database\Query\Insert($table, $this);
	}

	/**
	 * Update statement
	 * 
	 * @param string Table name
	 * @return \Query\Update 
	 */
	public function update($table) {
		return new \Database\Query\Update($table, $this);
	}

	/**
	 * Delete statement
	 * 
	 * @param string Table name
	 * @return \Database\Query\Delete
	 */
	public function delete($table) {
		return new \Database\Query\Delete($table, $this);
	}

	/**
	 * Quote string
	 * 
	 * @param type $string
	 * @param type $parameterType
	 * @return type
	 */
	public function quote($string, $parameterType = \PDO::PARAM_STR) {
		return $this->pdo->quote($string, $parameterType);
	}

	/**
	 * Log statement
	 * 
	 * @param type $statement
	 */
	private function logQuery($statement) {
		$this->queryLog[date('H:i:m Y-m-d') . '_' . rand(1111, 9999)] = $statement;
	}

	/**
	 * List of executted queries
	 * 
	 * @return type
	 */
	public function getQueryLog() {
		return $this->queryLog;
	}

}
