<?php

namespace controllers;

class Main extends \Core\Controller {

    public function index() {
        return $this->html("Hello world");
    }

    public function page1() {
        return $this->html("<h1>Request</h1><pre>".print_r($this->container->make("\Core\Request"),true)."</pre>");
    }

    public function page2($name) {
        return $this->html("<h1>$name</h1><pre>".print_r($this->container->make("\Core\Request"),true)."</pre>");
    }

    public function error404() {
        return $this->html("404 :(");
    }

    public function error500() {
        return $this->html("500 :(");
    }
}