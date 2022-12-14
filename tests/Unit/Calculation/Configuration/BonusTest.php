<?php
/**
 * Contains definition of tests to cover the bonus value object.
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
use DownPaymentCalculator\Calculation\Configuration\Bonus;
use DownPaymentCalculator\Calculation\MonthlyDownPayment\MonthSequenceNumber;
use Generator;
use PHPUnit\Framework\TestCase;

class BonusTest extends TestCase
{
    public function test_it_can_define_its_applicability(): void
    {
        $yearlyUsage = new NonNegativeInteger(2500);
        $date = new DateTime('2021-06-01');
        $monthNumber = new MonthSequenceNumber(
            new NonNegativeInteger(4)
        );

        $bonus = Bonus::fromArray([
            'name' => 'BONUS-C',
            'usageFrom' => 2500,
            'validFrom' => '2021-01-01',
            'validUntil' => '2022-12-31',
            'value' => 2.5,
            'paymentAfterMonths' => 3,
        ]);
        self::assertTrue($bonus->isApplicable($yearlyUsage, $date, $monthNumber));

        $bonus = Bonus::fromArray([
            'name' => 'BONUS-C',
            'usageFrom' => 2500,
            'validFrom' => '2020-01-01',
            'validUntil' => '2020-12-31',
            'value' => 2.5,
            'paymentAfterMonths' => 3,
        ]);
        self::assertFalse($bonus->isApplicable($yearlyUsage, $date, $monthNumber));

        $bonus = Bonus::fromArray([
            'name' => 'BONUS-D',
            'usageFrom' => 4500,
            'validFrom' => '2021-01-01',
            'validUntil' => '2022-12-31',
            'value' => 2.5,
            'paymentAfterMonths' => 3,
        ]);
        self::assertFalse($bonus->isApplicable($yearlyUsage, $date, $monthNumber));

        $monthNumber = new MonthSequenceNumber(
            new NonNegativeInteger(2)
        );
        self::assertFalse($bonus->isApplicable($yearlyUsage, $date, $monthNumber));
    }

    /**
     * @dataProvider arrayInputProvider
     */
    public function test_it_can_be_instantiated_from_array(array $input): void
    {
        $expectation = new Bonus(
            Name::fromString($input['name']),
            new NonNegativeInteger($input['usageFrom']),
            ValidityInterval::fromFormattedStrings($input['validFrom'], $input['validUntil']),
            new NonNegativeFloat($input['value']),
            new NonNegativeInteger($input['paymentAfterMonths'])
        );

        self::assertEquals($expectation, Bonus::fromArray($input));
    }

    public function arrayInputProvider(): Generator
    {
        yield 'normal-input' => [
            [
                'name' => 'BONUS-A',
                'usageFrom' => 0,
                'validFrom' => '2021-01-01',
                'validUntil' => '2021-12-31',
                'value' => 2.5,
                'paymentAfterMonths' => 3
            ],
        ];

        yield 'input-with-floats-looking-as-integers' => [
            [
                'name' => 'BONUS-B',
                'usageFrom' => 2500,
                'validFrom' => '2021-01-01',
                'validUntil' => '2021-12-31',
                'value' => 2,
                'paymentAfterMonths' => 6
            ],
        ];
    }
}
