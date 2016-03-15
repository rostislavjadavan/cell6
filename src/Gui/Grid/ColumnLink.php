<?php

/**
 * Column Link
 *
 * @author spool
 */

namespace Gui\Grid;

class ColumnLink extends ColumnText {
	
	protected $link = "";
	protected $class = "";

	public function setLink($link) {
		$this->link = $link;
		return $this;
	}

	public function setClass($class) {
		$this->class = $class;
		return $this;
	}
	
	public function render($row = array()) {
		return '<a id="'.$this->id.'" href="'.$this->link.'" class="'.$this->class.'">'.$this->parsePattern($this->text, $row).'</a>';
	}
}