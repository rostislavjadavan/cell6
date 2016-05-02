<?php

namespace Gui\Data;

interface DataSourceInterface {
	public function getAll();	
	public function getCount();	
	public function getPage($page, $perPage = 10);
}

