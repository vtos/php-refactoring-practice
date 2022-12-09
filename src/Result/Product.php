<?php
/**
 * Contains definition of the class which represents an element of the resulting array which the down payment calculation returns.
 *
 * @package vtos/php-refactoring-practice
 * @author  Vitaly Potenko <potenkov@gmail.com>
 */

declare(strict_types=1);

namespace DownPaymentCalculator\Result;

final class Product
{
    public string $productName;

    public string $basePriceNet;

    public string $workingPriceNet;

    /**
     * @var MonthlyPayment[] $monthlyPayments
     */
    public array $monthlyPayments;
}
