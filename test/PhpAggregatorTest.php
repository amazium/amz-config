<?php

namespace AmzTest\Config;

use Amz\Config\PhpAggregator;
use Amz\Config\Util\File;
use PHPUnit\Framework\TestCase;

class PhpAggregatorTest extends TestCase
{
    public function testExtensions()
    {
        $jsonAggregator = new PhpAggregator('/path/to/file.php');
        $this->assertTrue(in_array('php', $jsonAggregator->extensions()));
    }

    public function testParseFile()
    {
        $arr = [
            "this" => "part1",
            "is" => "part2",
            "some" => [
                "arr1",
                "arr2",
            ],
            "data" => [
                "in" => "part3",
                "php" => "part4",
            ],
        ];

        $phpAggregator = $this->getMockBuilder(PhpAggregator::class)
                     ->setConstructorArgs([ '/path/to/php' ])
                     ->setMethods([ 'requirePhpFile' ])
                     ->getMock();
        $phpAggregator->expects($this->once())->method('requirePhpFile')->willReturn($arr);

        /** @var PhpAggregator $phpAggregator */
        $result = $phpAggregator->parseFile(new File(__FILE__));
        $this->assertEquals('part1', $result['this']);
        $this->assertEquals('part2', $result['is']);
        $this->assertEquals(2, count($result['some']));
        $this->assertEquals('arr1', $result['some'][0]);
        $this->assertEquals('arr2', $result['some'][1]);
        $this->assertEquals(2, count($result['data']));
        $this->assertEquals('part3', $result['data']['in']);
        $this->assertEquals('part4', $result['data']['php']);
    }

    public function testRequirePhpFile()
    {
        $filename = tempnam('/tmp', 'test_');
        file_put_contents($filename, '<?php' . PHP_EOL . 'return [ "x", "y", "z" ];');
        $aggregator = new PhpAggregator($filename);
        $actual = $aggregator->requirePhpFile($filename);
        $this->assertEquals(3, count($actual));
        $this->assertEquals('x', $actual[0]);
        $this->assertEquals('y', $actual[1]);
        $this->assertEquals('z', $actual[2]);
        unlink($filename);
    }
}
