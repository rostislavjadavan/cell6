<?php

/**
 * Url
 *
 * @package MVC
 * @author spool
 */

namespace System\MVC;

class Url {

	public static function route($routeName, $params = array()) {
		return \System\Core\Container::get('router')->createUrl($routeName, $params);
	}

}
