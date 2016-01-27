<?php

/**
 * Class Auto Loader
 *
 * @package Core
 * @author spool
 */

namespace System\Core;

class ClassAutoLoader {

	/**
	 * Get file path for class
	 * 
	 * @param string Class name
	 * @return string Class path
	 */
	public function getClassPath($class) {
		$class = ltrim($class, '\\');
		$segments = preg_split('#[\\\\]#', $class);

		$path = SYSPATH . DS . implode(DS, $segments) . '.php';

		if (!file_exists($path)) {
			throw new SystemException("LOADER: Class $class ($path) not found.");
		}

		return $path;
	}

	/**
	 * Register autoloader
	 *
	 * @return bool TRUE on success
	 */
	public function registerAutoloader() {
		return spl_autoload_register(array($this, '_autoloader_func'), true, true);
	}

	/**
	 * Autoloader function
	 *
	 * @param string Class name
	 * @return bool TRUE if class is loaded
	 */
	private function _autoloader_func($class) {
		$class = ltrim($class, '\\');
		$segments = preg_split('#[\\\\]#', $class);

		$file = SYSPATH . DS . implode(DS, $segments) . '.php';

		if (file_exists($file)) {
			return include_once $file;
		}

		return false;
	}

}
