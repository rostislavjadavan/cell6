<?php

namespace Gui\Form;

abstract class Base {
	protected $id;
	protected $label;
	protected $class;
	protected $size = 4;
	protected $note;
	protected $value = null;
    protected $template = null;
	protected $isSubmit = false;

	public function __construct($id, $label = null, $class = null) {
		$this->id = $id;
        $this->label = $label;
        $this->class = $class;
		$this->loadPost();
	}

    protected function loadPost() {
        $request = \System\Core\Container::get("request");
		if ($request->isPost() && $request->getPost($this->id)) {
			$this->value = $request->getPost($this->id);
		}
	}

	public function getId() {
		return $this->id;
	}

	public function setLabel($label) {
		$this->label = $label;
		return $this;
	}

	public function getLabel() {
		return $this->label;
	}

	public function setClass($class) {
		$this->class = $class;
		return $this;
	}

	public function getClass() {
		return $this->class;
	}

	public function setSize($size) {
		$this->size = $size;
		return $size;
	}

	public function getSize() {
		return $this->size;
	}

	public function setNote($note) {
		$this->note = $note;
		return $this;
	}

	public function getNote() {
		return $this->note;
	}

	public function getValue() {
		return $this->value;
	}

	/* @Override */
	public function getPreSaveValue() {
		return $this->value;
	}

	public function setDefaultValue($defaultValue) {
		$this->value = $defaultValue;
		$this->loadPost();
		return $this;
	}

	public function isSubmit() {
		return $this->isSubmit;
	}

	protected function getViewParams() {
		return array(
			'id' => $this->id,
			'label' => $this->label,
			'class' => $this->class,
			'note' => $this->note,
			'size' => $this->size,
			'value' => $this->value
		);
	}

	public function render() {
		$view = \System\MVC\View::load($this->template);
		$view->setParams($this->getViewParams());
		return $view->render();
	}

}
