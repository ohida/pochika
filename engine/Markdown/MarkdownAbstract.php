<?php namespace Pochika\Markdown;

use Event;

abstract class MarkdownAbstract {

    public function parse($md)
    {
        $event_params = (object) ['md' => &$md];

        //Event::fire('markdown.before_convert', $event_params);

        $html = $this->run($md);

        //Event::fire('markdown.after_convert', $event_params);

        return $html;
    }

    /**
     * @codeCoverageIgnore
     */
    abstract public function run($md);

}
