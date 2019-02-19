<?php

namespace Amz\Config;

use Amz\Config\Util\Dir;

interface Aggregator
{
    public function __construct(string $path);

    public function aggregate(): array;

    public function aggregatePath(Dir $path, array $result = [], bool $skipFilesInCurrentDir = false): array;
}
