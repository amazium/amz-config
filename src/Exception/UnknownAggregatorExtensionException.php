<?php

namespace Amz\Config\Exception;

use Throwable;
use RuntimeException;

class UnknownAggregatorExtensionException extends RuntimeException
{
    /**
     * @param string $extension
     * @param int $code
     * @param Throwable|null $previous
     * @return UnknownAggregatorExtensionException
     */
    public static function withExtension(
        string $extension,
        int $code = 0,
        Throwable $previous = null
    ): UnknownAggregatorExtensionException {
        return new UnknownAggregatorExtensionException(
            sprintf('Extension %s has no aggregator implementation', $extension),
            $code,
            $previous
        );
    }

    /**
     * @param int $code
     * @param Throwable|null $previous
     * @return UnknownAggregatorExtensionException
     */
    public static function withoutExtension(
        int $code = 0,
        Throwable $previous = null
    ): UnknownAggregatorExtensionException {
        return new UnknownAggregatorExtensionException(
            'AggregatorFactory::createAggregatorByExtension expects filename with extension',
            $code,
            $previous
        );
    }
}
