<?php

class GfmPlugin extends Plugin {

    public function register()
    {
        $this->listen('markdown.before_convert', 'convert');
    }

    /**
     * convert
     *
     * @param object $params
     * @return void
     * @see https://gist.github.com/koenpunt/319400
     */
    public function convert($params)
    {
        $text = &$params->md;

        $extractions = [];

        $text = $this->fencedCodeBlock($text);

        $text = $this->extractPreBlocks($text, $extractions);

        $text = $this->underscores($text);

        $text = $this->newlines($text);

        $text = $this->autolink($text);

        $text = $this->insertPreBlocks($text, $extractions);
    }

    protected function extractPreBlocks($text, &$extractions)
    {
        return preg_replace_callback('/<pre>.*?<\/pre>/s', function($matches) use (&$extractions){
            $match = $matches[0];
            $md5 = md5($match);
            $extractions[$md5] = $match;
            return "{gfm-extraction-${md5}}";
        }, $text);
    }

    protected function insertPreBlocks($text, &$extractions)
    {
        return preg_replace_callback('/\{gfm-extraction-([0-9a-f]{32})\}/', function($matches) use (&$extractions){
            $match = $matches[1];
            return "\n\n" . $extractions[$match];
        }, $text);
    }

    # prevent foo_bar_baz from ending up with an italic word in the middle
    protected function underscores($text)
    {
        // こちらのパターンだとsundownの時にmatchしない ... ?
//      return preg_replace_callback('/(^(?! {4}|\t)\w+_\w+_\w[\w_]*)/s', function($matches){
        return preg_replace_callback('/(\w+_\w+_\w[\w_]*)/s', function($matches){
            $x = $matches[0];
            $x_parts = str_split($x);
            sort($x_parts);
            if (substr(implode('', $x_parts), 0, 2) == '__') {
                return str_replace('_', '\_', $x);
            }
            return $x;
        }, $text);
    }

    # in very clear cases, let newlines become <br /> tags
    protected function newlines($text)
    {
        $res = preg_replace_callback('/^[\w\<\>][^\n]*\n+/mu', function($matches){
            $x = $matches[0];
            if (!preg_match("/\n{2}/", $x)) {
                $x = trim($x);
                $x .= "  \n";
            }
            return $x;
        }, $text);
        return $res;
    }

    protected function autolink($text)
    {
        $regex = '(http|https|ftp)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(:[a-zA-Z0-9]*)?\/?([a-zA-Z0-9\-\._\?\,\'\/\\\+&%\$#\=~])*[^\.\,\)\(\s<]';
#       $regex = '(?i)\b((?:https?://|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))';
        // URL must not be inside markdown tags already.
        // If a URL has a ", <, ( or [ in front, then don't match it.
        return preg_replace(
            '/(^|[^\[\(<"]\s*)'.'('.$regex.')/',
            '$1[$2]($2)',
            $text
        );
    }

    protected function fencedCodeBlock($text)
    {
        $regex =  '/^`{3} *([^\n]+)?\n(.+?)\n`{3}/ms';
        if (preg_match_all($regex, $text, $m)) {
            $count = count($m[0]);
            for ($i = 0; $i < $count; $i ++) {
                $lang = $m[1][$i] ?: 'generic';
                $str = trim($m[2][$i]);
                $str = htmlspecialchars($str, ENT_QUOTES|ENT_HTML5);
                $code = sprintf('<pre><code data-language="%s">%s</code></pre>', $lang, $str);
                $text = str_replace($m[0][$i], $code, $text);
            }
        }

        return $text;
    }

}
