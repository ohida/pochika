<?php namespace Pochika\Repository;

use Collection;
use Paginator;

class EntryCollection extends Collection {

	public function paginate($page, $per_page, $route = null, $convert = true)
	{
		return Paginator::get($this, $page, $per_page, $route, $convert);
	}

}