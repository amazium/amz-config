<?php

namespace Amz\Config\Util;

class Dir extends AbstractPath
{
    /**
     * @param array $allowedExtensions
     * @param bool $skipFilesInCurrentDir
     * @return array
     */
    public function getFilesAndDirs(array $allowedExtensions = [], bool $skipFilesInCurrentDir = true): array
    {
        $return = [];
        $files = new \DirectoryIterator($this->getPath());
        foreach ($files as $file) {
            if ($file->isDot()) {
                continue;
            }
            $thisPath = (string)$file->getRealPath();
            if ($file->isDir()) {
                $return[$thisPath] = new Dir($thisPath);
                // When we have specified a filename, we don't process other files in dir
            } elseif (!$skipFilesInCurrentDir && in_array($file->getExtension(), $allowedExtensions, true)) {
                $return[$thisPath] = new File($thisPath);
            }
        }
        return $return;
    }
}
