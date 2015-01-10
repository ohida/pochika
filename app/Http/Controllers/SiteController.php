<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Cache;
use Conf;
use Feed;
use Layout;
use Page;
use Paginator;
use Post;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Sitemap;

class SiteController extends Controller {

	/**
	 * Index
	 *
	 * @param int $page
	 * @return Response
	 */
	public function index($page = null)
	{
		$posts = Post::all();
		
		$per_page = Conf::get('paginate');
		
		$paginator = $posts->paginate($page, $per_page);
		
		$html = Layout::find('index')->render([
			'paginator' => $paginator,
		]);

		return new Response($html);
	}

	/**
	 * Post permalink
	 *
	 * @param int $year
	 * @param int $month
	 * @param int $day
	 * @param string $slug
	 * @return Response
	 */
	public function post(Request $request, ...$args)
	{
		try {
			$post = Post::find(implode('-', $args));
		} catch (\NotFoundException $e) {
			return $this->notfound();
		}

		switch ($request->get('format')) {
			case 'content':
				return $post->getContent();
			case null:
				return $post->render();
			default:
				throw new \UnexpectedValueException();
		}
	}

	/**
	 * Page permalink
	 *
	 * @param mixed $arg
	 * @return Response
	 */
	public function page(...$name)
	{
		if (is_array($name)) {
			$name = implode('/', $name);
		}

		try {
			$html = Page::find($name)->render([
				'posts' => Post::all(),
			]);
		} catch (\NotFoundException $e) {
			return $this->notfound();
		}

		return new Response($html);
	}

	/**
	 * Archives
	 *
	 * @param int $page
	 * @return Response
	 */
	public function archives($page = null)
	{
		$posts = Post::all();

		$per_page = Conf::get('archives_paginate', 30);

		$pattern = sprintf('/archives/page/:page');
		$paginator = $posts->paginate($page, $per_page, 'archives_paged');

		$html = Layout::find('archives')->render([
			'paginator' => $paginator,
		]);

		return new Response($html);
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
		
		$per_page = Conf::get('paginate');

		$paginator = $posts->paginate($page, $per_page, [
			'index_tagged' => ['tag' => rawurlencode($tag)],
		]);
		
		$html = Layout::find('index')->render([
			'paginator' => $paginator,
			'tag' => $tag,
		]);

		return new Response($html);
	}

	/**
	 * Search
	 *
	 * @return Response
	 */
	public function search(Requests\SearchRequest $request)
	{
		$query = $request->get('q');
		$page = $request->get('page');

		$posts = [];

		if ($query) {
			$posts = Post::search($query);
		}

		$per_page = Conf::get('paginate');

		if (count($posts)) {
			$route = sprintf('/search?q=%s&page=:page', rawurlencode($query));
			$paginator = $posts->paginate($page, $per_page, $route);
		} else {
			$paginator = null;
		}

		$html = Layout::find('search')->render([
			'search' => true,
			'query' => $query,
			'paginator' => $paginator,
		]);

		return new Response($html);
	}

	/**
	 * Feed
	 *
	 * @return Response
	 */
	public function feed()
	{
		if (Conf::get('cache')) {
			$atom = Cache::rememberForever('atom', function () {
				return Feed::generate();
			});
		} else {
			$atom = Feed::generate();
		}

		return new Response($atom, 200, [
			'content-type' => 'application/xml',
			'charset' => 'utf-8',
		]);
	}

	public function sitemap()
	{
		if (Conf::get('cache')) {
			$xml = Cache::rememberForever('sitemap', function () {
				return Sitemap::generate();
			});
		} else {
			$xml = Sitemap::generate();
		}

		return new Response($xml, 200, [
			'content-type' => 'application/xml',
			'charset' => 'utf-8',
		]);
	}

	/**
	 * Not Found
	 *
	 * @return Response
	 */
	public function notfound()
	{
		try {
			$html = Layout::find('errors/404')->render();
		} catch (\NotFoundException $e) {
			$html = view('errors.404');
		} finally {
			return new Response($html, 404);
		}
	}

}
