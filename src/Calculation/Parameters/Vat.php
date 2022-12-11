<?php
/**
 * Contains definition of a value object denoting VAT value.
 *
 * @package vtos/php-refactoring-practice
 * @author  Vitaly Potenko <potenkov@gmail.com>
 */

declare(strict_types=1);

namespace DownPaymentCalculator\Calculation\Parameters;

use InvalidArgumentException;

final class Vat
{
    private float $value;

    public function __construct(float $value)
    {
        if ($value < 0) {
            throw new InvalidArgumentException('Vat value cannot be negative.');
        }
        if ($value > 100) {
            throw new InvalidArgumentException('Vat value cannot exceed 100.');
        }

        $this->value = $value;
    }

    public function value(): float
    {
        return $this->value;
    }

    public static function fromFloat(float $value): self
    {
        return new self($value);
    }
}
