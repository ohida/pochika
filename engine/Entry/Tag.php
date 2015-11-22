<?php

namespace Pochika\Entry;

use Collection;

class Tag
{
    protected $collection;

    public function all()
    {
        return $this->collection;
    }

    public function load()
    {
        $posts = Post::all();

        $items = [];

        $posts->each(function ($post) use (&$items) {
            if (!$post->tags) {
                return;
            }
            foreach ($post->tags as $tag) {
                if (!isset($items[$tag])) {
                    $items[$tag] = [];
                }
                $items[$tag][] = &$post;
                //$items[$tag][] = &$post->key;
            }
        });

        $this->collection = new Collection($items);
    }

    //public function load2()
    //{
    //    $posts = Post::all();
    //
    //    $tags = [];
    //
    //    $posts->each(function($post) use(&$tags) {
    //        if ($post->tags) {
    //            foreach ($post->tags as $tag) {
    //                if (!isset($tags[$tag])) {
    //                    $tags[$tag] = [];
    //                }
    //                //$tags[$tag][] = &$post;
    //                $tags[$tag][] = &$post->key;
    //            }
    //        }
    //    });
    //
    //    //dd(xdebug_memory_usage()/1024/1024);
    //}

    //public function bench()
    //{
    //    $load1 = function() {
    //        $this->load();
    //    };
    //    $load2 = function() {
    //        $this->load2();
    //    };
    //    dd(bench([$load1, $load2], 1000));
    //}
}
