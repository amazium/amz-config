<?php

namespace AmzTest\Config\Util;

use Amz\Config\Exception\PathNotFoundException;
use Amz\Config\Util\Dir;
use Amz\Config\Util\File;
use Amz\Config\Util\PathFactory;
use PHPUnit\Framework\TestCase;

class PathFactoryTest extends TestCase
{
    private function generatePath(): string
    {
        return __DIR__ . '/../../data/tmp/' . str_replace('.', '', date('YmdHis_' . microtime(true)));
    }

    public function testFromPathStringWithExistingDir()
    {
        $path = $this->generatePath();
        mkdir($path, 0755, true);
        $actual = PathFactory::fromPathString($path);
        $this->assertInstanceOf(Dir::class, $actual);
        rmdir($path);
    }

    public function testFromPathStringWithNonExistingDir()
    {
        $this->expectException(PathNotFoundException::class);
        $path = $this->generatePath();
        PathFactory::fromPathString($path);
    }

    public function testFromPathStringWithExistingFile()
    {
        $path = $this->generatePath();
        mkdir($path, 0755, true);
        file_put_contents($path . '/test.json', '{}');
        $actual = PathFactory::fromPathString($path . '/test.json');
        $this->assertInstanceOf(File::class, $actual);
        unlink($path . '/test.json');
        rmdir($path);
    }

    public function testFromPathStringWithNonExistingFile()
    {
        $this->expectException(PathNotFoundException::class);
        $path = $this->generatePath();
        PathFactory::fromPathString($path . '/test.json');
    }
}
