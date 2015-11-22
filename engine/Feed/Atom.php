<?php

namespace Pochika\Feed;

use Conf;
use Post;
use View;

class Atom extends Feed
{
    /**
     * Generate atom feed
     *
     * @return View
     */
    public function generate()
    {
        return $this->make()->render();
    }

    /**
     * @return View
     */
    public function make()
    {
        $posts = Post::all()->slice(0, self::ENTRY_COUNT);

        $blog_title = Conf::get('title');
        $blog_url = url();
        $description = Conf::get('description');
        $author = Conf::get('author');

        $blog_id = $this->getBlogId($blog_url);

        $data = [
            'title' => $this->escape($blog_title),
            'subtitle' => $this->escape($description),
            'author' => $this->escape($author),
            'url' => $blog_url,
            'updated' => date('c'),
            'id' => $blog_id,
            'entries' => &$entries,
            'generator' => 'Pochika',
            'feed_url' => url('feed'),
            'icon_url' => url('assets/favicon.png'),
            'logo_url' => null,
        ];

        $entries = [];
        foreach ($posts as $post) {
            $post->convert();
            $entries[] = [
                'title' => $post->title,
                'url' => $post->url,
                'updated' => date('c', $post->date),
                'author' => $post->author ?: $author,
                'id' => $this->getPostId($blog_id, $post),
                'content' => $this->cdata($post->content),
                //'content' => $post->content,
            ];
        }

        return view('feed.atom')->with($data);
    }

    protected function getBlogId($blog_url)
    {
        $res = parse_url($blog_url);

        return sprintf('tag:%s,%d:blog', $res['host'], 2013);
    }

    protected function getPostId($blog_id, $post)
    {
        return sprintf('%s.post-%s', $blog_id, $post->key);
    }

    protected function cdata($value)
    {
        $value = str_replace(']]>', ']]]]><![CDATA[>', $value);

        return sprintf('<![CDATA[%s]]>', $value);
    }

    protected function escape($value)
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }
}
