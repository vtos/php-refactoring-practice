<?php
/**
 * Contains definition of a value object for a float value which cannot be negative.
 *
 * @package vtos/php-refactoring-practice
 * @author  Vitaly Potenko <potenkov@gmail.com>
 */

declare(strict_types=1);

namespace DownPaymentCalculator\Calculation\Common;

use InvalidArgumentException;

final class NonNegativeFloat
{
    private float $value;

    public function __construct(float $value)
    {
        if ($value < 0) {
            throw new InvalidArgumentException('The value cannot be negative.');
        }

        $this->value = $value;
    }

    public function value(): float
    {
        return $this->value;
    }
}
