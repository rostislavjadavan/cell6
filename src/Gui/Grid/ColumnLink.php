<?php

/**
 * Column Link
 *
 * @author spool
 */

namespace Gui\Grid;

class ColumnLink extends ColumnButton {
	
	protected $class = '';
	protected $link = "#";

	public function setLink($link) {
		$this->link = $link;
		return $this;
	}
	
	public function render($row = array()) {
		return '<a id="'.$this->parsePattern($this->id, $row).'" href="'.$this->parsePattern($this->link, $row).'" class="'.$this->class.'">'.$this->parsePattern($this->text, $row).'</a>';
	}
}