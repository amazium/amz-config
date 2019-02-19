<?php

namespace AmzTest\Config\Util;

use Amz\Config\Util\Dir;
use Amz\Config\Util\Path;
use PHPStan\Testing\TestCase;

class DirTest extends TestCase
{
    private $basePath;

    private $dirs = [];

    private $files = [];

    public function setUp(): void
    {
        $this->basePath = $this->generatePath();
        mkdir($this->basePath, 0755, true);
        $this->basePath = realpath($this->basePath);
        $this->dirs = [
            $this->basePath . '/sub',
        ];
        $this->files = [
            $this->basePath . '/test1.json' => '{ "a": 1 }',
            $this->basePath . '/test2.json' => '{ "b": 2 }',
            $this->basePath . '/test1.php' => '<?php' . PHP_EOL . 'return [ "b" => 2 ];',
            $this->basePath . '/sub/test2.json' => '{ "c": 3 }',
        ];
        foreach ($this->dirs as $key => $dir) {
            mkdir($dir, 0755, true);
        }
        foreach ($this->files as $file => $content) {
            file_put_contents($file, $content);
        }
    }

    public function tearDown(): void
    {
        foreach ($this->files as $file => $content) {
            unlink($file);
        }
        foreach ($this->dirs as $dir) {
            rmdir($dir);
        }
        rmdir($this->basePath);
    }

    private function generatePath(): string
    {
        return __DIR__ . '/../../data/tmp/' . str_replace('.', '', date('YmdHis_' . microtime(true)));
    }

    public function testConstructor()
    {
        $dir = new Dir($this->basePath);
        $this->assertEquals($this->basePath, $dir->getPath());
    }

    public function testExistingPath()
    {
        $dir = new Dir($this->basePath);
        $this->assertTrue($dir->exists());
    }

    public function testNonExistingPath()
    {
        $path = $this->generatePath();
        $dir = new Dir($path);
        $this->assertFalse($dir->exists());
    }

    public function testGetDirName()
    {
        $dir = new Dir($this->basePath . '/sub');
        $this->assertNotEmpty($dir->getDirName());
        $this->assertEquals($this->basePath, $dir->getDirName());
    }

    public function testGetBaseName()
    {
        $dir = new Dir($this->basePath . '/sub');
        $this->assertNotEmpty($dir->getBaseName());
        $this->assertEquals('sub', $dir->getBaseName());
    }

    public function testGetFilesAndDirsSkip()
    {
        $dir = new Dir($this->basePath);
        $result = $dir->getFilesAndDirs([ 'php' ], true);
        $expected = [
            $this->basePath . '/sub',
        ];
        $this->assertEquals(count($expected), count($result));
        /** @var Path $p */
        foreach ($result as $p) {
            $this->assertTrue(in_array($p->getRealPath(), $expected));
        }
    }

    public function testGetFilesAndDirsJsonExtNoSkip()
    {
        $dir = new Dir($this->basePath);
        $result = $dir->getFilesAndDirs([ 'json' ], false);
        $expected = [
            $this->basePath . '/sub',
            $this->basePath . '/test1.json',
            $this->basePath . '/test2.json',
        ];
        $this->assertEquals(count($expected), count($result));
        /** @var Path $p */
        foreach ($result as $p) {
            $this->assertTrue(in_array($p->getRealPath(), $expected));
        }
    }

    public function testGetFilesAndDirsPhpExtNoSkip()
    {
        $dir = new Dir($this->basePath);
        $result = $dir->getFilesAndDirs([ 'php' ], false);
        $expected = [
            $this->basePath . '/sub',
            $this->basePath . '/test1.php',
        ];
        $this->assertEquals(count($expected), count($result));
        /** @var Path $p */
        foreach ($result as $p) {
            $this->assertTrue(in_array($p->getRealPath(), $expected));
        }
    }
}
