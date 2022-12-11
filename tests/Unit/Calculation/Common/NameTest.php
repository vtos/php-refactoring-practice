<?php
/**
 * Contains definition of tests to cover the name value object.
 *
 * @package vtos/php-refactoring-practice
 * @author  Vitaly Potenko <potenkov@gmail.com>
 */

declare(strict_types=1);

namespace DownPaymentCalculator\Tests\Unit\Calculation\Common;

use DownPaymentCalculator\Calculation\Common\Name;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class NameTest extends TestCase
{
    public function test_it_can_be_instantiated_from_a_string(): void
    {
        self::assertEquals(new Name('A Product'), Name::fromString('A Product'));
    }

    public function test_it_can_return_its_value_as_a_string(): void
    {
        $name = new Name('A Product');

        self::assertEquals('A Product', $name->asString());
    }

    public function test_it_fails_to_instantiate_from_an_empty_string(): void
    {
        self::expectException(InvalidArgumentException::class);
        new Name('');
    }

    public function test_it_fails_to_instantiate_from_a_not_meaningful_string(): void
    {
        self::expectException(InvalidArgumentException::class);
        new Name('        
        ');
    }
}
