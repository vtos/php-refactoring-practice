<?php
/**
 * Contains definition of the calculation service class.
 *
 * @package vtos/php-refactoring-practice
 * @author  Vitaly Potenko <potenkov@gmail.com>
 */

declare(strict_types=1);

namespace DownPaymentCalculator\Service;

use DownPaymentCalculator\Request\Request;
use DownPaymentCalculator\Result\MonthlyPayment;
use DownPaymentCalculator\Result\Product;
use DownPaymentCalculator\Result\Result;

final class DownPaymentCalculator
{
    public function calculate(Request $request): Result
    {
        $now = new \DateTime('now');

        if (empty($request->postalCode)) {
            echo 'Zip code is missing';
            exit;
        }

        if (!is_numeric($request->vat)
            || (float)$request->vat > 100 || (float)$request->vat < 0) {
            echo 'Vat is missing or invalid';
            exit;
        }

        if (empty($request->downPaymentInterval)
            || !is_numeric($request->downPaymentInterval)
            || (int)$request->downPaymentInterval < 1) {
            echo 'Down payment interval is missing or invalid';
            exit;
        }

        if (empty($request->yearlyUsage)
            || !is_numeric($request->yearlyUsage)
            || (int)$request->yearlyUsage < 0) {
            echo 'Yearly usage is missing or invalid';
            exit;
        }

        if (empty($request->product)) {
            echo 'Products are missing';
            exit;
        }

        $data = [];
        $data['yearlyUsage'] = $request->yearlyUsage;
        $data['vat'] = $request->vat;
        $data['downPaymentInterval'] = $request->downPaymentInterval;

        foreach ($request->product as $i => $product) {
            if ($now >= new \DateTime($product['validFrom']) && $now <= new \DateTime($product['validUntil'])) {
                $data['products'][$i]['productName'] = $product['name'];
                foreach ($product['tariff'] as $tariff) {
                    if ($now >= new \DateTime($tariff['validFrom']) && $now <= new \DateTime($tariff['validUntil'])
                        && $request->yearlyUsage >= $tariff['usageFrom']) {
                        $data['products'][$i]['tariff'] = $tariff;
                        $data['products'][$i]['workingPriceNet'] = $tariff['workingPriceNet'];
                        $data['products'][$i]['basePriceNet'] = $tariff['basePriceNet'];
                    }
                }
            }
        }

        // check for valid bonus
        $bonuses = [];
        foreach ($request->bonus as $bonus) {
            if ($now >= new \DateTime($bonus['validFrom']) && $now <= new \DateTime($bonus['validUntil'])
                && $request->yearlyUsage >= $bonus['usageFrom']) {
                $bonuses[] = $bonus;
            }
        }

        foreach ($data['products'] as $product_i => $product) {
            if (!empty($product['tariff'])) {
                // yearly working price
                $workingPriceNetYearly = $product['workingPriceNet'] * $data['yearlyUsage'];

                // calculate monthly down payment for the contract
                $monthlyDownPayment = ($product['basePriceNet'] + $workingPriceNetYearly) / (int)$data['downPaymentInterval'];

                $data['products'][$product_i]['monthlyPayments'] = [];
                for ($i = 1; $i <= (int)$data['downPaymentInterval']; $i++) {
                    $mPayment = $monthlyDownPayment;
                    foreach ($bonuses as $bonus) {
                        if ($i > $bonus['paymentAfterMonths']) {
                            // add here the bonus on the staring monthly down payment, not the resulted
                            $mPayment -= ($monthlyDownPayment * ((float)$bonus['value'] / 100));
                        }
                    }
                    $data['products'][$product_i]['monthlyPayments'][$i] = round($mPayment + ($mPayment * ($data['vat'] / 100)), 2);
                }
            }
        }

        return $this->buildResult($data['products']);
    }

    public function printHTML(Result $result): string
    {
        $html = "<div>";
        foreach ($result->products as $product) {
            $html .= "<div>";
            $html .= "<p>Product Name: $product->productName</p>";
            $html .= "<p>Tariff Base Price Net: $product->basePriceNet EUR</p>";
            $html .= "<p>Tariff Working Price Net: $product->workingPriceNet Cent</p>";
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

    public function printJSON(Result $result): string
    {
        $dataToJson = [];
        foreach ($result->products as $product) {
            $productData = [
                'productName' => $product->productName,
                'basePriceNet' => (int) $product->basePriceNet,
                'workingPriceNet' => (float) $product->workingPriceNet,
            ];

            foreach ($product->monthlyPayments as $monthlyPayment) {
                $productData['downPayment'][$monthlyPayment->month] = (float) $monthlyPayment->amount;
            }

            $dataToJson[] = $productData;
        }

        return json_encode($dataToJson);
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
