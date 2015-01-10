<?php namespace Pochika\Repository;

use Collection;
use Pochika\Support\Paginator;

class EntryCollection extends Collection {

    public function paginate($page, $per_page, $route = null, $convert = true)
    {
        return Paginator::get($this, $page, $per_page, $route, $convert);
    }

}