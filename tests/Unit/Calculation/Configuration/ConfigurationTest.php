<?php
/**
 * Contains definition of tests to cover the configuration value object.
 *
 * @package vtos/php-refactoring-practice
 * @author  Vitaly Potenko <potenkov@gmail.com>
 */

declare(strict_types=1);

namespace DownPaymentCalculator\Tests\Unit\Calculation\Configuration;

use DownPaymentCalculator\Calculation\Common\Name;
use DownPaymentCalculator\Calculation\Common\NonNegativeFloat;
use DownPaymentCalculator\Calculation\Common\NonNegativeInteger;
use DownPaymentCalculator\Calculation\Common\ValidityInterval;
use DownPaymentCalculator\Calculation\Configuration\Configuration;
use DownPaymentCalculator\Calculation\Configuration\Product;
use DownPaymentCalculator\Calculation\Configuration\Tariff;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ConfigurationTest extends TestCase
{
    public function test_it_cannot_be_instantiated_with_down_payment_interval_less_than_1(): void
    {
        self::expectException(InvalidArgumentException::class);
        new Configuration(new NonNegativeInteger(0), $this->products(), []);
    }

    public function test_it_cannot_be_instantiated_with_no_products(): void
    {
        self::expectException(InvalidArgumentException::class);
        new Configuration(new NonNegativeInteger(12), [], []);
    }

    /**
     * @return Product[]
     */
    private function products(): array
    {
        $tariff = new Tariff(
            Name::fromString('A Tariff'),
            new NonNegativeInteger(3500),
            ValidityInterval::fromFormattedStrings('2022-5-15', '2022-12-15'),
            new NonNegativeFloat(0.00),
            new NonNegativeFloat(0.00)
        );

        $products = [];
        $products[] = new Product(
            Name::fromString('A Product'),
            ValidityInterval::fromFormattedStrings('2022-5-15', '2022-12-15'),
            [$tariff]
        );

        return $products;
    }
}
