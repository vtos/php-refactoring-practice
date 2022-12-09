<?php
/**
 * Contains definition of the class which represents what the down payment calculation returns.
 *
 * @package vtos/php-refactoring-practice
 * @author  Vitaly Potenko <potenkov@gmail.com>
 */

declare(strict_types=1);

namespace DownPaymentCalculator\Result;

final class Result
{
    /**
     * @var Product[] $products
     */
    public array $products;
}
