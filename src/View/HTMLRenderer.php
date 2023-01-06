<?php
/**
 * Contains implementation of the renderer interface to render the result of down payment calculation as HTML.
 *
 * @package vtos/php-refactoring-practice
 * @author  Vitaly Potenko <potenkov@gmail.com>
 */

declare(strict_types=1);

namespace DownPaymentCalculator\View;

use DownPaymentCalculator\Result\Result;

final class HTMLRenderer implements Renderer
{
    public function render(Result $downPaymentCalculationResult): string
    {
        $html = "<div>";
        foreach ($downPaymentCalculationResult->products as $product) {
            $basePriceNet = $product->tariffApplied->basePriceNet !== null ? $product->tariffApplied->basePriceNet->asFloat() : '-';
            $workingPriceNet = $product->tariffApplied->workingPriceNet !== null
                ? $product->tariffApplied->workingPriceNet->asFloat()
                : '-'
            ;

            $html .= "<div>";
            $html .= "<p>Product Name: " . $product->name->asString() . "</p>";
            $html .= "<p>Tariff Base Price Net: $basePriceNet EUR</p>";
            $html .= "<p>Tariff Working Price Net: $workingPriceNet Cent</p>";
            $html .= "</div>";

            $html .= "<div>";
            foreach ($product->monthlyPayments as $monthlyPayment) {
                $html .= "<p>Monthly down payment: $monthlyPayment->month - $monthlyPayment->amount EUR</p>\n";
            }
            $html .= "</div>";
        }
        $html .= "</div>";

        return $html;
    }
}
