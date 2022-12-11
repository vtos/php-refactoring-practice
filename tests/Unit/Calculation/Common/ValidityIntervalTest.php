<?php
/**
 * Contains definition of tests to cover the validity interval value object.
 *
 * @package vtos/php-refactoring-practice
 * @author  Vitaly Potenko <potenkov@gmail.com>
 */

declare(strict_types=1);

namespace DownPaymentCalculator\Tests\Unit\Calculation\Common;

use DateTime;
use DownPaymentCalculator\Calculation\Common\ValidityInterval;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ValidityIntervalTest extends TestCase
{
    public function test_it_can_be_instantiated_from_dates_as_formatted_strings(): void
    {
        $from = new DateTime();
        $from->setDate(2022, 5, 15);
        $from->setTime(0, 0);

        $until = new DateTime();
        $until->setDate(2022, 12, 15);
        $until->setTime(0, 0);

        self::assertEquals(
            new ValidityInterval($from, $until),
            ValidityInterval::fromFormattedStrings('2022-5-15', '2022-12-15')
        );
    }

    public function test_it_cannot_be_instantiated_from_an_interval_where_the_until_date_is_earlier_than_the_from_date(): void
    {
        $from = new DateTime();
        $from->setDate(2022, 5, 15);
        $from->setTime(0, 0);

        $until = new DateTime();
        $until->setDate(2022, 1, 15);
        $until->setTime(0, 0);

        self::expectException(InvalidArgumentException::class);
        new ValidityInterval($from, $until);
    }

    public function test_it_cannot_be_instantiated_from_an_interval_where_the_until_date_equals_the_from_date(): void
    {
        $from = new DateTime();
        $from->setDate(2022, 5, 15);
        $from->setTime(0, 0);

        $until = new DateTime();
        $until->setDate(2022, 5, 15);
        $until->setTime(0, 0);

        self::expectException(InvalidArgumentException::class);
        new ValidityInterval($from, $until);
    }

    public function test_it_can_tell_if_a_certain_date_lies_in_the_interval(): void
    {
        $interval = ValidityInterval::fromFormattedStrings('2022-5-15', '2022-12-15');

        self::assertTrue($interval->coversDate(new DateTime('2022-5-15')));
        self::assertTrue($interval->coversDate(new DateTime('2022-7-15')));
        self::assertTrue($interval->coversDate(new DateTime('2022-12-15')));
        self::assertFalse($interval->coversDate(new DateTime('2022-3-15')));
        self::assertFalse($interval->coversDate(new DateTime('2022-12-17')));
    }
}
