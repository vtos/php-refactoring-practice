<?php
/**
 * Contains definition of the class which represents the tariff applied by the calculator for a given product.
 *
 * @package vtos/php-refactoring-practice
 * @author  Vitaly Potenko <potenkov@gmail.com>
 */

declare(strict_types=1);

namespace DownPaymentCalculator\Result;

use DownPaymentCalculator\Calculation\Common\NonNegativeFloat;

final class TariffApplied
{
    public ?NonNegativeFloat $basePriceNet;

    public ?NonNegativeFloat $workingPriceNet;
}
