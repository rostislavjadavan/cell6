<?php

namespace Gui\Form;

class Button extends Base {
    const FORM_BUTTON_ID_PARAM = 'form-button-id';

	protected $template = 'Gui\Form\Button_view';
    protected $class = 'btn-primary';

    public function getViewParams() {
        return array_merge(
            parent::getViewParams(),
            array('actionUrl' => \System\MVC\Url::routeCurrent(array(), array(self::FORM_BUTTON_ID_PARAM => $this->id)))
        );
    }
}
