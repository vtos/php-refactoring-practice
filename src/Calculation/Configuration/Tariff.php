<?php
/**
 * Contains definition of a value object denoting a tariff to use when calculating the down payment.
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

final class Tariff
{
    use DataExtraction;

    private Name $name;

    private NonNegativeInteger $usageFrom;

    private ValidityInterval $validityInterval;

    private NonNegativeFloat $workingPriceNet;

    private NonNegativeFloat $basePriceNet;

    public function __construct(
        Name $name,
        NonNegativeInteger $usageFrom,
        ValidityInterval $validityInterval,
        NonNegativeFloat $workingPriceNet,
        NonNegativeFloat $basePriceNet
    ) {
        $this->name = $name;
        $this->usageFrom = $usageFrom;
        $this->validityInterval = $validityInterval;
        $this->workingPriceNet = $workingPriceNet;
        $this->basePriceNet = $basePriceNet;
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

    public function workingPriceNet(): NonNegativeFloat
    {
        return $this->workingPriceNet;
    }

    public function basePriceNet(): NonNegativeFloat
    {
        return $this->basePriceNet;
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
            new NonNegativeFloat(self::extractFloatOrNull($data, 'workingPriceNet')),
            new NonNegativeFloat(self::extractFloatOrNull($data, 'basePriceNet'))
        );
    }
}
