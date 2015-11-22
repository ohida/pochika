<?php

namespace Pochika\Repository;

use Finder;
use Log;
use Page;

class PageRepository extends Repository
{
    use MarkdownFinder;
    use ContentCachable;

    /**
     * collect items
     *
     * @return array
     */
    protected function collect()
    {
        $items = [];

        foreach ($this->finder() as $file) {
            try {
                $page = new Page($file);
                $items[$page->key] = $page;
            } catch (\InvalidEntryException $e) {
                continue;
            }
        }

        Log::debug(sprintf('%d pages loaded', count($items)));

        return new EntryCollection($items);
    }

    /**
     * search
     *
     * @param string $query
     * @return [type]        [description]
     */
    public function search($query)
    {
        return $this->collection->filter(function ($page) use ($query) {
            return (
                false !== stripos($page->content, $query)
//                    ||
//              false !== stripos($page->title, $query)
            );
        });
    }
}
