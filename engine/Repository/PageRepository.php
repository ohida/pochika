<?php namespace Pochika\Repository;

use Conf;
use Finder;
use Log;
use Page;

class PageRepository extends Repository {

    use ContentCacheTrait;

    public function __construct()
    {
        //if (Conf::get('cache')) {
        //    $this->enableContentCache();
        //}
    }

    /**
     * collect items
     *
     * @access protected
     * @return array
     */
    protected function collect()
    {
        $dir = source_path('pages');

        if (!file_exists($dir)) {
            return [];
        }

        $ext = '/\.(?:md|markdown|mkdn?|mdown)$/';

        $finder = new Finder;
        $finder->files()->name($ext)->in($dir);

        $items = [];

        foreach ($finder as $file) {
            try {
                $page = new Page($file);
                $items[$page->key] = $page;
            } catch (\InvalidEntryException $e) {
                continue;
            }
        }

        Log::debug(sprintf('%d pages loaded', count($items)));

        return $items;
    }

    /**
     * search
     *
     * @param string $query
     * @return [type]        [description]
     */
    public function search($query)
    {
        return $this->collection->filter(function($page) use ($query) {
            return (
                false !== stripos($page->content, $query)
//                    ||
//              false !== stripos($page->title, $query)
            );
        });
    }

    //public function find($key)
    //{
    //    if ($key[0] == ':') {
    //        dd(substr($key, 1));
    //        //theme_path();
    //    }
    //    return parent::find($key);
    //}

}
