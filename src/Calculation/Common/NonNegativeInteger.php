<?php
/**
 * Contains definition of a value object for an integer value which cannot be negative.
 *
 * @package vtos/php-refactoring-practice
 * @author  Vitaly Potenko <potenkov@gmail.com>
 */

declare(strict_types=1);

namespace DownPaymentCalculator\Calculation\Common;

use InvalidArgumentException;

final class NonNegativeInteger
{
    private int $value;

    public function __construct(int $value)
    {
        if ($value < 0) {
            throw new InvalidArgumentException('The value cannot be negative.');
        }

        $this->value = $value;
    }

    public function greaterThanOrEqual(NonNegativeInteger $valueToCompareWith): bool
    {
        return $this->value >= $valueToCompareWith->value();
    }

    public function value(): int
    {
        return $this->value;
    }
}
