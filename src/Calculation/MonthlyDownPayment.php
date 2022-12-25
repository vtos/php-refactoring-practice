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

final class MonthlyDownPayment
{
    private NonNegativeFloat $value;

    private function __construct(NonNegativeFloat $value)
    {
        $this->value = $value;
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

    public function value(): NonNegativeFloat
    {
        return $this->value;
    }
}
