<?php

namespace Amz\Config;

use Amz\Config\Util\File;

class JsonAggregator extends AbstractAggregator
{
    /**
     * @return array
     */
    public function extensions(): array
    {
        return [
            'json'
        ];
    }

    /**
     * @param File $file
     * @return array
     */
    public function parseFile(File $file): array
    {
        return json_decode($file->getContent(), true) ?? [];
    }
}
