<?php

namespace Amz\Config;

use Amz\Config\Exception\UnknownAggregatorExtensionException;
use Amz\Core\Support\Util\Str;

class AggregatorFactory
{
    /**
     * @param string $path
     * @return Aggregator
     */
    public static function createAggregatorByExtension(string $path): Aggregator
    {
        if (Str::endsWith($path, [ '.php' ])) {
            return self::createPhpAggregator($path);
        } elseif (Str::endsWith($path, [ '.yml', '.yaml' ])) {
            return self::createYamlAggregator($path);
        } elseif (Str::endsWith($path, [ '.json' ])) {
            return self::createJsonAggregator($path);
        } else {
            $dotPosition = strrpos($path, '.');
            if ($dotPosition === false) {
                throw UnknownAggregatorExtensionException::withoutExtension();
            }
            $extension = substr($path, $dotPosition + 1);
            throw UnknownAggregatorExtensionException::withExtension($extension);
        }
    }

    /**
     * @param string $path
     * @return JsonAggregator
     */
    public static function createJsonAggregator(string $path): JsonAggregator
    {
        return new JsonAggregator($path);
    }

    /**
     * @param string $path
     * @return YamlAggregator
     */
    public static function createYamlAggregator(string $path): YamlAggregator
    {
        return new YamlAggregator($path);
    }

    /**
     * @param string $path
     * @return PhpAggregator
     */
    public static function createPhpAggregator(string $path): PhpAggregator
    {
        return new PhpAggregator($path);
    }
}
