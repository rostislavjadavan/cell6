<?php

namespace Pure\Controller;

class DevController {
	
	public function index() {
		$view = \System\MVC\View::load('\Pure\templates\main');
		$view->setParam('pageTitle', 'Yahoo Pure');
		$view->setParam('baseUrl', \System\Core\Container::get('request')->getBaseUrl());
		return \System\Core\Container::build('\System\Http\HtmlResponse', array('content' => $view->render()));
	}
}

