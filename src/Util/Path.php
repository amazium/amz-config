<?php

namespace Amz\Config\Util;

interface Path
{
    public function exists(): bool;

    public function getRealPath(): string;

    public function getPath(): string;

    public function getDirName(): string;

    public function getBaseName(): string;
}
