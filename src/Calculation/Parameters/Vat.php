<?php
/**
 * Contains definition of a value object denoting VAT value.
 *
 * @package vtos/php-refactoring-practice
 * @author  Vitaly Potenko <potenkov@gmail.com>
 */

declare(strict_types=1);

namespace DownPaymentCalculator\Calculation\Parameters;

use DownPaymentCalculator\Calculation\Common\NonNegativeFloat;
use InvalidArgumentException;

final class Vat
{
    private NonNegativeFloat $value;

    public function __construct(NonNegativeFloat $value)
    {
        if ($value->asFloat() > 100) {
            throw new InvalidArgumentException('Vat value cannot exceed 100.');
        }

        $this->value = $value;
    }

    public function value(): NonNegativeFloat
    {
        return $this->value;
    }

    public function asFraction(): NonNegativeFloat
    {
        return new NonNegativeFloat($this->value->asFloat() / 100);
    }

    public static function fromFloat(float $value): self
    {
        return new self(
            new NonNegativeFloat($value)
        );
    }
}
