<?php

/**
 * Filter
 *
 * @package Http
 * @author spool
 */

namespace System\Http;

class Filter {
	protected $rules = array();
	
	public function add(FilterRule $rule) {
		$this->rules[] = $rule;
	}
	
	public function match($input) {
		foreach($this->rules as $rule) {
			//if ($ru)
		}
	}
}
