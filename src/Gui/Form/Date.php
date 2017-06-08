<?php

namespace Gui\Form;

class Date extends Base {
	protected $template = 'Gui\Form\Date_view';
	protected $size = 1;

	public function setToday() {
		$this->setDefaultValue(date("Y/m/d"));
		return $this;
	}
}
