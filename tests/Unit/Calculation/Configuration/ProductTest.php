<?php
/**
 * Contains definition of tests to cover the product value object.
 *
 * @package vtos/php-refactoring-practice
 * @author  Vitaly Potenko <potenkov@gmail.com>
 */

declare(strict_types=1);

namespace DownPaymentCalculator\Tests\Unit\Calculation\Configuration;

use DownPaymentCalculator\Calculation\Common\Name;
use DownPaymentCalculator\Calculation\Common\ValidityInterval;
use DownPaymentCalculator\Calculation\Configuration\Product;
use DownPaymentCalculator\Calculation\Configuration\Tariff;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function test_it_can_be_instantiated_from_array(): void
    {
        $input = [
            'name' => 'Electricity Simple',
            'validFrom' => '2021-01-01',
            'validUntil' => '2022-12-31',
            'tariff' => [
                [
                    'name' => 'Tariff 1',
                    'usageFrom' => 0,
                    'validFrom' => '2021-01-01',
                    'validUntil' => '2021-12-31',
                    'workingPriceNet' => 0.20,
                    'basePriceNet' => 50.00,
                ]
            ],
        ];

        $expectation = new Product(
            Name::fromString($input['name']),
            ValidityInterval::fromFormattedStrings('2021-01-01', '2022-12-31'),
            array_map(
                fn (array $tariffAsArray): Tariff => Tariff::fromArray($tariffAsArray),
                $input['tariff']
            )
        );

        self::assertEquals($expectation, Product::fromArray($input));
    }

    public function test_it_cannot_be_instantiated_without_a_single_tariff(): void
    {
        self::expectException(InvalidArgumentException::class);
        new Product(
            Name::fromString('A Product'),
            ValidityInterval::fromFormattedStrings('2022-5-12', '2022-7-12'),
            []
        );
    }
}
