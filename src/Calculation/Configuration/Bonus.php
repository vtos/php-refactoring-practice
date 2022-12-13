<?php
/**
 * Contains definition of a value object denoting a bonus to be applied in the down payment calculation.
 *
 * @package vtos/php-refactoring-practice
 * @author  Vitaly Potenko <potenkov@gmail.com>
 */

declare(strict_types=1);

namespace DownPaymentCalculator\Calculation\Configuration;

use DateTime;
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

    public function value(): NonNegativeFloat
    {
        return $this->value;
    }

    public function paymentAfterMonths(): NonNegativeInteger
    {
        return $this->paymentAfterMonths;
    }

    /**
     * Given a yearly usage value, the current date and using the object's validity interval property, the method can decide
     * whether the bonus is applicable for the calculation. The method is intended to encourage operating the domain's concepts,
     * which in its turn leads to increased code readability.
     */
    public function isApplicable(NonNegativeInteger $yearlyUsage, DateTime $now): bool
    {
        return $this->validityInterval->coversDate($now) && $yearlyUsage->greaterThanOrEqual($this->usageFrom);
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
