<?php

/**
 * Grid
 *
 * @author spool
 */

namespace Gui;

class Grid {
	protected $columns = array();
	
	function __construct() {
		
	}
	
	public function add($column) {
		$this->columns[$column->getId()] = $column;
	}
} 