<?php

/**
 * Column Text
 *
 * @author spool
 */

namespace Gui\Grid;

class ColumnButton extends ColumnText {

	const BTN_DEFAULT = "btn btn-default";
	const BTN_PRIMARY = "btn btn-primary";
	const BTN_SUCCESS = "btn btn-success";
	const BTN_INFO = "btn btn-info";
	const BTN_WARNING = "btn btn-warning";
	const BTN_DANGER = "btn btn-danger";
	
	protected $link = "";
	protected $class = BTN_DEFAULT;

	public function setLink($link) {
		$this->link = $link;
		return $this;
	}

	public function setClass($class) {
		$this->class = $class;
		return $this;
	}
	
	public function render($row = array()) {
		return '<button id="'.$this->id.'" type="button" class="'.$this->class.'">'.$this->parsePattern($this->text, $row).'</button>';
	}
}