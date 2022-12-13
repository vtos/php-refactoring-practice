<?php
/**
 * Contains definition of tests to cover the tariff value object.
 *
 * @package vtos/php-refactoring-practice
 * @author  Vitaly Potenko <potenkov@gmail.com>
 */

declare(strict_types=1);

namespace DownPaymentCalculator\Tests\Unit\Calculation\Configuration;

use DateTime;
use DownPaymentCalculator\Calculation\Common\Name;
use DownPaymentCalculator\Calculation\Common\NonNegativeFloat;
use DownPaymentCalculator\Calculation\Common\NonNegativeInteger;
use DownPaymentCalculator\Calculation\Common\ValidityInterval;
use DownPaymentCalculator\Calculation\Configuration\Tariff;
use Generator;
use PHPUnit\Framework\TestCase;

class TariffTest extends TestCase
{
    public function test_it_can_define_its_applicability(): void
    {
        $yearlyUsage = new NonNegativeInteger(3500);
        $date = new DateTime('2021-06-01');

        $tariff = Tariff::fromArray([
            'name' => 'Tariff 1',
            'usageFrom' => 0,
            'validFrom' => '2021-01-01',
            'validUntil' => '2021-12-31',
            'workingPriceNet' => 0.20,
            'basePriceNet' => 50.00,
        ]);
        self::assertTrue($tariff->isApplicable($yearlyUsage, $date));

        $tariff = Tariff::fromArray([
            'name' => 'Tariff 1',
            'usageFrom' => 0,
            'validFrom' => '2020-01-01',
            'validUntil' => '2020-12-31',
            'workingPriceNet' => 0.20,
            'basePriceNet' => 50.00,
        ]);
        self::assertFalse($tariff->isApplicable($yearlyUsage, $date));

        $tariff = Tariff::fromArray([
            'name' => 'Tariff 1',
            'usageFrom' => 5001,
            'validFrom' => '2021-01-01',
            'validUntil' => '2021-12-31',
            'workingPriceNet' => 0.20,
            'basePriceNet' => 50.00,
        ]);
        self::assertFalse($tariff->isApplicable($yearlyUsage, $date));
    }

    /**
     * @dataProvider arrayInputProvider
     */
    public function test_it_can_be_instantiated_from_array(array $input): void
    {
        $expectation = new Tariff(
            Name::fromString($input['name']),
            new NonNegativeInteger($input['usageFrom']),
            ValidityInterval::fromFormattedStrings($input['validFrom'], $input['validUntil']),
            new NonNegativeFloat($input['workingPriceNet']),
            new NonNegativeFloat($input['basePriceNet'])
        );

        self::assertEquals($expectation, Tariff::fromArray($input));
    }

    public function arrayInputProvider(): Generator
    {
        yield 'normal-input' => [
            [
                'name' => 'Tariff 1',
                'usageFrom' => 0,
                'validFrom' => '2021-01-01',
                'validUntil' => '2021-12-31',
                'workingPriceNet' => 0.20,
                'basePriceNet' => 50.00,
            ],
        ];

        yield 'input-with-floats-looking-as-integers' => [
            [
                'name' => 'Tariff 1',
                'usageFrom' => 2,
                'validFrom' => '2021-01-01',
                'validUntil' => '2021-12-31',
                'workingPriceNet' => 1,
                'basePriceNet' => 50,
            ],
        ];
    }
}
