<?php

/**
 * Column Text
 *
 * @author spool
 */

namespace Gui\Grid;

class ColumnText {
	protected $id = null;
	protected $text = '';
	
	public function __construct($id, $text) {
		$this->id = $id;
		$this->text = $text;
	}

	public function render($row = array()) {
		return $this->parsePattern($this->text, $row);
	}
	
	protected function parsePattern($pattern, $data) {
		$dict = array();
		foreach ($data as $name => $value) {
			$dict['{'.$name.'}'] = $value;
		}
		return strtr($pattern, $dict);
	}
}