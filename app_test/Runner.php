<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

define("TEST_APP_PATH", "../app/");
define("CORE_PATH", TEST_APP_PATH . "core/");

require_once CORE_PATH . "ColorCli.php";

class Assert {
    public static function assertTrue($name, $value) {
        echo "\t\t", $value == true ? ColorCli::green("$name=true OK") : ColorCli::red("$name=true FAIL!"), PHP_EOL;
    }
    public static function assertEqual($name, $expectedValue ,$value) {
        echo "\t\t", $value == $expectedValue ? ColorCli::green("$name=$value OK") : ColorCli::red("$name=$value, expected=$expectedValue FAIL!"), PHP_EOL;
    }
    public static function assertNotNull($name, $value) {
        echo "\t\t", $value != null ? ColorCli::green("$name!=null OK") : ColorCli::red("$name!=null FAIL"), PHP_EOL;
    }
    public static function assertObjectNotNull($name, $value) {
        echo "\t\t", $value != null ? ColorCli::green("$name!=null (".get_class($value).") OK") : ColorCli::red("$name!=null FAIL"), PHP_EOL;
    }
}

class Runner {
    public function run() {
        echo ColorCli::cyan("Cell6 Test Runner"), PHP_EOL, PHP_EOL;

        $allFiles = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('test'));
        $testFiles = new RegexIterator($allFiles, '/\Test.php$/');

        foreach ($testFiles as $file) {
            require_once $file;
            $pathInfo = pathinfo($file);
            $className = $pathInfo['filename'];

            if (class_exists($className)) {
                $this->runMethods($className);
            }
        }
    }

    private function runMethods($className) {
        $class = new ReflectionClass($className);
        $instance = $class->newInstance();

        foreach ($class->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            echo "\t", ColorCli::light_gray($className . "::" . $method->getName()), PHP_EOL;
            $method->invoke($instance);
            echo PHP_EOL;
        }
    }
}

$runner = new Runner();
$runner->run();