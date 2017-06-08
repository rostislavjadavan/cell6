<?php

namespace Gui;

class Form {
	protected $uid = null;
	protected $data = null;
	protected $template = 'Gui\Form\view';
	protected $controls = array();
	protected $buttons = array();

	public function __construct($uid) {
		$this->uid = $uid;
	}

	public function add($formControl) {
		if ($formControl instanceof \Gui\Form\Button) {
			$this->buttons[] = $formControl;
		} else {
			$this->controls[] = $formControl;
		}
		return $formControl;
	}

	public function render() {
		$view = \System\MVC\View::load($this->template);
		$view->setParams(array(
			'uid' => $this->uid,
			'controls' => $this->controls,
			'buttons' => $this->buttons
		));
		return $view->render();
	}
}
