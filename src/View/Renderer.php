<?php
/**
 * Contains definition of the renderer interface. An object implementing such interface is used to render the result
 * of down payment calculation.
 *
 * @package vtos/php-refactoring-practice
 * @author  Vitaly Potenko <potenkov@gmail.com>
 */

declare(strict_types=1);

namespace DownPaymentCalculator\View;

use DownPaymentCalculator\Result\Result;

interface Renderer
{
    public function render(Result $downPaymentCalculationResult): string;
}
