<?php
/**
 * Contains definition of a value object denoting parameters to use in the down payment calculation. Similar to the configuration
 * value object, this is a way of grouping related values together. In the initial version of the application these values
 * are marked as 'coming from customer'. We group them together based on that.
 *
 * @package vtos/php-refactoring-practice
 * @author  Vitaly Potenko <potenkov@gmail.com>
 */

declare(strict_types=1);

namespace DownPaymentCalculator\Calculation\Parameters;

use DownPaymentCalculator\Calculation\Common\NonNegativeInteger;

final class Parameters
{
    private NonNegativeInteger $yearlyUsage;

    private Vat $vat;

    public function __construct(NonNegativeInteger $yearlyUsage, Vat $vat)
    {
        $this->yearlyUsage = $yearlyUsage;
        $this->vat = $vat;
    }

    public function yearlyUsage(): NonNegativeInteger
    {
        return $this->yearlyUsage;
    }

    public function vat(): Vat
    {
        return $this->vat;
    }
}
