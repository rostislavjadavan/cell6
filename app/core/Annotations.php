<?php

namespace Core;

class Annotations {
    /**
     * Pattern for matching variable
     * @var string
     */
    private $patternVariable = "/[\s*]@(?<name>[\w]+)[(](?<data>.*)[)]/";

    /**
     * Pattern for matching array
     * @var string
     */
    private $patternArray = "/[\s*]@(?<name>[\w]+)[(]\[(?<data>.*)\][)]/";

    /**
     * Pattern for matching object
     * @var string
     */
    private $patternObject = "/[\s*]@(?<name>[\w]+)[(]\{(?<data>.*)\}[)]/";

    /**
     * Parsed doc comments
     *
     * @var array|mixed
     */
    private $data = [];

    /**
     * Annotations constructor.
     *
     * @param $className
     */
    public function __construct($className) {
        $this->data = $this->parse(new \ReflectionClass($className));
    }

    /**
     * Get all annotations
     * @example ([class] => ... [properties] => ([property_name1] ...) [methods] => ([method_name1] ...)
     *
     * @return array|mixed
     */
    public function getAll() {
        return $this->data;
    }

    /**
     * Get class annotations
     *
     * @return mixed
     */
    public function getClass() {
        return $this->data['class'];
    }

    /**
     * Get methods annotations
     *
     * @param $methodName
     * @return mixed
     * @throws RuntimeException
     */
    public function getMethod($methodName) {
        if (array_key_exists($methodName, $this->data['methods'])) {
            return $this->data['methods'][$methodName];
        }
        throw new RuntimeException("Annotations: Method $methodName does not exists");
    }

    /**
     * Get properties annotations
     *
     * @param $propertyName
     * @return mixed
     * @throws RuntimeException
     */
    public function getProperty($propertyName) {
        if (array_key_exists($propertyName, $this->data['properties'])) {
            return $this->data['properties'][$propertyName];
        }
        throw new RuntimeException("Annotations: Method $propertyName does not exists");
    }

    /**n
     * Find all annotatios with given name
     *
     * @param $annotationName
     * @return array
     */
    public function findAnnotations($annotationName) {
        $output = [];
        if (array_key_exists($annotationName, $this->data['class'])) {
            $output[] = $this->data['class'][$annotationName];
        }
        foreach ($this->data['properties'] as $property) {
            if (array_key_exists($annotationName, $property)) {
                $output[] = $property[$annotationName];
            }
        }
        foreach ($this->data['methods'] as $method) {
            if (array_key_exists($annotationName, $method)) {
                $output[] = $method[$annotationName];
            }
        }
        return $output;
    }

    /**
     * Parse all annotations for class
     *
     * @param \ReflectionClass $class
     * @return mixed
     */
    private function parse(\ReflectionClass $class) {
        $output['class'] = $this->parseDocComment($class->getDocComment());

        foreach ($class->getMethods() as $m) {
            $output['methods'][$m->getName()] = array_merge(
                $this->parseDocComment($m->getDocComment()),
                [ '_element' => $m->getName(), '_element_type' => 'method']
            );
        }

        foreach ($class->getProperties() as $p) {
            $output['properties'][$p->getName()] = array_merge(
                $this->parseDocComment($p->getDocComment()),
                [ '_element' => $p->getName(), '_element_type' => 'property']
            );
        }

        return $output;
    }

    /**
     * Parse annotations for give doc docmment
     *
     * @param $docComment
     * @return array
     */
    private function parseDocComment($docComment) {
        $output = [];
        foreach (explode("\n", $docComment) as $line) {
            preg_match($this->patternObject, $line, $m);
            if (isset($m[0]) && isset($m['name']) && isset($m['data'])) {
                $output[$m['name']] = $this->parseObject($m['data']);
                continue;
            }
            preg_match($this->patternArray, $line, $m);
            if (isset($m[0]) && isset($m['name']) && isset($m['data'])) {
                $output[$m['name']] = $this->parseArray($m['data']);
                continue;
            }
            preg_match($this->patternVariable, $line, $m);
            if (isset($m[0]) && isset($m['name']) && isset($m['data'])) {
                $output[$m['name']] = $this->parseVariable($m['data']);
                continue;
            }
        }
        return $output;
    }

    /**
     * Parse object annotation
     *
     * @param $data
     * @return stdClass
     */
    private function parseObject($data) {
        $output = new stdClass();
        foreach(explode(",", $data) as $pair) {
            $pairParts = explode("=", trim($pair));
            if (count($pairParts) > 1) {
                $output->{$pairParts[0]} = $pairParts[1];
            }
        }
        return $output;
    }

    /**
     * Parse array annotation
     *
     * @param $data
     * @return array
     */
    private function parseArray($data) {
        $output = [];
        foreach(explode(",", $data) as $item) {
            $pairParts = explode("=", trim($item));
            if (count($pairParts) > 1) {
                $output[$pairParts[0]] = $pairParts[1];
            } else {
                $output[] = trim($item);
            }
        }
        return $output;
    }

    /**
     * Parse variable annotation
     *
     * @param $data
     * @return string
     */
    private function parseVariable($data) {
        return trim($data);
    }
}
