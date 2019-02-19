<?php


namespace Amz\Config\Util;

use Amz\Config\Exception\PathNotFoundException;

class PathFactory
{
    /**
     * @param string $path
     * @return Path
     */
    public static function fromPathString(string $path): Path
    {
        if (is_file($path)) {
            return new File($path);
        } elseif (is_dir($path)) {
            return new Dir($path);
        }
        throw PathNotFoundException::withPath($path);
    }
}
