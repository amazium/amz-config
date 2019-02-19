<?php

namespace AmzTest\Config;

use Amz\Config\AbstractAggregator;
use Amz\Config\Exception\PathNotFoundException;
use Amz\Config\JsonAggregator;
use PHPStan\Testing\TestCase;

class AbstractAggregatorTest extends TestCase
{
    protected $base;

    protected $baseConfig;

    public function setUp(): void
    {
        $this->base = '/tmp/' . date('YmdHis') . '/';
        mkdir($this->base . '/d', 0755, true);
        $this->baseConfig = $this->base . 'test.json';
        file_put_contents($this->baseConfig, '{ "a": 1, "b": 2, "c" : [ 3, 4 ] }');
        file_put_contents($this->base . 'd/e.json', '[ 5, 6 ]');
        file_put_contents($this->base . 'd/f.json', '{ "g": 7 }');
    }

    public function tearDown(): void
    {
        unlink($this->baseConfig);
        unlink($this->base . 'd/e.json');
        unlink($this->base . 'd/f.json');
        rmdir($this->base . 'd');
        rmdir($this->base);
    }

    public function testAggregateUnknownPath()
    {
        $mock = $this->getMockBuilder(AbstractAggregator::class)
                     ->setConstructorArgs([ '/non/existing/path' ])
                     ->setMethods([ 'aggregatePath', 'parseFile', 'extensions' ])
                     ->getMock();
        $mock->expects($this->any())->method('aggregatePath')->willReturn([]);
        $mock->expects($this->any())->method('parseFile')->willReturn([]);
        $mock->expects($this->any())->method('extensions')->willReturn([ 'json ']);

        $this->expectException(PathNotFoundException::class);
        $this->expectExceptionMessage('Path /non/existing/path does not exist');
        /** @var AbstractAggregator $mock */
        $mock->aggregate();
    }

    public function testAggregateKnown()
    {
        $json = new JsonAggregator($this->baseConfig);
        $result = $json->aggregate();

        $this->assertArrayHasKey('a', $result);
        $this->assertEquals(1, $result['a']);
        $this->assertArrayHasKey('b', $result);
        $this->assertEquals(2, $result['b']);
        $this->assertArrayHasKey('c', $result);
        $this->assertEquals([ 3, 4 ], $result['c']);

        $this->assertArrayHasKey('d', $result);
        $this->assertArrayHasKey('e', $result['d']);
        $this->assertEquals([ 5, 6 ], $result['d']['e']);
        $this->assertArrayHasKey('f', $result['d']);
        $this->assertArrayHasKey('g', $result['d']['f']);
        $this->assertEquals(7, $result['d']['f']['g']);
    }

    public function testAggregateKnownPath()
    {
        $json = new JsonAggregator($this->base);
        $result = $json->aggregate();

        $this->assertArrayHasKey('test', $result);
        $this->assertArrayHasKey('a', $result['test']);
        $this->assertEquals(1, $result['test']['a']);
        $this->assertArrayHasKey('b', $result['test']);
        $this->assertEquals(2, $result['test']['b']);
        $this->assertArrayHasKey('c', $result['test']);
        $this->assertEquals([ 3, 4 ], $result['test']['c']);

        $this->assertArrayHasKey('d', $result);
        $this->assertArrayHasKey('e', $result['d']);
        $this->assertEquals([ 5, 6 ], $result['d']['e']);
        $this->assertArrayHasKey('f', $result['d']);
        $this->assertArrayHasKey('g', $result['d']['f']);
        $this->assertEquals(7, $result['d']['f']['g']);
    }
}
