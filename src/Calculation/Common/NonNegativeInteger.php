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

    public function isGreaterThanOrEqual(NonNegativeInteger $valueToCompareWith): bool
    {
        return $this->value >= $valueToCompareWith->asInteger();
    }

    public function isGreaterThan(NonNegativeInteger $valueToCompareWith): bool
    {
        return $this->value > $valueToCompareWith->asInteger();
    }

    public function isPositive(): bool
    {
        return $this->value > 0;
    }

    public function asInteger(): int
    {
        return $this->value;
    }
}
