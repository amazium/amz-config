<?php

namespace Amz\Config;

use Amz\Config\Util\Dir;
use Amz\Config\Util\File;
use Amz\Config\Util\Path;
use Amz\Config\Util\PathFactory;
use Amz\Core\Support\Util\Str;

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
     * Returns the root config if a file
     *
     * @return array|null
     */
    public function rootConfig(): ?array
    {
        $fileOrDir = PathFactory::fromPathString($this->path);
        if ($fileOrDir instanceof File) {
            return $this->parseFile($fileOrDir);
        }
        return null;
    }

    /**
     * @return array
     */
    public function aggregate(): array
    {
        // Check if the file/path exists
        $path = PathFactory::fromPathString($this->path);

        // Process file or path
        $result = [];
        $skipFilesInCurrentDir = false;
        if ($path instanceof File) {
            $result = $this->parseFile($path);
            $dir = new Dir($path->getDirName());
            $skipFilesInCurrentDir = true;
        } else {
            $dir = new Dir($path->getRealPath());
        }

        // Aggregate and return array
        return $this->aggregatePath($dir, $result, $skipFilesInCurrentDir);
    }

    /**
     * @param Dir $path
     * @param array $result
     * @param bool $skipFilesInCurrentDir
     * @return array
     */
    public function aggregatePath(Dir $path, array $result = [], bool $skipFilesInCurrentDir = false): array
    {
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
