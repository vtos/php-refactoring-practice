<?php
/**
 * Contains definition of a value object denoting sequence number of a month.
 *
 * @package vtos/php-refactoring-practice
 * @author  Vitaly Potenko <potenkov@gmail.com>
 */

declare(strict_types=1);

namespace DownPaymentCalculator\Calculation\MonthlyDownPayment;

use DownPaymentCalculator\Calculation\Common\NonNegativeInteger;
use InvalidArgumentException;

final class MonthSequenceNumber
{
    private NonNegativeInteger $value;

    public function __construct(NonNegativeInteger $value)
    {
        if (!$value->isPositive()) {
            throw new InvalidArgumentException('Month sequence number must be greater than 0.');
        }

        $this->value = $value;
    }

    public function value(): NonNegativeInteger
    {
        return $this->value;
    }

    public static function fromInteger(int $value): self
    {
        return new self(
            new NonNegativeInteger($value)
        );
    }
}
