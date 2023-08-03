<?php

namespace Pebble\Helpers;

/**
 * FileSystem Helper
 *
 * @author mathieu
 */
class FileSystem
{
    public static function createdir(string $dir): string
    {
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        return $dir;
    }
}
