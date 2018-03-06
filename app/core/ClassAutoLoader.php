<?php

namespace Core;

/**
 * Class ClassAutoLoader
 * @package Core
 */
class ClassAutoLoader {

    /**
     * Get file path for class
     *
     * @param $class string Class name
     * @throws RuntimeException
     * @return string Class path
     */
    public function getClassPath($class) {
        $class = ltrim($class, '\\');
        $segments = preg_split('#[\\\\]#', $class);

        $path = SYSPATH . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $segments) . '.php';

        if (!file_exists($path)) {
            throw new RuntimeException("ClassAutoLoader: Class $class ($path) not found.");
        }

        return $path;
    }

    /**
     * Register autoloader
     *
     * @return bool TRUE on success
     */
    public function registerAutoloader() {
        return spl_autoload_register([$this, '_autoloader_func'], true, true);
    }

    /**
     * Autoloader function
     *
     * @param $class string Class name
     * @return bool TRUE if class is loaded
     */
    private function _autoloader_func($class) {
        $class = ltrim($class, '\\');
        $segments = preg_split('#[\\\\]#', $class);

        $filename = array_pop($segments);
        array_walk($segments, function (&$array, &$key) {
            $array[$key] = strtolower($array[$key]);
        });

        $path = SYSPATH . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $segments) . DIRECTORY_SEPARATOR . $filename . '.php';

        if (file_exists($path)) {
            return include_once $path;
        }

        return false;
    }

}
