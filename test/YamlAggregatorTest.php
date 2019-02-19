<?php

namespace AmzTest\Config;

use Amz\Config\Util\File;
use Amz\Config\YamlAggregator;
use PHPUnit\Framework\TestCase;

class YamlAggregatorTest extends TestCase
{
    public function testExtensions()
    {
        $jsonAggregator = new YamlAggregator('/path/to/file.yaml');
        $this->assertTrue(in_array('yaml', $jsonAggregator->extensions()));
        $this->assertTrue(in_array('yml', $jsonAggregator->extensions()));
    }

    public function testParseFile()
    {
        $yaml = <<<YAML
this: part1
is: part2
some:
   - arr1
   - arr2
data:
   in: part3
   yaml: part4
YAML;
        $file = $this->getMockBuilder(File::class)
                     ->setConstructorArgs(([ '/path/to/my/file' ]))
                     ->setMethods([ 'getContent' ])
                     ->getMock();
        $file->expects($this->once())->method('getContent')->willReturn($yaml);

        $aggregator = new YamlAggregator('/path/to/yaml');
        /** @var File $file */
        $result = $aggregator->parseFile($file);
        $this->assertEquals('part1', $result['this']);
        $this->assertEquals('part2', $result['is']);
        $this->assertEquals(2, count($result['some']));
        $this->assertEquals('arr1', $result['some'][0]);
        $this->assertEquals('arr2', $result['some'][1]);
        $this->assertEquals(2, count($result['data']));
        $this->assertEquals('part3', $result['data']['in']);
        $this->assertEquals('part4', $result['data']['yaml']);
    }
}
