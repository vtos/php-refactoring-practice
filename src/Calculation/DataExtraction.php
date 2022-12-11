<?php
/**
 * Contains definition of a trait to extract the expected data from raw input (an array).
 *
 * @package vtos/php-refactoring-practice
 * @author  Vitaly Potenko <potenkov@gmail.com>
 */

declare(strict_types=1);

namespace DownPaymentCalculator\Calculation;

trait DataExtraction
{
    private static function extractIntegerOrNull(array $data, string $key): ?int
    {
        return self::extractValueOrNull($data, $key, fn ($value): bool => is_int($value));
    }

    private static function extractFloatOrNull(array $data, string $key): ?float
    {
        return self::extractValueOrNull($data, $key, fn ($value): bool => is_float($value) || is_int($value));
    }

    private static function extractStringOrNull(array $data, string $key): ?string
    {
        return self::extractValueOrNull($data, $key, fn ($value): bool => is_string($value));
    }

    /**
     * @return mixed A primitive value (int, float, etc.).
     */
    private static function extractValueOrNull(array $data, string $key, callable $hasExpectedType)
    {
        if (!isset($data[$key])) {
            return null;
        }
        if (!$hasExpectedType($data[$key])) {
            return null;
        }

        return $data[$key];
    }
}
