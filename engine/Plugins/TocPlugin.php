<?php namespace Pochika\Plugins;

use App\Events\AfterConvert;
use DOMDocument;
use DOMXPath;

class TocPlugin extends Plugin {
    
    protected $content;

    public function register()
    {
        $this->listen(AfterConvert::class);
    }

    public function handle(AfterConvert $event)
    {
        $this->content = &$event->entry->content;

        $escaped = $this->escape($this->content);

        if (false === strpos($this->content, '{:TOC}')) {
            if ($escaped) {
                $this->unescape($this->content);
            }
            return;
        }

        $headers = $this->collectHeader($this->content);

        if ($headers) {
            $html = '<div class="toc">'.$this->makeList($headers).'</div>';
            $this->content = str_replace('{:TOC}', $html, $this->content);
        }
        
        if ($escaped) {
            $this->unescape($this->content);
        }
    }

    protected function escape(&$text)
    {
        if (false !== strpos($text, '\{:TOC}')) {
            $text = str_replace('\{:TOC}', "<!--[toc]-->", $text);
            return true;
        }
    }
    
    protected function unescape(&$text)
    {
        $text = str_replace('<!--[toc]-->', '{:TOC}', $text);
    }

    /**
     * Collect header from specified html
     *
     * @todo deeper level
     * @return array
     */
    protected function collectHeader($html)
    {
        try {
            $dom = new DOMDocument;
            $dom->loadHTML($html);
        } catch (\ErrorException $e) {
            return;
        }

        $xpath = new DOMXPath($dom);
        # /html/body//h1|/html/body//h2|//meta|//h3|//base
        $headers = [];
        foreach ($xpath->query('/html/body//h2|//h3|') as $item) {
            if ('h2' == $item->nodeName) {
                $h2 = utf8_decode($item->nodeValue);
                $headers[] = ['name' => $h2, 'children' => []];
            } elseif ('h3' == $item->nodeName) {
                $h3 = utf8_decode($item->nodeValue);
                $headers[count($headers)-1]['children'][] = ['name' => $h3];
            }
        }

        return $headers;
    }

    protected function makeTocNum($i, $parent = null)
    {
        if ($parent) {
            return sprintf('%d.%d', $parent, $i + 1);
        } else {
            return sprintf('%d', $i + 1);
        }
    }

    /**
     * Create html list from header array
     *
     * @param $items
     * @param int $lv
     * @param string $parent_num
     * @return string|void
     */
    protected function makeList($items, $lv = 2, $parent_num = '')
    {
        if (!is_array($items)) {
            return;
        }
        
        $html = '<ul>';
        foreach ($items as $i => $item) {
            $num = $this->makeTocNum($i, $parent_num);
            $html .= '<li>';
            $html .= sprintf('<a href="#toc-%s">%s</a>', $num, e($item['name']));
            $this->tweakHeader($lv, $num, $item['name']);
            if (isset($item['children']) && $item['children']) {
                $html .= $this->makeList($item['children'], $lv + 1, $num);
            }
            $html .= '</li>';
        }
        $html .= '</ul>';
        
        return $html;
    }

    /**
     * Tweak header html to have toc num as it's ID
     *
     * @param $lv
     * @param $num
     * @param $val
     */
    protected function tweakHeader($lv, $num, $val)
    {
        $old = sprintf('<h%d>%s</h%d>', $lv, $val, $lv);
        $new = sprintf('<h%d><span id="toc-%s">%s</span></h%d>', $lv, $num, e($val), $lv);
        $this->content = str_replace($old, $new, $this->content);
    }

}
