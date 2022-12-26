<?php
/**
 * Contains definition of tests to cover the monthly down payment value object.
 *
 * @package vtos/php-refactoring-practice
 * @author  Vitaly Potenko <potenkov@gmail.com>
 */

declare(strict_types=1);

namespace DownPaymentCalculator\Tests\Unit\Calculation;

use DownPaymentCalculator\Calculation\Common\NonNegativeFloat;
use DownPaymentCalculator\Calculation\Common\NonNegativeInteger;
use DownPaymentCalculator\Calculation\MonthlyDownPayment;
use DownPaymentCalculator\Calculation\Parameters\Vat;
use PHPUnit\Framework\TestCase;

class MonthlyDownPaymentTest extends TestCase
{
    public function test_it_can_be_instantiated_with_a_base_value_calculated(): void
    {
        $monthlyDownPayment = MonthlyDownPayment::calculateBase(
            new NonNegativeFloat(0.20),
            new NonNegativeFloat(50.00),
            new NonNegativeInteger(3500),
            new NonNegativeInteger(12)
        );

        self::assertEquals(MonthlyDownPayment::fromFloat(62.5), $monthlyDownPayment);
    }

    public function test_it_can_be_added_with_vat_applied(): void
    {
        $monthlyDownPayment = MonthlyDownPayment::calculateBase(
            new NonNegativeFloat(0.20),
            new NonNegativeFloat(50.00),
            new NonNegativeInteger(3500),
            new NonNegativeInteger(12)
        );

        self::assertEquals(
            MonthlyDownPayment::fromFloat(74.38),
            $monthlyDownPayment->withVatIncluded(Vat::fromFloat(19.00))
        );
    }
}
