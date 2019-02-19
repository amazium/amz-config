<?php

namespace AmzTest\Config\Util;

use Amz\Config\Util\File;
use PHPStan\Testing\TestCase;

class FileTest extends TestCase
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
        $file = new File(array_keys($this->files)[0]);
        $this->assertEquals(array_keys($this->files)[0], $file->getPath());
    }

    public function testExistingPath()
    {
        $file = new File(array_keys($this->files)[0]);
        $this->assertTrue($file->exists());
    }

    public function testNonExistingPath()
    {
        $path = $this->generatePath() . '/test.php';
        $file = new File($path);
        $this->assertFalse($file->exists());
    }

    public function testGetDirName()
    {
        $file = new File(array_keys($this->files)[0]);
        $this->assertNotEmpty($file->getDirName());
        $this->assertEquals($this->basePath, $file->getDirName());
    }

    public function testGetBaseName()
    {
        $file = new File(array_keys($this->files)[0]);
        $this->assertNotEmpty($file->getBaseName());
        $this->assertEquals('test1', $file->getBaseName());
    }

    public function testGetContent()
    {
        $filename = array_keys($this->files)[0];
        $file = new File($filename);
        $actual = $file->getContent();
        $expected = $this->files[$filename];
        $this->assertEquals($expected, $actual);
    }
}
