<?php
/**
 * Contains definition of tests to cover the value object denoting a non-negative float value.
 *
 * @package vtos/php-refactoring-practice
 * @author  Vitaly Potenko <potenkov@gmail.com>
 */

declare(strict_types=1);

namespace DownPaymentCalculator\Tests\Unit\Calculation\Common;

use DownPaymentCalculator\Calculation\Common\NonNegativeFloat;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class NonNegativeFloatTest extends TestCase
{
    public function test_it_can_be_instantiated_with_an_integer_value_and_return_it(): void
    {
        $nonNegativeInteger = new NonNegativeFloat(0.25);

        self::assertEquals(0.25, $nonNegativeInteger->value());
    }

    public function test_it_cannot_be_instantiated_with_a_negative_value(): void
    {
        self::expectException(InvalidArgumentException::class);
        new NonNegativeFloat(-1.2);
    }
}
