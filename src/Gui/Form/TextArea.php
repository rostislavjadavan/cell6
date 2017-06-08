<?php

namespace Gui\Form;

class TextArea extends Base {
	protected $template = 'Gui\Form\TextArea_view';
    protected $rows = 5;
    protected $size = 5;

    public function getRows() {
        return $this->rows;
    }

    public function setRows($rows) {
        $this->rows = $rows;
        return $this;
    }

    protected function getViewParams() {
        return array_merge(parent::getViewParams(), array('rows' => $this->rows));
    }
}
