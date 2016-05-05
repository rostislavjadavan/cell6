<?php

/**
 * Column Button
 *
 * @author spool
 */

namespace Gui\Grid;

class ColumnButton extends ColumnText implements ColumnInterface {

	const BTN_DEFAULT = "btn btn-default";
	const BTN_PRIMARY = "btn btn-primary";
	const BTN_SUCCESS = "btn btn-success";
	const BTN_INFO = "btn btn-info";
	const BTN_WARNING = "btn btn-warning";
	const BTN_DANGER = "btn btn-danger";

	protected $id = "";
	protected $class = self::BTN_DEFAULT;

	public function setId($id) {
		$this->id = $id;
		return $this;
	}

	public function setClass($class) {
		$this->class = $class;
		return $this;
	}

	public function render($row = array()) {
		return $this->parsePattern($this->buildPattern(), $row);
	}

	private function buildPattern() {
		return '<button id="' . $this->id . '" type="button" class="' . $this->class . '">' . $this->text . '</button>';
	}

}
