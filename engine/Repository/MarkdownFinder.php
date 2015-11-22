<?php

namespace Pochika\Repository;

use Finder;

trait MarkdownFinder
{
    protected function finder($dir = null)
    {
        if (!$dir) {
            $dir = source_path($this->dirName());
        }

        $ext = '/\.(md|markdown|mkdn?|mdown)$/';

        $finder = new Finder;
        $finder->files()->name($ext)->in($dir);

        return $finder;
    }

    /**
     * Get source dir name
     *
     * @return string
     */
    private function dirName()
    {
        return str_plural(strtolower($this->itemClass()));
    }
}
