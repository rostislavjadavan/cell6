<?php

namespace Gui;

class Form {
	protected $uid = null;
	protected $data = null;
	protected $templateName = 'Gui\Grid\views\default';
	protected $elements = array();
	
	public function __construct($uid) {
		$this->uid = $uid;		
	}
	
	
}

