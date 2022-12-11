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
                    if ($tariff->validityInterval()->coversDate($now)
                        && $parameters->yearlyUsage()->value() >= $tariff->usageFrom()->value()
                    ) {
                        $data['products'][$i]['tariff'] = $tariff;
                        $data['products'][$i]['workingPriceNet'] = $tariff->workingPriceNet()->value();
                        $data['products'][$i]['basePriceNet'] = $tariff->basePriceNet()->value();
                    }
                }
            }
        }

        // check for valid bonus
        $bonuses = [];
        foreach ($configuration->bonuses() as $bonus) {
            if ($bonus->validityInterval()->coversDate($now)
                && $parameters->yearlyUsage()->value() >= $bonus->usageFrom()->value()
            ) {
                $bonuses[] = $bonus;
            }
        }

        foreach ($data['products'] as $product_i => $product) {
            if (!empty($product['tariff'])) {
                // yearly working price
                $workingPriceNetYearly = $product['workingPriceNet'] * $parameters->yearlyUsage()->value();

                // calculate monthly down payment for the contract
                $monthlyDownPayment = ($product['basePriceNet'] + $workingPriceNetYearly) / $configuration->downPaymentInterval()->value();

                $data['products'][$product_i]['monthlyPayments'] = [];
                for ($i = 1; $i <= $configuration->downPaymentInterval()->value(); $i++) {
                    $mPayment = $monthlyDownPayment;
                    foreach ($bonuses as $bonus) {
                        if ($i > $bonus->paymentAfterMonths()->value()) {
                            // add here the bonus on the staring monthly down payment, not the resulted
                            $mPayment -= ($monthlyDownPayment * ($bonus->value()->value() / 100));
                        }
                    }
                    $data['products'][$product_i]['monthlyPayments'][$i] = round($mPayment + ($mPayment * ($parameters->vat()->value() / 100)), 2);
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
            $product->basePriceNet = (string) $productData['basePriceNet'] ?? '';
            $product->workingPriceNet = (string) $productData['workingPriceNet'] ?? '';

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
