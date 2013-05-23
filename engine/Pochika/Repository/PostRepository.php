<?php namespace Pochika\Repository;

use App;
use Cache;
use Collection;
use Conf;
use Event;
use Finder;
use Log;
use Post;
use Profiler;
use Tag;

class PostRepository extends EntryRepository {

    /**
     * collect items
     * 
     * @param mixed $dir 
     * @access protected
     * @return array
     */
    protected function collect()
    {
        $dir = app('path.posts');

        if (!file_exists($dir)) {
            return [];
        }

        $ext = '/\.(?:md|markdown|mkdn?|mdown)$/';

        $finder = new Finder;
        $finder->files()->name($ext)->in($dir);

        $items = [];

        foreach ($finder->in($dir) as $file) {
            try {
                $post = new Post($file->getRealPath());
                $items[$post->key] = $post;
            } catch (\InvalidEntryException $e) {
                continue;
            }
        }

        if ($items) {
            uasort($items, function($a, $b){
                return $a->date < $b->date;
            });
        }

        Log::debug(sprintf('%d posts loaded', count($items)));

        return $items;
    }

    /**
     * findByTag
     *
     * @param  string $tag
     * @return array
     */
    public function findByTag($tag)
    {
        return $this->find(function() use ($tag) {
            return $this->collection->filter(function($post) use ($tag) {
                return in_array($tag, $post->tags);
            });
        });
    }

    /**
     * Search posts
     * 
     * @param string $query 
     * @return array
     * @todo tag?
     */
    public function search($query)
    {
        return $this->collection->filter(function($post) use ($query) {
            return (
                false !== stripos($post->content, $query) ||
                false !== stripos($post->title, $query) ||
                in_array($query, $post->tags)
            );
        });
    }

}
