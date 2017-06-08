<?php

namespace Gui;

class Grid {

	const GRID_UID_PARAM = 'griduid';

	protected $uid = null;
	protected $data = null;
	protected $templateName = 'Gui\Grid\views\default';
	protected $columns = array();	

	public function __construct($uid, Data\DataSourceInterface $data) {
		$this->uid = $uid;
		$this->data = $data;
	}

	public function add(Grid\ColumnInterface $column) {
		$this->columns[] = $column;
		return $column;
	}

	public function render() {
		$request = \System\Core\Container::get('request');
		if ($request->isAjax() && $request->getQuery(self::GRID_UID_PARAM) == $this->uid) {
			$response = \System\Core\Container::build('\System\Http\JsonResponse', array(
						'content' => $this->getData(), 'code' => 200
			));
			$response->sendHeaders();
			$response->sendContent();
			die();
		}

		$template = \System\MVC\View::load('Gui\Grid\view', array(
					'uid' => $this->uid,
					'backendUrl' => \System\MVC\Url::routeCurrent(array(), array(self::GRID_UID_PARAM => $this->uid)),
					'columnNames' => json_encode($this->getColumnNames()),
					'searchableIndexes' => json_encode($this->getSearchableColumnIndexes()),
		));
		return $template->render();
	}

	private function getData() {
		$data = array();
		foreach ($this->data->getAll() as $row) {
			$dataRow = array();
			foreach ($this->columns as $col) {
				$dataRow[] = $col->render($row);
			}
			$data[] = $dataRow;
		}
		return $data;
	}

	private function getColumnNames() {
		$names = array();
		foreach ($this->columns as $col) {
			$names[] = htmlentities($col->getTitle());
		}
		return $names;
	}

	private function getSearchableColumnIndexes() {
		$indexes = array();
		$index = 0;
		foreach ($this->columns as $col) {
			if ($col->isSearchable()) {
				$indexes[] = $index;
			}
			$index++;
		}
		return $indexes;
	}

}
