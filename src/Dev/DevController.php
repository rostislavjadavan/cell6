<?php

namespace Dev;

class DevController extends \System\MVC\Controller {	
	
	private $db;
	
	public function __construct(\Database\DB $db) {
		$this->db = $db;
	}
	
	public function homepage($page = 0) {
		echo 'HOMEPAGE '.$page;
	}
	
	public function error404() {
		echo '404 PAGE NOT FOUND';
	}
	
	public function error500() {
		echo '500 INTERNAL SERVER ERROR';
	}
	
	public function gotologin() {
		echo 'GOTO LOGIN';
	}
	
	public function login() {
		echo 'LOGIN';
	}
	
	public function view() {		
		//$out = $this->db->executeQuery("SELECT * FROM users")->fetchCollection(new \Dev\UserModel());
		$out = $this->db->executeQuery("SELECT * FROM users WHERE id = ?", 2)->fetchObject('\Dev\UserModel');
		
		$view = \System\MVC\View::load('\Dev\views\test');
		$view->setParam('dbtest', print_r($out, true));
		return \System\Core\Container::build('\System\Http\HtmlResponse', array('content' => $view->render()));
	}
}