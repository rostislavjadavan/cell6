<?php

namespace Gui\Form;

class Select extends Base {
	protected $template = 'Gui\Form\Select_view';
    protected $options = array();
    protected $size = 2;

    public function getOptions() {
        return $this->options;
    }

    public function setOptions(array $options) {
        $this->options = $options;
        return $this;
    }

    protected function getViewParams() {
        return array_merge(parent::getViewParams(), array('options' => $this->options));
    }
}
