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
        Assert::assertEquals("CT_Singleton::counter", 1, $i3->getCounter());
    }

    public function instanceTest() {
        $d = new CT_D();
        $d->setValue(1);

        $container = new Core\Container();
        $container->instance("CT_D", $d);
        $c = $container->make("CT_C");

        Assert::assertObjectNotNull("CT_C", $c);
        Assert::assertEquals("CT_D::value", 1, $c->getD()->getValue());
    }

    public function injectContainerTest() {
        $container = new Core\Container();
        $containerClass = $container->make("CT_Container");

        Assert::assertObjectNotNull("CT_Container", $containerClass);
        Assert::assertObjectNotNull("CT_Container::container", $containerClass->getContainer());
        Assert::assertTrue("CT_Container === container", $container === $containerClass->getContainer());
    }

    public function injectArraysTest() {
        $container = new Core\Container();
        $route = $container->make("CT_Route", array(
            'params' => array('uri' => 'uri', 'class' => 'class', 'method' => 'method', 'requestMethod' => 'get'),
            'paramsConstraints' => array()
        ));
        Assert::assertObjectNotNull("CT_Route", $route);
        Assert::assertObjectNotNull("CT_Route::container", $route->getContainer());
    }

    public function makeAndInvokeTest() {
        $container = new Core\Container();
        $output = $container->makeAndInvoke("CT_Invoke", "run", array(
            'name' => 'A',
            'surname' => 'B'
        ));
        Assert::assertEquals('output', "AB", $output);
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

class CT_Container {
    private $container = null;

    public function __construct(\Core\Container $container) {
        $this->container = $container;
    }

    public function getContainer() {
        return $this->container;
    }
}

class CT_Route {
    private $container;
    private $params, $paramsConstraints;

    public function __construct(\Core\Container $container, array $params = array(), array $paramsConstraints = array()) {
        $this->container = $container;
        $this->params = $params;
        $this->paramsConstraints = $paramsConstraints;
    }

    public function getContainer() {
        return $this->container;
    }

    public function getParams() {
        return $this->params;
    }

    public function getParamsConstraints() {
        return $this->paramsConstraints;
    }

}

class CT_Invoke {
    private $surname;

    public function __construct($surname) {
        $this->surname = $surname;
    }

    public function run($name) {
        return $name.''.$this->surname;
    }
}