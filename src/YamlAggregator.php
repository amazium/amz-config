<?php

namespace Amz\Config;

use Amz\Config\Util\File;
use Symfony\Component\Yaml\Yaml;

class YamlAggregator extends AbstractAggregator
{
    /**
     * @return array
     */
    public function extensions(): array
    {
        return [ 'yaml', 'yml' ];
    }

    /**
     * @param File $file
     * @return array
     */
    public function parseFile(File $file): array
    {
        return Yaml::parse($file->getContent());
    }
}
