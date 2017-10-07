<?php

namespace Core;

class Container {
    private $storage = array();

    public function bind($className, $value) {
        if ($this->isClosure($value)) {
            $this->storage[$className] = $value;
        } else {
            throw new RuntimeException("Provided class $className must be valid closure so container is able to create new instances");
        }
    }

    public function instance($className, $value) {
        $this->storage[$className] = $value;
    }

    public function singleton($className, $params = array()) {
        $instance = $this->make($className, $params);
        $this->storage[$className] = $instance;
        return $instance;
    }

    public function make($className, $params = array()) {
        if (array_key_exists($className, $this->storage)) {
            return $this->storage[$className];
        }

        $callParams = array();
        $r = new \ReflectionClass($className);

        $constructor = $r->getConstructor();
        if ($constructor == false) {
            return $r->newInstanceArgs();
        }

        foreach ($constructor->getParameters() as $param) {
            $name = $param->getName();

            if ($param->getType() == null) {
                if (array_key_exists($name, $params)) {
                    $callParams[$name] = $params[$name];
                } elseif ($param->isDefaultValueAvailable()) {
                    $callParams[$name] = $param->getDefaultValue();
                } else {
                    throw new RuntimeException("Cannot init '$name'. No value provided.");
                }
            } else {
                $type = $param->getType()->__toString();

                if (array_key_exists($type, $this->storage)) {
                    if (is_callable($this->storage[$type])) {
                        $callParams[$name] = call_user_func($this->storage[$type]);
                    } else {
                        $callParams[$name] = $this->storage[$type];
                    }
                } else {
                    $callParams[$name] = $this->make($type);
                }

                if (!($callParams[$name] instanceof $type)) {
                    throw new RuntimeException("Invalid injected type. Expected '$type', got '" . get_class($callParams[$name]) . "'");
                }
            }
        }
        return $r->newInstanceArgs($callParams);
    }

    private function isClosure($t) {
        return is_object($t) && ($t instanceof \Closure);
    }
}