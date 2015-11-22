<?php

namespace Pochika\Layout;

use Conf;

class Theme
{
    /**
     * Get current theme's name
     *
     * @return mixed
     */
    public static function name()
    {
        return Conf::get('theme');
    }

    /**
     * Check Theme's availability
     *
     * @param bool $create_link
     * @throws \NotFoundException
     */
    public static function check()
    {
        if (!self::exists()) {
            throw new \NotFoundException('Theme not found');
        }

        if (!self::hasAssetsLink(true)) {
            //self::createAssetsLink();
            throw new \NotFoundException('Theme not found');
        }

        return true;
    }

    /**
     * Check existence of theme dir
     *
     * @throws \NotFoundException
     * @return bool
     */
    public static function exists()
    {
        return file_exists(theme_path()) && is_dir(theme_path());
    }

    /**
     * Check assets link
     *
     * @param bool $symlink
     * @return bool
     */
    public static function hasAssetsLink($create_link = false)
    {
        $asset_path = public_path('assets');
        $theme_asset_path = theme_path('assets');

        // return if theme doesn't have assets
        if (!file_exists($theme_asset_path)) {
            #todo true?
            return true;
        }

        if (file_exists($asset_path)) {
            if (is_link($asset_path) && $theme_asset_path == readlink($asset_path)) {
                return true;
            }
        }

        if ($create_link) {
            return self::createAssetsLink();
        }

        return false;
    }

    /**
     * Create assets symlink
     *
     * @return bool
     */
    public static function createAssetsLink()
    {
        $asset_path = public_path('assets');
        $theme_asset_path = theme_path('assets');

        if (!is_writable(public_path())) {
            throw new \RuntimeException('cannot create symlink (public dir is not writable)');
            //return false;
        }

        if (file_exists($asset_path) && !unlink($asset_path)) {
            throw new \RuntimeException('cannot remove asset link');
        }

        if (!symlink($theme_asset_path, $asset_path)) {
            throw new \RuntimeException('cannot create assets link');
        }

        return true;
    }
}
