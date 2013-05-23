<?php namespace Pochika\Markdown;

use Event;

abstract class MarkdownAbstract implements MarkdownInterface {

    public function convert($md)
    {
        $event_params = (object) ['md' => &$md];

        Event::fire('markdown.before_convert', $event_params);

        $html = $this->translate($md);

        Event::fire('markdown.after_convert', $event_params);

        return $html;
    }

    /**
     * @codeCoverageIgnore
     */
    abstract public function translate($md);

}
