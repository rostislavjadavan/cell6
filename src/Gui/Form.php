<?php

namespace Gui;

class Form {
	protected $uid = null;
	protected $data = null;
	protected $template = 'Gui\Form\view';
	protected $controls = array();

	public function __construct($uid) {
		$this->uid = $uid;
	}

	public function add($formControl) {
		$this->controls[] = $formControl;
	}

	public function render() {
		$view = \System\MVC\View::load($this->template);
		$view->setParams(array(
			'uid' => $this->uid,
			'controls' => $this->controls
		));
		return $view->render();
	}
}
