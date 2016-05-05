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
	
	public function grid() {		
				
		$grid = new \Gui\Grid('users', new \Gui\Data\DataSourceDatabase($this->db->select('users')));
		$grid->add(new \Gui\Grid\ColumnButton('', 'Edit'))->setId('edit-btn-{id}');		
		$grid->add(new \Gui\Grid\ColumnText('ID', '{id}'));
		$grid->add(new \Gui\Grid\ColumnText('User', '{member_nick} ({email})'));
				
		$grid2 = new \Gui\Grid('shop_orders', new \Gui\Data\DataSourceDatabase($this->db->select('shop_orders')));
		$grid2->add(new \Gui\Grid\ColumnButton('', 'Edit'))->setId('edit-btn-{id}');		
		$grid2->add(new \Gui\Grid\ColumnText('ID', '{id}'));
		$grid2->add(new \Gui\Grid\ColumnText('Number', '{number}'));
		
		$data = array(
			'pageTitle' => 'Dev',
			'out' => $grid->render(),
			'out2' => $grid2->render()
		);
		
		return $this->template('\Admin\views\dev', '\Admin\templates\main', $data);
	}
}

