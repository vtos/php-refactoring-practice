<?php
/**
 * Contains definition of the class which represents an element of the monthly payments array.
 * The monthly payments array is part of what the down payment calculation returns.
 *
 * @package vtos/php-refactoring-practice
 * @author  Vitaly Potenko <potenkov@gmail.com>
 */

declare(strict_types=1);

namespace DownPaymentCalculator\Result;

final class MonthlyPayment
{
    public string $month;

    public string $amount;
}
