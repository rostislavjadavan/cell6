<?php

require_once TEST_APP_PATH . "core/RuntimeException.php";
require_once TEST_APP_PATH . "core/Container.php";

class ContainerTest {

    public function makeTest() {
        $container = new Core\Container();
        $instance = $container->make("CT_A");

        Assert::assertObjectNotNull("instance", $instance);
    }

    public function singletonTest() {
        $container = new Core\Container();
        $i1 = $container->singleton("CT_Singleton");
        $i2 = $container->singleton("CT_Singleton");
        $i3 = $container->singleton("CT_Singleton");

        Assert::assertObjectNotNull("i1", $i1);
        Assert::assertObjectNotNull("i2", $i2);
        Assert::assertObjectNotNull("i3", $i3);
        Assert::assertEqual("CT_Singleton::counter", 1, $i3->getCounter());
    }

    public function instanceTest() {
        $d = new CT_D();
        $d->setValue(1);

        $container = new Core\Container();
        $container->instance("CT_D", $d);
        $c = $container->make("CT_C");

        Assert::assertObjectNotNull("CT_C", $c);
        Assert::assertEqual("CT_D::value", 1, $c->getD()->getValue());
    }
}

class CT_A {
    private $b, $c;

    public function __construct(CT_B $b, CT_C $c) {
        $this->b = $b;
        $this->c = $c;
    }
}

class CT_B {

}

class CT_C {
    private $d;

    public function __construct(CT_D $d) {
        $this->d = $d;
    }

    public function getD() {
        return $this->d;
    }
}

class CT_D {
    private $value = 0;

    public function getValue() {
        return $this->value;
    }

    public function setValue($value) {
        $this->value = $value;
    }

}

class CT_Singleton {
    private static $counter = 0;

    public function __construct() {
        self::$counter++;
    }

    public function getCounter() {
        return self::$counter;
    }

}