<?php

namespace Core;

class Container {
    private $storage = array();

    public function make($className, $params = array()) {
        $callParams = array();
        $r = new \ReflectionClass($className);

        $constructor = $r->getConstructor();
        if ($constructor == false) {
            return $r->newInstanceArgs();
        }

        foreach ($constructor->getParameters() as $param) {
            $name = $param->getName();

            if (array_key_exists($name, $params)) {
                $callParams[$name] = $params[$name];
            } else if (array_key_exists($name, $this->storage)) {
                if (is_callable($this->storage[$name])) {
                    $callParams[$name] = call_user_func($this->storage[$name]);
                } else {
                    $callParams[$name] = $this->storage[$name];
                }
            } else {
                if ($param->getClass() == null) {
                    if ($param->isDefaultValueAvailable()) {
                        $callParams[$name] = $param->getDefaultValue();
                    } else {
                        throw new RuntimeException("Cannot inject '$name'. Unable to find key in container or create basic type.");
                    }
                } else {
                    $callParams[$name] = $this->make($param->getClass());
                }
            }
        }

        return $r->newInstanceArgs($callParams);
    }
}