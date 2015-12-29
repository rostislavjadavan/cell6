<?php
/**
 * Container
 *
 * @package Core
 * @author spool
 */

namespace System\Core;

class Container {
	private static $storage = array();

	public static function add($key, $value) {
		self::$storage[$key] = $value;
	}

	public static function get($key) {
		if (array_key_exists($key, self::$storage)) {
			return self::$storage[$key];
		}
		throw new SystemException("Unable to found '$key'");
	}
	
	public static function build($className, $params = array()) {
		$callParams = array();
		$r = new \ReflectionClass($className);

		$constructor = $r->getConstructor();
		if ($constructor == false) {
			return $r->newInstanceArgs();
		}
				
		foreach ($constructor->getParameters() as $param) {
			$name = $param->getName();
			
			if (array_key_exists($name, self::$storage)) {
				if (is_callable(self::$storage[$name])) {
					$callParams[$name] = call_user_func(self::$storage[$name]);
				}
				else {
					$callParams[$name] = self::$storage[$name];
				}
			} elseif (array_key_exists($name, $params)) {
				$callParams[$name] = $params[$name];
			} else {
				if ($param->getClass() == null) {
					if ($param->isDefaultValueAvailable()) {
						$callParams[$name] = $param->getDefaultValue();
					} else {
						throw new SystemException("Cannot inject '$name'. Unable to find key in container or create basic type.");
					}
				} else {
					$callParams[$name] = $param->getClass()->newInstance();
				}
			}
		}		

		return $r->newInstanceArgs($callParams);
	}

	public static function buildAndAdd($key, $className, $params = array()) {
		$instance = self::build($className, $params);
		self::add($key, $instance);
		return $instance;
	}

	public static function getStorage() {
		return self::$storage;
	}
}