<?php

/**
 * REST Controller
 *
 * @package MVC
 * @author spool
 */

namespace Core;

class RESTController extends Controller {
	
	protected $request;		
	
	public function __construct(Request $request) {
		$this->request = $request;
	}
		
	public function getBody() {
		switch ($this->request->getMethod()) {
			case 'POST':
				return $this->request->getPost();
			case 'PUT':				
				$json = new JSON();
				return $json->decode(file_get_contents("php://input"), true);								
			default:
				return null;
		}
	}
	
	public function returnResponse(array $data, $statusCode = 200) {
		return Container::build('\Core\JsonResponse', array(
			'content' => $data, 'code' => $statusCode
		));
	}
	
	public function get() {
		throw new \Exception("RESTCONTROLLER: GET not implemented");
	}
	
	public function post() {
		throw new \Exception("RESTCONTROLLER: POST not implemented");
	}
	
	public function put() {
		throw new \Exception("RESTCONTROLLER: PUT not implemented");
	}
	
	public function delete() {
		throw new \Exception("RESTCONTROLLER: DELETE not implemented");
	}
}