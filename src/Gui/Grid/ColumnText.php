<?php

/**
 * Column Text
 *
 * @author spool
 */

namespace Gui\Grid;

class ColumnText implements ColumnInterface {

	protected $title = '';
	protected $text = '';
	protected $searchable = true;

	public function __construct($title, $text) {
		$this->title = $title;
		$this->text = $text;
	}

	public function getTitle() {
		return $this->title;
	}

	public function render($row = array()) {
		return $this->parsePattern($this->text, $row);
	}
	
	function isSearchable() {
		return $this->searchable;
	}

	function setSearchable($searchable) {
		$this->searchable = $searchable;
	}
	
	protected function parsePattern($pattern, $data) {
		$dict = array();
		foreach ($data as $name => $value) {
			$dict['{' . $name . '}'] = $value;
		}
		return strtr($pattern, $dict);
	}

}
