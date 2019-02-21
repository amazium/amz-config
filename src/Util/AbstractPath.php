<?php

namespace Amz\Config\Util;

use Amz\Core\Support\Util\Str;

abstract class AbstractPath implements Path
{
    /**
     * @var string
     */
    private $path;

    /**
     * Path constructor.
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getRealPath(): string
    {
        return (string)realpath($this->path);
    }

    /**
     * Check if this path exists
     *
     * @return bool
     */
    public function exists(): bool
    {
        return $this->getRealPath() !== '';
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getDirName(): string
    {
        return dirname($this->path);
    }

    /**
     * @return string
     */
    public function getBaseName(): string
    {
        return Str::before(basename($this->path), '.');
    }
}
