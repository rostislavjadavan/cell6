<?php

namespace Admin;

class DevController extends \System\MVC\Controller {
	
	protected $db;
	
	function __construct(\Database\DB $db) {
		$this->db = $db;
	}

	
	public function index() {
		$gridData = $this->db->select('users')->fetchAll();
		
		$grid = new \Gui\Grid();
		$grid->add(new \Gui\Grid\ColumnButton('', 'Edit'))->setId('edit-btn-{id}');
		$grid->add(new \Gui\Grid\ColumnLink('', 'Preview link'))->setLink('http://localhost/{id}');
		$grid->add(new \Gui\Grid\ColumnText('ID', '{id}'));
		$grid->add(new \Gui\Grid\ColumnText('User', '{username} ({email})'));
		
		$data = array('pageTitle' => 'Dev', 'out' => $grid->render($gridData));
		return $this->template('\Admin\views\dev', '\Admin\templates\main', $data);
	}
}

