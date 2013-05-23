<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class PochikaNewPostCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'pochika:new_post';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create a new post';

    protected $time;

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
	 * @return void
	 */
	public function fire()
	{
        $this->time = time();

        $title = $this->option('title');

        if (!$title) {
            $title = $this->askTitle();
        }

        $posts_dir = app('path.posts');
        $this->checkPostsDir($posts_dir);

        $filename = $this->fixFilename($title);

        $published = $this->confirm('Published: [y|N]', false);
        $tags = $this->ask('Tags:');
        $external_url = $this->askExternalURL();

        $template = $this->getTemplate([
            'title' => $title,
            'published' => $published,
            'tags' => $tags,
            'external_url' => $external_url,
        ]);

        $path = sprintf('%s/%s.md', $posts_dir, $filename);

        file_put_contents($path, $template);
        $this->info('Successfully generated.');
        $this->info($path);

        $this->openFile($path);
	}

    protected function getUrlInfo($url)
    {
        return Embed\Embed::create(new Embed\Url($url));
    }

//    protected function _getUrlInfo($url)
//    {
//        try {
//            $essence = new fg\Essence\Essence([
//                'OEmbed/Youtube',
//                'OpenGraph/Generic',
//            ]);
//            $media = $essence->embed($url);
//        } catch (Exception $e) {
//            $media = [];
////            exit($e->getMessage());
//        }
//
//        return $media;
//    }

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

        if ($this->confirm('Open file? [Y|n]')) {
            exec(sprintf('%s %s', $cmd, $path));
        }
    }

    protected function askExternalURL()
    {
        $url = $this->ask('External URL:');

        if ($url && !preg_match('/^(ht|f)tp/', $url)) {
            $url = 'http://' . $url;
        }

        return $url;
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

    protected function fixFilename($filename)
    {
        $filename = Str::slug($filename);

        if (!$filename) {
            return $this->askFilename();
        }

        if (!preg_match('/^[\d]{4}-[\d]{2}-[\d]{2}-.*$/', $filename)) {
            $filename = date('Y-m-d-', $this->time).$filename;
        }

        if (!$this->confirm('File Name: "'.$filename.'". OK? [Y|n]', true)) {
            return $this->askFilename();
        }

        $path = sprintf('%s/%s.md', app('path.posts'), $filename);

        if (file_exists($path)) {
            $this->error('File is already exists: '.$filename);
            return $this->askFilename();
        }

        return $filename;
    }

    protected function getTemplate($params)
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

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
//			array('example', InputArgument::OPTIONAL, 'An example argument.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('title', 't', InputOption::VALUE_OPTIONAL, 'post title', null),
		);
	}

}