<?php namespace Pochika\Feed;

/**
 * @codeCoverageIgnore
 */
abstract class Feed {

    const ENTRY_COUNT = 25;

    abstract public function generate();

}