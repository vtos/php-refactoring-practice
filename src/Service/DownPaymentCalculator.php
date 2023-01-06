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
use DownPaymentCalculator\Calculation\MonthlyDownPayment\MonthlyDownPayment;
use DownPaymentCalculator\Calculation\MonthlyDownPayment\MonthSequenceNumber;
use DownPaymentCalculator\Calculation\Parameters\Parameters;
use DownPaymentCalculator\Result\MonthlyPayment;
use DownPaymentCalculator\Result\Product;
use DownPaymentCalculator\Result\Result;
use DownPaymentCalculator\Result\TariffApplied;

final class DownPaymentCalculator
{
    public function calculate(Parameters $parameters, Configuration $configuration, DateTime $now): Result
    {
        $data = [];

        foreach ($configuration->products() as $i => $product) {
            if ($product->validityInterval()->coversDate($now)) {
                $data['products'][$i]['productName'] = $product->name();
                foreach ($product->tariffs() as $tariff) {
                    if ($tariff->isApplicable($parameters->yearlyUsage(), $now)) {
                        $data['products'][$i]['tariff'] = $tariff;
                        $data['products'][$i]['workingPriceNet'] = $tariff->workingPriceNet();
                        $data['products'][$i]['basePriceNet'] = $tariff->basePriceNet();
                    }
                }
            }
        }

        foreach ($data['products'] as $product_i => $product) {
            if (!empty($product['tariff'])) {
                $monthlyDownPayment = MonthlyDownPayment::calculateBase(
                    $product['workingPriceNet'],
                    $product['basePriceNet'],
                    $parameters->yearlyUsage(),
                    $configuration->downPaymentInterval()
                );

                $data['products'][$product_i]['monthlyPayments'] = [];
                for ($monthNumber = 1; $monthNumber <= $configuration->downPaymentInterval()->asInteger(); $monthNumber++) {
                    $data['products'][$product_i]['monthlyPayments'][$monthNumber] = $monthlyDownPayment
                        ->applyBonuses(
                            $configuration->bonuses(),
                            $parameters->yearlyUsage(),
                            $now,
                            MonthSequenceNumber::fromInteger($monthNumber)
                        )
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
            $product->name = $productData['productName'];

            $tariffApplied = new TariffApplied();
            $tariffApplied->basePriceNet = $productData['basePriceNet'] ?? null;
            $tariffApplied->workingPriceNet = $productData['workingPriceNet'] ?? null;

            $product->tariffApplied = $tariffApplied;

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
