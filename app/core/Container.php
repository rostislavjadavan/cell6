<?php

namespace Core;

/**
 * Class Container
 * @package Core
 */
class Container {
    private $storage = [];

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
    public function singleton($className, array $params = []) {
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
    public function make($className, array $args = []) {
        $className = $this->fixSlashInClassName($className);

        if (array_key_exists($className, $this->storage)) {
            return $this->storage[$className];
        }

        $callParams = [];
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
     * @throws RuntimeException
     */
    public function makeAndInvoke($className, $methodName, array $args = []) {
        try {
            $instance = $this->make($className, $args);
            $method = new \ReflectionMethod($className, $methodName);
            return $method->invokeArgs($instance, $this->getParamsForInvocation($method, $args));
        } catch (\ReflectionException $e) {
            throw new RuntimeException($e);
        }
    }

    /**
     * Invoke closure (callable) and inject parameters
     *
     * @param callable $closure
     * @param array $params
     * @return mixed
     * @throws RuntimeException
     */
    public function invokeClosure(callable $closure, array $params = []) {
        try {
            $function = new \ReflectionFunction($closure);
            return $function->invokeArgs($this->getParamsForInvocation($function, $params));
        } catch (\ReflectionException $e) {
            throw new RuntimeException($e);
        }
    }

    /**
     * Examine method params and return dependencies for invocation
     *
     * @param \ReflectionFunctionAbstract $method
     * @param array $args
     * @return array
     * @throws RuntimeException
     */
    private function getParamsForInvocation(\ReflectionFunctionAbstract $method, array $args = []) {
        $callParams = [];

        foreach ($method->getParameters() as $param) {
            $name = $param->getName();
            $type = $this->getParamType($param);

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
     * Get parameter type (PHP 5.6 version)
     *
     * @param \ReflectionParameter $param
     * @return null|string
     */
    private function getParamType(\ReflectionParameter $param) {
        // PHP 7
        // $param->getType() == null ? $param->getType() : $this->fixSlashInClassName($param->getType()->__toString());

        if ($param->isArray()) {
            return "\array";
        }
        try {
            $class = $param->getClass();
            return $class == null ? null : $this->fixSlashInClassName($class->getName());
        } catch (\Exception $e) {
            return null;
        }
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