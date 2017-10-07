<?php

namespace Core;

/**
 * Class Container
 * @package Core
 */
class Container {
    private $storage = array();

    /**
     * Bind closure to class type
     *
     * @param $className
     * @param $value
     * @throws RuntimeException
     */
    public function bind($className, $value) {
        if ($this->isClosure($value)) {
            $this->storage[$this->fixSlashInClassName($className)] = $value;
        } else {
            throw new RuntimeException("Container: Provided class $className must be valid closure so container is able to create new instances");
        }
    }

    /**
     * Bind existing instance of class type
     *
     * @param $className
     * @param $value
     */
    public function instance($className, $value) {
        $this->storage[$this->fixSlashInClassName($className)] = $value;
    }

    /**
     * Create singleton for class
     *
     * @param $className
     * @param array $params
     * @return mixed|object
     */
    public function singleton($className, array $params = array()) {
        $className = $this->fixSlashInClassName($className);
        $instance = $this->make($className, $params);
        $this->storage[$className] = $instance;
        return $instance;
    }

    /**
     * Create instance of class and inject all dependencies
     *
     * @param $className
     * @param array $args
     * @return mixed|object
     * @throws RuntimeException
     */
    public function make($className, array $args = array()) {
        $className = $this->fixSlashInClassName($className);

        if (array_key_exists($className, $this->storage)) {
            return $this->storage[$className];
        }

        $callParams = array();
        try {
            $r = new \ReflectionClass($className);
        } catch (\ReflectionException $e) {
            throw new RuntimeException("Container: " . $e->getMessage());
        }

        $constructor = $r->getConstructor();
        if ($constructor) {
            $callParams = $this->getParamsForInvocation($constructor, $args);
        }

        return $r->newInstanceArgs($callParams);
    }

    /**
     * Create intance of class, invoke method and return output.
     * Args are shared for class creation and method call.
     *
     * @param $className
     * @param $methodName
     * @param array $args
     * @return mixed
     */
    public function makeAndInvoke($className, $methodName, array $args = array()) {
        $instance = $this->make($className, $args);
        $method = new \ReflectionMethod($className, $methodName);
        return $method->invokeArgs($instance, $this->getParamsForInvocation($method, $args));
    }

    /**
     * Examine method params and return dependencies for invocation
     *
     * @param \ReflectionMethod $method
     * @param array $args
     * @return array
     * @throws RuntimeException
     */
    private function getParamsForInvocation(\ReflectionMethod $method, array $args = array()) {
        $callParams = array();

        foreach ($method->getParameters() as $param) {
            $name = $param->getName();
            $type = $param->getType() == null ? $param->getType() : $this->fixSlashInClassName($param->getType()->__toString());

            if ($type == null || $type == '\array') {
                if (array_key_exists($name, $args)) {
                    $callParams[$name] = $args[$name];
                } elseif ($param->isDefaultValueAvailable()) {
                    $callParams[$name] = $param->getDefaultValue();
                } else {
                    throw new RuntimeException("Container: Cannot init '$name'. No value provided.");
                }
            } else {
                if ($type == $this->fixSlashInClassName(get_class($this))) {
                    $callParams[$name] = $this;
                } elseif (array_key_exists($type, $this->storage)) {
                    if (is_callable($this->storage[$type])) {
                        $callParams[$name] = call_user_func($this->storage[$type]);
                    } else {
                        $callParams[$name] = $this->storage[$type];
                    }
                } else {
                    $callParams[$name] = $this->make($type);
                }

                if (!($callParams[$name] instanceof $type)) {
                    throw new RuntimeException("Container: Invalid injected type. Expected '$type', got '" . get_class($callParams[$name]) . "'");
                }
            }
        }
        return $callParams;
    }

    /**
     * Check if given value is valid closure
     *
     * @param $t
     * @return bool
     */
    private function isClosure($t) {
        return is_object($t) && ($t instanceof \Closure);
    }

    /**
     * @param $className
     * @return string
     */
    private function fixSlashInClassName($className) {
        return "\\" . ltrim($className, '\\');
    }
}