<?php
/**
 * Contains definition of tests to cover the VAT value object.
 *
 * @package vtos/php-refactoring-practice
 * @author  Vitaly Potenko <potenkov@gmail.com>
 */

declare(strict_types=1);

namespace DownPaymentCalculator\Tests\Unit\Calculation\Parameters;

use DownPaymentCalculator\Calculation\Parameters\Vat;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class VatTest extends TestCase
{
    public function test_it_can_be_instantiated_from_a_float_value(): void
    {
        self::assertEquals(new Vat(15.45), Vat::fromFloat(15.45));
    }

    public function test_it_cannot_be_instantiated_from_a_negative_value(): void
    {
        self::expectException(InvalidArgumentException::class);
        new Vat(-15);
    }

    public function test_it_cannot_be_instantiated_from_a_value_higher_than_100(): void
    {
        self::expectException(InvalidArgumentException::class);
        new Vat(100.1);
    }
}
