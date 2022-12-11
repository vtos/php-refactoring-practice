<?php
/**
 * Contains definition of a value object denoting a bonus to be applied in the down payment calculation.
 *
 * @package vtos/php-refactoring-practice
 * @author  Vitaly Potenko <potenkov@gmail.com>
 */

declare(strict_types=1);

namespace DownPaymentCalculator\Calculation\Configuration;

use DownPaymentCalculator\Calculation\Common\Name;
use DownPaymentCalculator\Calculation\Common\NonNegativeFloat;
use DownPaymentCalculator\Calculation\Common\NonNegativeInteger;
use DownPaymentCalculator\Calculation\Common\ValidityInterval;
use DownPaymentCalculator\Calculation\DataExtraction;

final class Bonus
{
    use DataExtraction;

    private Name $name;

    private NonNegativeInteger $usageFrom;

    private ValidityInterval $validityInterval;

    private NonNegativeFloat $value;

    private NonNegativeInteger $paymentAfterMonths;

    public function __construct(
        Name $name,
        NonNegativeInteger $usageFrom,
        ValidityInterval $validityInterval,
        NonNegativeFloat $value,
        NonNegativeInteger $paymentAfterMonths
    ) {
        $this->name = $name;
        $this->usageFrom = $usageFrom;
        $this->validityInterval = $validityInterval;
        $this->value = $value;
        $this->paymentAfterMonths = $paymentAfterMonths;
    }

    public function name(): Name
    {
        return $this->name;
    }

    public function usageFrom(): NonNegativeInteger
    {
        return $this->usageFrom;
    }

    public function validityInterval(): ValidityInterval
    {
        return $this->validityInterval;
    }

    public function value(): NonNegativeFloat
    {
        return $this->value;
    }

    public function paymentAfterMonths(): NonNegativeInteger
    {
        return $this->paymentAfterMonths;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            Name::fromString(self::extractStringOrNull($data, 'name')),
            new NonNegativeInteger(self::extractIntegerOrNull($data, 'usageFrom')),
            ValidityInterval::fromFormattedStrings(
                self::extractStringOrNull($data, 'validFrom'),
                self::extractStringOrNull($data, 'validUntil')
            ),
            new NonNegativeFloat(self::extractFloatOrNull($data, 'value')),
            new NonNegativeInteger(self::extractIntegerOrNull($data, 'paymentAfterMonths'))
        );
    }
}
