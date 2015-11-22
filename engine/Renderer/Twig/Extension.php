<?php

namespace Pochika\Renderer\Twig;

use Conf;
use Request;
use Twig_SimpleFilter;
use Twig_SimpleFunction;
use URL;

class Extension extends \Twig_Extension
{
    public function getName()
    {
        return 'pochika';
    }

    public function getFilters()
    {
        return [
            new Twig_SimpleFilter('excerpt', [$this, 'excerpt']),
            new Twig_SimpleFilter('has_excerpt', [$this, 'hasExcerpt']),
            new Twig_SimpleFilter('titlecase', [$this, 'titlecase']),
            new Twig_SimpleFilter('paginate', [$this, 'paginate']),
            new Twig_SimpleFilter('url_decode', [$this, 'urlDecode']),
        ];
    }

    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('js_tag', [$this, 'jsTag'], ['is_safe' => ['html']]),
            new Twig_SimpleFunction('css_tag', [$this, 'cssTag'], ['is_safe' => ['html']]),
            new Twig_SimpleFunction('asset', [$this, 'asset']),
            new Twig_SimpleFunction('url', [$this, 'url']),
//            new Twig_SimpleFunction('assetic_js', [$this, 'asseticJs']),
        ];
    }

    public function urlDecode($string)
    {
        return urldecode($string);
    }

    public function excerpt($string)
    {
        if (preg_match('/<!--\s*more\s*-->/i', $string, $m)) {
            $pos = strpos($string, $m[0]);

            return substr($string, 0, $pos);
        } else {
            return $string;
        }
    }

    public function hasExcerpt($string)
    {
        return (bool)preg_match('/<!--\s*more\s*-->/i', $string);
    }

    public function jsTag($name)
    {
        if (false !== strpos($name, ',')) {
            $res = [];
            foreach (explode(',', $name) as $name) {
                $res[] = $this->jsTag(trim($name));
            }

            return implode("\n", $res);
        }

        if (':jquery' == $name) {
            $name = '//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js';
        } elseif (false === strpos($name, '//')) {
            if ('js' != pathinfo($name, PATHINFO_EXTENSION)) {
                $name .= '.js';
            }
            $name = sprintf('%s/js/%s', $this->getAssetsDir(), $name);
            //$name = $this->appendTimeStamp($name);
        }

        return sprintf('<script src="%s"></script>', $name);
    }

    public function cssTag($name)
    {
        if (false !== strpos($name, ',')) {
            $res = [];
            foreach (explode(',', $name) as $name) {
                $res[] = $this->cssTag(trim($name));
            }

            return implode("\n", $res);
        }

        if (false === strpos($name, '//')) {
            if ('css' != pathinfo($name, PATHINFO_EXTENSION)) {
                $name .= '.css';
            }
            $name = sprintf('%s/css/%s', $this->getAssetsDir(), $name);
            //$name = $this->appendTimeStamp($name);
        }

        return sprintf('<link href="%s" rel="stylesheet">', $name);
    }

    #todo cache?
    protected function appendTimeStamp($path)
    {
        $path = ltrim(str_replace(URL::to('/'), '', $path), '/');
        $fullpath = public_path($path);
        if (file_exists($fullpath)) {
            $path .= '?'.filemtime($fullpath);
        }

        return $path;
    }

    public function asset($path)
    {
        $dir = $this->getAssetsDir();

        return sprintf('%s/%s', $dir, $path);
    }

    protected function getAssetsDir()
    {
        return Url::to('assets', [], $this->secure());
    }

    protected function secure()
    {
        return Request::secure() || Request::server('HTTP_X_FORWARDED_PROTO') == 'https';
    }

    public function url($name)
    {
        return Url::to($name, [], $this->secure());
        //return url($name);
        //if (starts_with($name, ':')) {
        //    $name = substr($name, 1);
        //    return url($name);
        //    return app("url.{$name}");
        //} else {
        //    if (starts_with($name, '/')) {
        //        $name = substr($name, 1);
        //    }
        //    return app('url.root').$name;
        //}
    }

    public function paginate($posts, $arg)
    {
        if ('year' == $arg) {
            $years = [];
            foreach ($posts as $post) {
                $year = date('Y', $post['date']);
                $years[$year]['posts'][] = $post;
            }

            return $years;
        }

        throw new \InvalidArgumentException('Invalid paginate filter: '.$arg);
    }

//    public function asseticJs($output_path)
//    {
//        return;
//        $theme = Conf::get('theme');
//        $assets_dir = app('path.themes').'/assets/'.$theme;
//
//        $assets_dir = sprintf('%s/%s/assets', app('path.themes'), Conf::get('theme'));
//
//        $js = new AssetCollection([
//            new GlobAsset($assets_dir.'/js/*.js'),
//        ], [
////          new Yui\CssCompressorFilter('/usr/local/bin/yuicompressor'),
//        ]);
//
//        $path = sprintf('%s/assets', app('path.public'), $output_path);
//        $write = new AssetWriter($path);
//        $write->writeAsset($js);
//    }
}
