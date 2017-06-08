<?php

/**
 * Url
 *
 * @package MVC
 * @author spool
 */

namespace System\MVC;

class Url {

	public static function route($routeName, array $params = array(), array $query = array()) {
		return \System\Core\Container::get('router')->createUrl($routeName, $params) . (!empty($query) ? '?' . http_build_query($query) : '');
	}

	public static function routeCurrent(array $params = array(), array $query = array()) {
		 $routeName = \System\Core\Container::get('router')->getCurrentRouteName();
		 return self::route($routeName, $params, $query);
	}
}
