<?php namespace Pochika\Support;

use Conf;
use Pochika\Repository\EntryCollection as Collection;
use Route;
use URL;

class Paginator {

    const DEFAULT_COUNT = 10;

    public static function get($posts, $page = 1, $per_page = null, $route = null, $convert = false)
    {
        if (!$page) {
            $page = 1;
        }

        if (!is_numeric($page) || 0 > $page) {
            throw new \InvalidPageException;
        }

        $per_page = $per_page ?: Conf::get('paginate', self::DEFAULT_COUNT);
        $offset = $per_page * ($page - 1);

        $total = count($posts);
        $pages = (int)ceil($total / $per_page);

        if ($pages && $page > $pages) {
            throw new \InvalidPageException("Page number can't be greater than total pages: {$page} > {$pages}");
        }

        $next_page = ($pages && $page != $pages) ? $page + 1 : null;
        $prev_page = $page != 1 ? $page - 1 : null;

        # URL::route(name, parameters, absolute)
        if (!$route) {
            $route = 'index_paged';
        }
        $params = ['page' => 0];
        if (is_array($route)) {
            $params += current($route);
            $route = key($route);
        }
        if (false === strpos($route, ':page')) {
            $pattern = str_replace('0', ':page', URL::route($route, $params, false));
        } else {
            $pattern = $route;
        }

        if ($posts) {
            $posts = self::paginatePosts($posts, $offset, $per_page, $convert);
        }

        $count = count($posts);
        $start_pos = ($page - 1) * $per_page + 1;
        $end_pos = $start_pos + $count - 1;

        return [
            'posts'     => $posts,
            'page'      => $page,
            'per_page'  => $per_page,
            'total'     => $total,
            'count'     => $count,
            'pages'     => $pages,
            'prev_page' => $prev_page,
            'next_page' => $next_page,
            'prev_url'  => $prev_page ? str_replace(':page', $prev_page, $pattern) : null,
            'next_url'  => $next_page ? str_replace(':page', $next_page, $pattern) : null,
            'start_pos' => $start_pos,
            'end_pos'   => $end_pos,
        ];
    }

    public static function paginatePosts(Collection $posts, $offset, $num, $convert = false)
    {
        return $posts->slice($offset, $num)->map(function ($post) use ($convert) {
            return $post->payload($convert);
        });
    }

}
