<?php

namespace Amz\Config\Util;

class File extends AbstractPath
{
    /**
     * @return string
     */
    public function getContent(): string
    {
        return (string)@file_get_contents($this->getPath());
    }
}
