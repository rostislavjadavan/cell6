<?php

/**
 * Filter Rule
 *
 * @package Http
 * @author spool
 */

namespace System\Http;

class FilterRule {

	protected $condition;
	protected $action;

	public function __construct(callable $condition, callable $action) {
		$this->condition = $condition;
		$this->action = $action;
	}
}
