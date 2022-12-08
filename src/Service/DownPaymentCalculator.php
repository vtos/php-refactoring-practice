<?php

declare(strict_types=1);

namespace DownPaymentCalculator\Service;

use DownPaymentCalculator\Request\Request;

final class DownPaymentCalculator
{
    public $data;

    public function calculate(Request $request)
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

        $this->data['yearlyUsage'] = $request->yearlyUsage;
        $this->data['vat'] = $request->vat;
        $this->data['downPaymentInterval'] = $request->downPaymentInterval;

        foreach ($request->product as $i => $product) {
            if ($now >= new \DateTime($product['validFrom']) && $now <= new \DateTime($product['validUntil'])) {
                $this->data['products'][$i]['productName'] = $product['name'];
                foreach ($product['tariff'] as $tariff) {
                    if ($now >= new \DateTime($tariff['validFrom']) && $now <= new \DateTime($tariff['validUntil'])
                        && $request->yearlyUsage >= $tariff['usageFrom']) {
                        $this->data['products'][$i]['tariff'] = $tariff;
                        $this->data['products'][$i]['workingPriceNet'] = $tariff['workingPriceNet'];
                        $this->data['products'][$i]['basePriceNet'] = $tariff['basePriceNet'];
                    }
                }
            }
        }

        // check for valid bonus
        foreach ($request->bonus as $bonus) {
            if ($now >= new \DateTime($bonus['validFrom']) && $now <= new \DateTime($bonus['validUntil'])
                && $request->yearlyUsage >= $bonus['usageFrom']) {
                $this->data['bonus'][] = $bonus;
            }
        }

        foreach ($this->data['products'] as $product_i => $product) {
            if (!empty($product['tariff'])) {
                // yearly working price
                $workingPriceNetYearly = $product['workingPriceNet'] * $this->data['yearlyUsage'];
                //$this->data['products'][$product_i]['workingPriceNetYearly'] = $product['workingPriceNet'] * $this->data['yearlyUsage'];

                // calculate monthly down payment for the contract
                $monthlyDownPayment = ($product['basePriceNet'] + $workingPriceNetYearly) / (int)$this->data['downPaymentInterval'];

                $this->data['products'][$product_i]['monthlyPayments'] = [];
                for ($i = 1; $i <= (int)$this->data['downPaymentInterval']; $i++) {
                    $mPayment = $monthlyDownPayment;
                    foreach ($this->data['bonus'] as $bonus) {
                        if ($i > $bonus['paymentAfterMonths']) {
                            // add here the bonus on the staring monthly down payment, not the resulted
                            $mPayment -= ($monthlyDownPayment * ((float)$bonus['value'] / 100));
                        }
                    }
                    $this->data['products'][$product_i]['monthlyPayments'][$i] = round($mPayment + ($mPayment * ($this->data['vat'] / 100)), 2);
                }
            }
        }
    }

    public function printHTML()
    {
        $html = "<div>";
        foreach ($this->data['products'] as $product) {
            $html .= "<div>";
            $html .= "<p>Product Name: {$product['productName']}</p>";
            $html .= "<p>Tariff Base Price Net: {$product['basePriceNet']} EUR</p>";
            $html .= "<p>Tariff Working Price Net: {$product['workingPriceNet']} Cent</p>";
            $html .= "</div>";

            $html .= "<div>";
            foreach ($product['monthlyPayments'] as $month => $monthlyPayment) {
                $html .= "<p>Monthly down payment: {$month} - {$monthlyPayment} EUR</p>\n";
            }
            $html .= "</div>";
        }
        $html .= "</div>";

        return $html;
    }

    public function printJSON()
    {
        $data = [];
        foreach ($this->data['products'] as $product) {
            $productData = [
                'productName' => $product['productName'],
                'basePriceNet' => $product['basePriceNet'],
                'workingPriceNet' => $product['workingPriceNet'],
            ];

            foreach ($product['monthlyPayments'] as $month => $monthlyPayment) {
                $productData['downPayment'][$month] = $monthlyPayment;
            }

            $data[] = $productData;
        }

        return json_encode($data);
    }
}
