<?php

namespace Dev;

class Auth {
	public static function checkLogin() {				
		return \System\Core\Container::build('\System\Http\RedirectResponse', array('url' => \System\MVC\Url::route('login')));
	}
}

