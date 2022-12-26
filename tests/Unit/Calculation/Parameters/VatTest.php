<?php
/**
 * Contains definition of tests to cover the VAT value object.
 *
 * @package vtos/php-refactoring-practice
 * @author  Vitaly Potenko <potenkov@gmail.com>
 */

declare(strict_types=1);

namespace DownPaymentCalculator\Tests\Unit\Calculation\Parameters;

use DownPaymentCalculator\Calculation\Common\NonNegativeFloat;
use DownPaymentCalculator\Calculation\Parameters\Vat;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class VatTest extends TestCase
{
    public function test_it_can_be_instantiated_from_a_float_value(): void
    {
        $expected = new Vat(
            new NonNegativeFloat(15.45)
        );
        self::assertEquals($expected, Vat::fromFloat(15.45));

        $expected = new Vat(
            new NonNegativeFloat(100.00)
        );
        self::assertEquals($expected, Vat::fromFloat(100.00));
    }

    public function test_it_cannot_be_instantiated_from_a_value_higher_than_100(): void
    {
        self::expectException(InvalidArgumentException::class);
        Vat::fromFloat(100.1);
    }

    public function test_it_can_be_converted_to_fraction(): void
    {
        self::assertEquals(new NonNegativeFloat(0.19), Vat::fromFloat(19.00)->asFraction());
    }
}
