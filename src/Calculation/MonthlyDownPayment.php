<?php
/**
 * Contains definition of a value object denoting a monthly down payment to use in the calculation.
 *
 * @package vtos/php-refactoring-practice
 * @author  Vitaly Potenko <potenkov@gmail.com>
 */

declare(strict_types=1);

namespace DownPaymentCalculator\Calculation;

use DownPaymentCalculator\Calculation\Common\NonNegativeFloat;
use DownPaymentCalculator\Calculation\Common\NonNegativeInteger;
use DownPaymentCalculator\Calculation\Parameters\Vat;

final class MonthlyDownPayment
{
    private NonNegativeFloat $value;

    public function __construct(NonNegativeFloat $value)
    {
        $this->value = $value;
    }

    public function withVatIncluded(Vat $vat): self
    {
        return new self(
            new NonNegativeFloat(
                round($this->value->asFloat() + $this->value->asFloat() * $vat->asFraction()->asFloat(), 2)
            )
        );
    }

    public function value(): NonNegativeFloat
    {
        return $this->value;
    }

    public static function calculateBase(
        NonNegativeFloat $workingPriceNet,
        NonNegativeFloat $basePriceNet,
        NonNegativeInteger $yearlyUsage,
        NonNegativeInteger $downPaymentInterval
    ): self {
        $monthlyDownPaymentAsFloat = (
                $basePriceNet->asFloat() + $workingPriceNet->asFloat() * $yearlyUsage->value()
            ) / $downPaymentInterval->value();

        return new self(
            new NonNegativeFloat($monthlyDownPaymentAsFloat)
        );
    }

    public static function fromFloat(float $value): self
    {
        return new self(
            new NonNegativeFloat($value)
        );
    }
}
