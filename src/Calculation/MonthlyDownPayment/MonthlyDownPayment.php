<?php
/**
 * Contains definition of a value object denoting a monthly down payment to use in the calculation.
 *
 * @package vtos/php-refactoring-practice
 * @author  Vitaly Potenko <potenkov@gmail.com>
 */

declare(strict_types=1);

namespace DownPaymentCalculator\Calculation\MonthlyDownPayment;

use DateTime;
use DownPaymentCalculator\Calculation\Common\NonNegativeFloat;
use DownPaymentCalculator\Calculation\Common\NonNegativeInteger;
use DownPaymentCalculator\Calculation\Configuration\Bonus;
use DownPaymentCalculator\Calculation\Parameters\Vat;

final class MonthlyDownPayment
{
    private NonNegativeFloat $value;

    public function __construct(NonNegativeFloat $value)
    {
        $this->value = $value;
    }

    /**
     * @param Bonus[] $bonuses
     */
    public function applyBonuses(array $bonuses, NonNegativeInteger $yearlyUsage, DateTime $now, MonthSequenceNumber $month): self
    {
        $monthlyDownPaymentWithBonusesApplied = new self($this->value);

        foreach ($bonuses as $bonus) {
            $monthlyDownPaymentWithBonusesApplied = $monthlyDownPaymentWithBonusesApplied
                ->applyBonusIfApplicable($this, $bonus, $yearlyUsage, $now, $month);
        }

        return $monthlyDownPaymentWithBonusesApplied;
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
                $basePriceNet->asFloat() + $workingPriceNet->asFloat() * $yearlyUsage->asInteger()
            ) / $downPaymentInterval->asInteger();

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

    private function applyBonusIfApplicable(
        self $beforeBonusesApplied,
        Bonus $bonus,
        NonNegativeInteger $yearlyUsage,
        DateTime $now,
        MonthSequenceNumber $month
    ): self {
        if (!$bonus->isApplicable($yearlyUsage, $now, $month)) {
            return $this;
        }

        $monthlyDownPaymentAsFloat = $this->value->asFloat()
            - $beforeBonusesApplied->value()->asFloat() * $bonus->value()->asFloat() / 100;

        return new self(
            new NonNegativeFloat($monthlyDownPaymentAsFloat)
        );
    }
}
