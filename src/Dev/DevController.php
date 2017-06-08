<?php

namespace Dev;

class DevController extends \System\MVC\Controller {

	private $db;
	private $request;
	private $session;

	public function __construct(\Database\DB $db, \System\Http\Request $request, \System\Http\Session $session) {
		$this->db = $db;
		$this->request = $request;
		$this->session = $session;
	}

	public function homepage($page = 1) {
		$view = \System\MVC\View::load('\Dev\views\homepage');
		$view->setParam('title', "Homepage");
		$view->setParam('content', "Content of the page ".$page);
		return \System\Core\Container::build('\System\Http\HtmlResponse', array('content' => $view->render()));
	}

	public function error404() {
		echo '404 PAGE NOT FOUND';
	}

	public function error500() {
		echo '500 INTERNAL SERVER ERROR';
	}

	public function database() {
		$out[] = $this->db->select('users')->fetchAll();
		/*
		$out[] = $this->db->select('users')->fetchClasses('\Dev\UserModel');
		$out[] = $this->db->select('users')->where('id', 2)->fetchClass('\Dev\UserModel');
		
		$this->db->update('users')->where('id >', 2)->values(array(
			'status' => 1,
			'kokot' => 'NEJVETSI'
		))->exec();
		
		$this->db->update('users')->where('id', 2)->values(array(
			'status' => 0,
			'kokot' => 'NEJVETSI'
		))->exec();
		
		$this->db->insert('users')->values(array(
			'email' => 'user@pohon.cz',
			'status' => 1,
			'aaa' => 1,
			'id' => 5
		))->exec();
		
		$this->db->delete('users')->where('email', 'user@pohon.cz')->exec();
		*/
		$out[] = $this->db->getQueryLog();
		
		return '<pre>'.print_r($out, true).'</pre>';		
	}

}
