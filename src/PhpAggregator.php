<?php

namespace Amz\Config;

use Amz\Config\Util\File;

class PhpAggregator extends AbstractAggregator
{
    /**
     * @return array
     */
    public function extensions(): array
    {
        return [ 'php' ];
    }

    /**
     * @param File $file
     * @return array
     */
    public function parseFile(File $file): array
    {
        return $this->requirePhpFile($file->getRealPath());
    }

    /**
     * @param string $filePath
     * @return array
     */
    public function requirePhpFile(string $filePath): array
    {
        return require_once $filePath ?: [];
    }
}
