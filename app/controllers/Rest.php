<?php

namespace controllers;

use Core\RESTController;

class Rest extends RESTController {

    public function get() {
        return $this->json(array('method' => 'GET'));
    }

    public function post() {
        return $this->json(array('method' => 'POST'));
    }
}