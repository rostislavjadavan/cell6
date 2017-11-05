<?php

namespace Core;

/**
 * Class RESTController
 * @package Core
 */
class RESTController extends Controller {

    protected $request;
    protected $container;

    public function __construct(Request $request, Container $container) {
        $this->request = $request;
        $this->container = $container;
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
        return $this->container->make('\Core\JsonResponse', ['content' => $data, 'code' => $statusCode]);
    }

    public function get() {
        return $this->json(['error' => "GET not implemented"], 501);
    }

    public function post() {
        return $this->json(['error' => "POST not implemented"], 501);
    }

    public function put() {
        return $this->json(['error' => "PUT not implemented"], 501);
    }

    public function delete() {
        return $this->json(['error' => "DELETE not implemented"], 501);
    }
}