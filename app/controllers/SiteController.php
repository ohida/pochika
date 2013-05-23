<?php

class SiteController extends BaseController {

    /**
     * Constructor
     * 
     * @return void
     */
    public function __construct()
    {
        if (false == Conf::get('profiler')) {
            Profiler::disable();
        }
    }

    /**
     * index
     * 
     * @param int $page 
     * @return Response
     */
    public function index($page = null)
    {
        $posts = Post::all();

        $paginator = count($posts) ? Paginator::get($posts, $page) : null;

        $html = Layout::find('index')->render([
            'paginator' => $paginator,
        ]);

        Event::fire('index.after', (object)['html' => &$html]);

        return Response::make($html);
    }

    /**
     * post
     *
     * @param int $year
     * @param int $month
     * @param int $day
     * @param string $slug
     * @return Response
     */
    public function post($key)
    {
        switch (Input::get('format')) {
            case 'content':
                return $this->postContent($key);
            case 'raw':
                return $this->postRaw($key);
            case 'dump':
                if ('local' == App::environment()) {
                    return $this->postDump($key);
                }
            default:
                return Post::find($key)->render();
        }
    }

    /**
     * render post content
     *
     * @param string $key
     * @return string
     * @todo
     */
    public function postContent($key)
    {
        $post = Post::find($key);
        $post->convert();

        return Response::make($post->content);
    }

    /**
     * page
     *
     * @param mixed $arg
     * @return Response
     */
    public function page($arg)
    {
        if (is_array($arg)) {
            $arg = implode('/', $arg);
        }

        $html = Page::find($arg)->render([
            'posts' => Post::all(),
        ]);

        return Response::make($html);
    }

    /**
     * tag
     *
     * @param string $tag
     * @param int $page
     * @return Response
     */
    public function tag($tag, $page = null)
    {
        $tag = urldecode($tag);

        $posts = Post::findByTag($tag);

        $pattern = sprintf('/tag/%s/:page', rawurlencode($tag));
        $paginator = Paginator::get($posts, $page, $pattern);

        $html = Layout::find('index')->render([
            'paginator' => $paginator,
            'tag' => $tag,
        ]);

        return Response::make($html);
    }

    /**
     * archives
     *
     * @param int $page
     * @return Response
     */
    public function archives($page = null)
    {
        $posts = Post::all();

        $per_page = Conf::get('archives_paginate', 30);

        $pattern = sprintf('/archives/page/:page');
        $paginator = Paginator::get($posts, $page, $pattern, $per_page);

        $html = Layout::find('archives')->render([
            'paginator' => $paginator,
        ]);

        return Response::make($html);
    }

    /**
     * feed
     *
     * @return Response
     */
    public function feed()
    {
        if ('production' == App::environment()) {
            $atom = Cache::rememberForever('atom', function() {
                return Feed::generate();
            });
        } else {
            $atom = Feed::generate();
        }

        return Response::make($atom, 200, [
            'content-type' => 'application/xml',
            'charset' => 'utf-8',
        ]);
    }

    /**
     * search 
     * 
     * @return Response
     */
    public function search()
    {
        $query = Input::get('q');
        $page  = Input::get('page');

        $posts = [];

        if ($query) {
            $posts = Post::search($query);
        }

        if (count($posts)) {
            $url = sprintf('/search?q=%s&page=:page', rawurlencode($query));
            $paginator = Paginator::get($posts, $page, $url);
        } else {
            $paginator = null;
        }

        $html = Layout::find('search')->render([
            'search' => true,
            'query' => $query,
            'paginator' => $paginator,
        ]);

        return Response::make($html);
    }

    public function notfound()
    {
        try {
            $html = Page::find(':error/404')->render();
            return Response::make($html, 404);
        } catch (Exception $e) {
            return Response::make('not found', 404);
        }
    }

    /**
     * show raw markdown
     *
     * @param string $key
     * @return Response
     */
    protected function postRaw($key)
    {
        Profiler::disable();

        $raw = Post::find($key)->raw();

        $response = Response::make($raw);
        $response->header('content-type', 'text/plain');

        return $response;
    }

    /**
     * show post debug
     *
     * @param string $key
     * @return Response
     */
    protected function postDump($key)
    {
        Profiler::disable();

        $post = Post::find($key);

        ob_start();
        var_dump($post);
        $dump = ob_get_clean();

        $response = Response::make($dump);
        $response->header('charset', 'utf-8');

        return $response;
    }

}
