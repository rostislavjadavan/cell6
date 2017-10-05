<?php

require_once TEST_APP_PATH."core/RuntimeException.php";
require_once TEST_APP_PATH."core/Container.php";

class ContainerTest {

    public function makeTest() {
        $container = new Core\Container();
        $instance = $container->make("CT_A");
        var_dump($instance);
    }

    public function singletonTest() {

    }

    private function helpMethod() {

    }
}

class CT_A {
    private $b, $c;

    public function __construct(CT_B $b, CT_C $c) {
        $this->b = b;
        $this->c = c;
    }
}

class CT_B {

}

class CT_C {
    private $d;

    public function __construct(CT_D $d) {
        $this->d = d;
    }
}

class CT_D {

}