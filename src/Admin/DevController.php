<?php

namespace Admin;

class DevController extends \System\MVC\Controller {
	
	public function index() {
		$data = array('pageTitle' => 'Admin');
		return $this->template('\Admin\views\dev', '\Admin\templates\main', $data);
	}
}

