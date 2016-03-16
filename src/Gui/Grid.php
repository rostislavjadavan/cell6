<?php

/**
 * Grid
 *
 * @author spool
 */

namespace Gui;

class Grid {
	protected $templateName = 'Gui\Grid\views\default';
	protected $columns = array();
	
	public function add($column) {
		$this->columns[] = $column;
		return $column;
	}
	
	public function render(array $data) {
		$template = \System\MVC\View::load($this->templateName, array(
			'columns' => $this->columns,
			'data' => $data
		));
		return $template->render();
	}
} 