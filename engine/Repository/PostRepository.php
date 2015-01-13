<?php namespace Pochika\Repository;

use Finder;
use Log;
use Post;

class PostRepository extends Repository {

    use MarkdownFinder;
    use ContentCachable;

    /**
     * collect items
     * 
     * @access protected
     * @return array
     */
    protected function collect()
    {
        $items = [];

        foreach ($this->finder() as $file) {
            try {
                $post = new Post($file->getRealPath());
                $items[$post->key] = $post;
            } catch (\InvalidEntryException $e) {
                continue;
            }
        }

        $items = array_sort($items, function($item) {
            return - $item->date;
        });

        Log::debug(sprintf('%d posts loaded', count($items)));
        
        return new EntryCollection($items);
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
