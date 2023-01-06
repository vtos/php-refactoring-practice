<?php
/**
 * Contains implementation of the renderer interface to render the result of down payment calculation as JSON.
 *
 * @package vtos/php-refactoring-practice
 * @author  Vitaly Potenko <potenkov@gmail.com>
 */

declare(strict_types=1);

namespace DownPaymentCalculator\View;

use DownPaymentCalculator\Result\Result;

final class JSONRenderer implements Renderer
{
    public function render(Result $downPaymentCalculationResult): string
    {
        $dataToJson = [];
        foreach ($downPaymentCalculationResult->products as $product) {
            $productData = [
                'productName' => $product->name->asString(),
                'basePriceNet' => $product->tariffApplied->basePriceNet !== null
                    ? $product->tariffApplied->basePriceNet->asFloat()
                    : '-'
                ,
                'workingPriceNet' => $product->tariffApplied->workingPriceNet !== null
                    ? $product->tariffApplied->workingPriceNet->asFloat()
                    : '-'
                ,
            ];

            foreach ($product->monthlyPayments as $monthlyPayment) {
                $productData['downPayment'][$monthlyPayment->month] = (float) $monthlyPayment->amount;
            }

            $dataToJson[] = $productData;
        }

        return json_encode($dataToJson);
    }
}
