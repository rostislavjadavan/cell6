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

	protected function parsePattern($pattern, $data) {
		$dict = array();
		foreach ($data as $name => $value) {
			$dict['{' . $name . '}'] = $value;
		}
		return strtr($pattern, $dict);
	}

}
