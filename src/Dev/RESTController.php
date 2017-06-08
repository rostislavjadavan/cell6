<?php

namespace Dev;

class RESTController extends \System\REST\RESTController {	
	
	public function get($id = 0) {
		$arrayOfData = array(
			'awe123fawef_'.$id => 'awefawefawef',
			'awefa41wef_'.$id => 'awefsdawwefawef',
			'awefawef_'.$id => 'TGH',
			'awe2fawef_'.$id => 'awefaweweffawef'
		);
		return $this->returnResponse($arrayOfData);
	}
	
	public function post() {
		return "POST".print_r($this->getBody(), true);
	}
	
	public function put() {
		return "PUT".print_r($this->getBody(), true);
	}
	
	public function delete() {
		return "DELETE";
	}
}