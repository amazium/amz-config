<?php

namespace AmzTest\Config;

use Amz\Config\Aggregator;
use Amz\Config\AggregatorFactory;
use Amz\Config\Exception\UnknownAggregatorExtensionException;
use Amz\Config\JsonAggregator;
use Amz\Config\PhpAggregator;
use Amz\Config\YamlAggregator;
use PHPUnit\Framework\TestCase;

class AggregatorFactoryTest extends TestCase
{
    public function testCreateByPhpExtension()
    {
        $aggregator = AggregatorFactory::createAggregatorByExtension('/path/to/file.php');
        $this->assertInstanceOf(Aggregator::class, $aggregator);
        $this->assertInstanceOf(PhpAggregator::class, $aggregator);
    }

    public function testCreateByYamlExtension()
    {
        $aggregator = AggregatorFactory::createAggregatorByExtension('/path/to/file.yaml');
        $this->assertInstanceOf(Aggregator::class, $aggregator);
        $this->assertInstanceOf(YamlAggregator::class, $aggregator);
    }

    public function testCreateByYmlExtension()
    {
        $aggregator = AggregatorFactory::createAggregatorByExtension('/path/to/file.yml');
        $this->assertInstanceOf(Aggregator::class, $aggregator);
        $this->assertInstanceOf(YamlAggregator::class, $aggregator);
    }

    public function testCreateByJsonExtension()
    {
        $aggregator = AggregatorFactory::createAggregatorByExtension('/path/to/file.json');
        $this->assertInstanceOf(Aggregator::class, $aggregator);
        $this->assertInstanceOf(JsonAggregator::class, $aggregator);
    }

    public function testCreateByUnknownExtension()
    {
        $this->expectException(UnknownAggregatorExtensionException::class);
        $this->expectExceptionMessage('Extension abc has no aggregator implementation');
        AggregatorFactory::createAggregatorByExtension('/path/to/file.abc');
    }

    public function testCreateWithoutExtension()
    {
        $this->expectException(UnknownAggregatorExtensionException::class);
        $this->expectExceptionMessage('AggregatorFactory::createAggregatorByExtension expects filename with extension');
        AggregatorFactory::createAggregatorByExtension('/path/to/file');
    }
}
