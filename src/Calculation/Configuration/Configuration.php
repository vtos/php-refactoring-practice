<?php
/**
 * Contains definition of a value object denoting configuration values to use in the down payment calculation. This is a way of
 * grouping related values together. In the initial version of the application these values are marked as 'system settings'. Thus,
 * this is possibly some stored or predefined values to use for the calculation.
 *
 * @package vtos/php-refactoring-practice
 * @author  Vitaly Potenko <potenkov@gmail.com>
 */

declare(strict_types=1);

namespace DownPaymentCalculator\Calculation\Configuration;

use DownPaymentCalculator\Calculation\Common\NonNegativeInteger;
use InvalidArgumentException;

final class Configuration
{
    private NonNegativeInteger $downPaymentInterval;

    /**
     * @var Product[] $products
     */
    private array $products;

    /**
     * @var Bonus[] $bonuses
     */
    private array $bonuses;

    /**
     * @param NonNegativeInteger $downPaymentInterval
     * @param Product[] $products
     * @param Bonus[] $bonuses
     */
    public function __construct(NonNegativeInteger $downPaymentInterval, array $products, array $bonuses)
    {
        if ($downPaymentInterval->asInteger() < 1) {
            throw new InvalidArgumentException('Down payment interval cannot be less than 1.');
        }
        if ($products === []) {
            throw new InvalidArgumentException('At least one product is required.');
        }

        $this->downPaymentInterval = $downPaymentInterval;
        $this->products = $products;
        $this->bonuses = $bonuses;
    }

    public function downPaymentInterval(): NonNegativeInteger
    {
        return $this->downPaymentInterval;
    }

    /**
     * @return Product[]
     */
    public function products(): array
    {
        return $this->products;
    }

    /**
     * @return Bonus[]
     */
    public function bonuses(): array
    {
        return $this->bonuses;
    }
}
