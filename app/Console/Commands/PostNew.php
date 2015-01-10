<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use Pochika;

class PostNew extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'post:new';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create a new post.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		Pochika::init();

		$dry_run = $this->option('dry-run');

		//$this->comment(PHP_EOL.Inspiring::quote().PHP_EOL);
		$this->time = time();

		$posts_dir = source_path('posts');
		$this->checkPostsDir($posts_dir);

		$title = $this->option('title');

		if (!$title) {
			$title = $this->askTitle();
		}

		$filename = $this->fixFilename($title);

		$published = $this->confirm('Published: [y|N]', false);
		$tags = $this->ask('Tags:');
		$external_url = $this->askExternalURL();

		$content = $this->render([
			'title' => $title,
			'published' => $published,
			'tags' => $tags,
			'external_url' => $external_url,
		]);

		$path = sprintf('%s/%s.md', $posts_dir, $filename);

		if ($dry_run) {
			$this->info('Successfully generated. (dry-run)');
		} else {
			file_put_contents($path, $content);
			$this->info('Successfully generated.');
		}

		$this->line(str_replace(base_path().'/', '', $path));

		if (!$dry_run) {
			$this->openFile($path);
		}
	}

	protected function askTitle()
	{
		while (1) {
			if ($title = $this->ask('Post Title:')) {
				return $title;
			}
		}
	}

	protected function askFilename()
	{
		$filename = $this->ask('File Name:');
		return $this->fixFilename($filename);
	}

	protected function askExternalURL()
	{
		$url = $this->ask('External URL:');

		if ($url && !preg_match('/^(ht|f)tp/', $url)) {
			$url = 'http://' . $url;
		}

		return $url;
	}

	protected function checkPostsDir($dir)
	{
		if (!file_exists($dir)) {
			$this->error('post_dir is not exists: '.$dir);
			exit;
		}

		if (!is_writable($dir)) {
			$this->error('post_dir is not writable: '.$dir);
			exit;
		}
	}

	protected function fixFilename($filename)
	{
		$filename = str_slug($filename);

		if (!$filename) {
			return $this->askFilename();
		}

		if (!preg_match('/^[\d]{4}-[\d]{2}-[\d]{2}-.*$/', $filename)) {
			$filename = date('Y-m-d-', $this->time).$filename;
		}

		if (!$this->confirm('File Name: "'.$filename.'". OK? [Y|n]', true)) {
			return $this->askFilename();
		}

		$path = sprintf('%s/%s.md', source_path('posts'), $filename);

		if (file_exists($path)) {
			$this->error('File is already exists: '.$filename);
			return $this->askFilename();
		}

		return $filename;
	}

	protected function render($params)
	{
		$title = $params['title'];
		$published = $params['published'] ? 'true' : 'false';
		$date = date('Y-m-d H:i', $this->time);
		$tags = $params['tags'];
		$external_url = $params['external_url'];
		$content = '';

		if ($external_url) {
			$info = $this->getUrlInfo($external_url);
			if ($info) {
				if ($info->title) {
					$content .= sprintf("%s\n", $info->title);
				}
				if ($info->description) {
					$lines = explode("\n", $info->description);
					$lines = array_map(function($line) {
						return '> '.$line;
					}, $lines);
					$description = implode("\n", $lines);
					$content .= sprintf("%s\n", $description);
				}
				if ($info->code) {
					$content .= sprintf("%s\n", $info->code);
				}
			}
		}

		return <<<EOF
---
layout: post
title: "{$title}"
published: {$published}
date: {$date}
tags: [{$tags}]
external-url: {$external_url}
---

{$content}

EOF;
	}

	protected function getUrlInfo($url)
	{
		return \Embed\Embed::create($url);
	}

	protected function openFile($path)
	{
		$cmd = '';

		switch (PHP_OS) {
			case 'Darwin':
				$cmd = 'open';
				break;
			default:
				return;
		}

		$path = realpath($path);

		if (!is_readable($path)) {
			$this->error('cannot open file: '.$path);
		}
		
		if ($this->confirm('Open file? [Y|n]', true)) {
			exec(sprintf('%s "%s"', $cmd, $path));
		}
	}
	
	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			//['example', InputArgument::REQUIRED, 'An example argument.'],
		];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [
			['title', 't', InputOption::VALUE_OPTIONAL, 'post title', null],
			['dry-run', 'd', InputOption::VALUE_NONE, 'dry run'],
		];
	}

}
