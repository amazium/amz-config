<?php

namespace Amz\Config;

use Amz\Config\Exception\PathNotFoundException;
use Amz\Config\Util\Dir;
use Amz\Config\Util\File;
use Amz\Config\Util\Path;
use Amz\Config\Util\PathFactory;
use Illuminate\Support\Str;

abstract class AbstractAggregator implements Aggregator
{
    /** @var string */
    private $path;

    /**
     * AbstractAggregator constructor.
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /***
     * returns an aray for file extensions to look out for
     *
     * @return array
     */
    abstract protected function extensions(): array;

    /**
     * Parse the file to an array
     *
     * @param File $file
     * @return array
     */
    abstract protected function parseFile(File $file): array;

    /**
     * @return array
     */
    public function aggregate(): array
    {
        // Check if the file/path exists
        $path = PathFactory::fromPathString($this->path);
        if (!$path->exists()) {
            throw PathNotFoundException::withPath($this->path);
        }

        // Process file or path
        $result = [];
        $skipFilesInCurrentDir = false;
        if ($path instanceof File) {
            $result = $this->parseFile($path);
            $path = new Dir($path->getDirName());
            $skipFilesInCurrentDir = true;
        }

        // Aggregate and return array
        if ($path instanceof Dir) {
            return $this->aggregatePath($path, $result, $skipFilesInCurrentDir);
        }
        return [];
    }

    /**
     * @param Dir $path
     * @param array $result
     * @param bool $skipFilesInCurrentDir
     * @return array
     */
    public function aggregatePath(Dir $path, array $result = [], bool $skipFilesInCurrentDir = false): array
    {
        // Check if the file/path exists
        if (!$path->exists()) {
            throw PathNotFoundException::withPath($path->getPath());
        }

        $paths = $path->getFilesAndDirs($this->extensions(), $skipFilesInCurrentDir);
        /** @var Path $subPath */
        foreach ($paths as $subPath) {
            $key = Str::camel($subPath->getBaseName());
            if ($subPath instanceof Dir) {
                $result[$key] = $this->aggregatePath($subPath);
                // When we have specified a filename, we don't process other files in dir
            } elseif ($subPath instanceof File) {
                $result[$key] = $this->parseFile($subPath);
            }
        }
        return $result;
    }
}
