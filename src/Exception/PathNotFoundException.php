<?php

namespace Amz\Config\Exception;

use Throwable;
use RuntimeException;

class PathNotFoundException extends RuntimeException
{
    /**
     * @param string $path
     * @param int $code
     * @param Throwable|null $previous
     * @return PathNotFoundException
     */
    public static function withPath(string $path, int $code = 0, Throwable $previous = null): PathNotFoundException
    {
        return new PathNotFoundException(
            sprintf('Path %s does not exist', $path),
            $code,
            $previous
        );
    }
}
