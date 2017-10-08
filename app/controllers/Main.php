<?php

namespace controllers;

class Main extends \Core\Controller {

    public function index() {
        return $this->html("Hello world");
    }

    public function page1() {
        return $this->html("Page 1");
    }

    public function error404() {
        return $this->html("404 :(");
    }

    public function error500() {
        return $this->html("500 :(");
    }
}