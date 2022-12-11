<?php
/**
 * Contains definition of tests to cover the value object denoting a non-negative integer value.
 *
 * @package vtos/php-refactoring-practice
 * @author  Vitaly Potenko <potenkov@gmail.com>
 */

declare(strict_types=1);

namespace DownPaymentCalculator\Tests\Unit\Calculation\Common;

use DownPaymentCalculator\Calculation\Common\NonNegativeInteger;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class NonNegativeIntegerTest extends TestCase
{
    public function test_it_can_be_instantiated_with_an_integer_value_and_return_it(): void
    {
        $nonNegativeInteger = new NonNegativeInteger(5);

        self::assertEquals(5, $nonNegativeInteger->value());
    }

    public function test_it_cannot_be_instantiated_with_a_negative_value(): void
    {
        self::expectException(InvalidArgumentException::class);
        new NonNegativeInteger(-1);
    }

    public function test_it_can_be_compared_with_other_values_to_define_if_it_is_greater_than_those_or_equal(): void
    {
        $nonNegativeInteger = new NonNegativeInteger(100);

        self::assertTrue($nonNegativeInteger->greaterThanOrEqual(new NonNegativeInteger(100)));
        self::assertTrue($nonNegativeInteger->greaterThanOrEqual(new NonNegativeInteger(99)));
        self::assertFalse($nonNegativeInteger->greaterThanOrEqual(new NonNegativeInteger(101)));
    }
}
