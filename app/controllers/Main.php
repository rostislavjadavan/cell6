<?php

namespace controllers;

use Core\Controller;

class Main extends Controller {

    public function index() {
        return $this->template("page", "bootstrap4/template", ["title" => $this->config['app']]);
    }

    public function page1() {
        return $this->html("<h1>Request</h1><pre>" . print_r($this->container->make("\Core\Request"), true) . "</pre>");
    }

    public function page2($name) {
        $response = $this->html("<h1>Your name is $name</h1>");
        $response->setCookie("test", "value1", 10);
        return $response;
    }

    public function error404() {
        return $this->html("404 :(");
    }

    public function error500() {
        return $this->html("500 :(");
    }

    public function bootstrap() {
        return $this->template('page', 'bootstrap4/template', ['title' => 'Bootstrap4']);
    }
}