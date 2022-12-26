<?php
/**
 * Contains definition of the calculation service class.
 *
 * @package vtos/php-refactoring-practice
 * @author  Vitaly Potenko <potenkov@gmail.com>
 */

declare(strict_types=1);

namespace DownPaymentCalculator\Service;

use DateTime;
use DownPaymentCalculator\Calculation\Configuration\Configuration;
use DownPaymentCalculator\Calculation\MonthlyDownPayment;
use DownPaymentCalculator\Calculation\Parameters\Parameters;
use DownPaymentCalculator\Result\MonthlyPayment;
use DownPaymentCalculator\Result\Product;
use DownPaymentCalculator\Result\Result;

final class DownPaymentCalculator
{
    public function calculate(Parameters $parameters, Configuration $configuration, DateTime $now): Result
    {
        $data = [];

        foreach ($configuration->products() as $i => $product) {
            if ($product->validityInterval()->coversDate($now)) {
                $data['products'][$i]['productName'] = $product->name()->asString();
                foreach ($product->tariffs() as $tariff) {
                    if ($tariff->isApplicable($parameters->yearlyUsage(), $now)) {
                        $data['products'][$i]['tariff'] = $tariff;
                        $data['products'][$i]['workingPriceNet'] = $tariff->workingPriceNet();
                        $data['products'][$i]['basePriceNet'] = $tariff->basePriceNet();
                    }
                }
            }
        }

        // check for valid bonus
        $bonuses = [];
        foreach ($configuration->bonuses() as $bonus) {
            if ($bonus->isApplicable($parameters->yearlyUsage(), $now)) {
                $bonuses[] = $bonus;
            }
        }

        foreach ($data['products'] as $product_i => $product) {
            if (!empty($product['tariff'])) {
                $monthlyDownPayment = MonthlyDownPayment::calculateBase(
                    $product['workingPriceNet'],
                    $product['basePriceNet'],
                    $parameters->yearlyUsage(),
                    $configuration->downPaymentInterval()
                )->value();

                $data['products'][$product_i]['monthlyPayments'] = [];
                for ($i = 1; $i <= $configuration->downPaymentInterval()->value(); $i++) {
                    $mPayment = $monthlyDownPayment->asFloat();
                    foreach ($bonuses as $bonus) {
                        if ($i > $bonus->paymentAfterMonths()->value()) {
                            // add here the bonus on the staring monthly down payment, not the resulted
                            $mPayment -= ($monthlyDownPayment->asFloat() * ($bonus->value()->asFloat() / 100));
                        }
                    }
                    $data['products'][$product_i]['monthlyPayments'][$i] = MonthlyDownPayment::fromFloat($mPayment)
                        ->withVatIncluded($parameters->vat())
                        ->value()
                        ->asFloat()
                    ;
                }
            }
        }

        return $this->buildResult($data['products']);
    }

    private function buildResult(array $productsData): Result
    {
        $result = new Result();
        $result->products = [];

        foreach ($productsData as $productData) {
            $product = new Product();
            $product->productName = $productData['productName'];
            $product->basePriceNet = isset($productData['basePriceNet']) ? (string) $productData['basePriceNet']->asFloat() : '';
            $product->workingPriceNet = isset($productData['workingPriceNet'])
                ? (string) $productData['workingPriceNet']->asFloat()
                : ''
            ;

            $product->monthlyPayments = [];

            if (empty($productData['monthlyPayments'])) {
                $result->products[] = $product;

                continue;
            }

            foreach ($productData['monthlyPayments'] as $month => $amount) {
                $monthlyPayment = new MonthlyPayment();
                $monthlyPayment->month = (string) $month;
                $monthlyPayment->amount = (string) $amount;

                $product->monthlyPayments[] = $monthlyPayment;
            }

            $result->products[] = $product;
        }

        return $result;
    }
}
