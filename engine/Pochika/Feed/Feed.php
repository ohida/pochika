<?php namespace Pochika\Feed;

/**
 * @codeCoverageIgnore
 */
abstract class Feed {

    const ENTRY_COUNT = 20;

    abstract public function generate();

}