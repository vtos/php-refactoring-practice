<?php
/**
 * Contains definition of tests to cover the month sequence number value object.
 *
 * @package vtos/php-refactoring-practice
 * @author  Vitaly Potenko <potenkov@gmail.com>
 */

namespace DownPaymentCalculator\Tests\Unit\Calculation\MonthlyDownPayment;

use DownPaymentCalculator\Calculation\Common\NonNegativeInteger;
use DownPaymentCalculator\Calculation\MonthlyDownPayment\MonthSequenceNumber;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class MonthSequenceNumberTest extends TestCase
{
    public function test_it_cannot_be_instantiated_with_a_zero_value(): void
    {
        self::expectException(InvalidArgumentException::class);
        new MonthSequenceNumber(
            new NonNegativeInteger(0)
        );
    }

    public function test_it_can_be_instantiated_from_an_integer_value(): void
    {
        $expectation = new MonthSequenceNumber(
            new NonNegativeInteger(1)
        );
        self::assertEquals($expectation, MonthSequenceNumber::fromInteger(1));
    }
}
