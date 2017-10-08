<?php

namespace controllers;

class Main extends \Core\Controller {

    public function index() {
        return $this->html("Hello world");
    }

    public function page1() {
        return $this->html("Page 1");
    }
}