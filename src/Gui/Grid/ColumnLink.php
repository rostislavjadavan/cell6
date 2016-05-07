<?php

/**
 * Column Link
 *
 * @author spool
 */

namespace Gui\Grid;

class ColumnLink extends ColumnButton implements ColumnInterface {

	protected $class = '';
	protected $link = "#";
	protected $searchable = false;

	public function setLink($link) {
		$this->link = $link;
		return $this;
	}

	public function render($row = array()) {
		return $this->parsePattern($this->buildPattern(), $row);
	}

	private function buildPattern() {
		return '<a id="' . $this->id . '" href="' . $this->link . '" class="' . $this->class . '">' . $this->text . '</a>';
	}

}
