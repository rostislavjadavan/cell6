<?php

namespace Gui;

class Form {
	protected $uid = null;
	protected $data = null;
	protected $template = 'Gui\Form\view';
	protected $controls = array();
	protected $buttons = array();

	public function __construct($uid, array $data = array()) {
		$this->uid = $uid;
		$this->data = $data;
	}

	public function add($formControl) {
		if ($formControl instanceof \Gui\Form\Button) {
			$this->buttons[] = $formControl;
		} else {
		    if (array_key_exists($formControl->getId(), $this->data)) {
                $formControl->setValue($this->data[$formControl->getId()]);
            }
			$this->controls[] = $formControl;
		}
		return $formControl;
	}

	public function loadData(array $data) {
	    foreach($this->controls as $control) {
            if (array_key_exists($control->getId(), $data)) {
                $control->setValue($data[$control->getId()]);
            }
        }
    }

    public function onClick($buttonId, callable $function) {
        $request = \System\Core\Container::get('request');
        if ($request->isAjax() && $request->getQuery(\Gui\Form\Button::FORM_BUTTON_ID_PARAM) == $buttonId) {
            parse_str($request->getAjaxPayload(), $requestData);
            $this->loadData($requestData);
            $payload = array(
                'content' => $this->render()
            );

            $response = \System\Core\Container::build('\System\Http\JsonResponse', array(
                'content' => json_encode($payload), 'code' => 200
            ));
            $response->sendHeaders();
            $response->sendContent();
            die();
        }
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
