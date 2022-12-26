<?php
/**
 * Contains definition of tests to cover the down payment calculation service.
 *
 * @package vtos/php-refactoring-practice
 * @author  Vitaly Potenko <potenkov@gmail.com>
 */

declare(strict_types=1);

namespace DownPaymentCalculator\Tests\Unit\Service;

use DateTime;
use DownPaymentCalculator\Calculation\Common\NonNegativeFloat;
use DownPaymentCalculator\Calculation\Common\NonNegativeInteger;
use DownPaymentCalculator\Calculation\Configuration\Bonus;
use DownPaymentCalculator\Calculation\Configuration\Configuration;
use DownPaymentCalculator\Calculation\Configuration\Product;
use DownPaymentCalculator\Calculation\Parameters\Parameters;
use DownPaymentCalculator\Calculation\Parameters\Vat;
use DownPaymentCalculator\Result\MonthlyPayment;
use DownPaymentCalculator\Result\Product as ResultProduct;
use DownPaymentCalculator\Result\Result;
use DownPaymentCalculator\Service\DownPaymentCalculator;
use PHPUnit\Framework\TestCase;

class DownPaymentCalculatorTest extends TestCase
{
    public function test_it_calculates_down_payment(): void
    {
        $calculator = new DownPaymentCalculator();
        $result = $calculator->calculate($this->parameters(), $this->configuration(), new DateTime('2022-12-11'));

        self::assertEquals($this->expectation(), $result);
    }

    private function parameters(): Parameters
    {
        return new Parameters(
            new NonNegativeInteger(3500),
            new Vat(
                new NonNegativeFloat(19.00)
            )
        );
    }

    private function configuration(): Configuration
    {
        return new Configuration(
            new NonNegativeInteger(12),
            array_map(
                fn (array $productAsArray): Product => Product::fromArray($productAsArray),
                [
                    [
                        'name' => 'Electricity Simple',
                        'validFrom' => '2021-01-01',
                        'validUntil' => '2022-12-31',
                        'tariff' => [
                            [
                                'name' => 'Tariff 1',
                                'usageFrom' => 0,
                                'validFrom' => '2021-01-01',
                                'validUntil' => '2021-12-31',
                                'workingPriceNet' => 0.20,
                                'basePriceNet' => 50.00
                            ],
                            [
                                'name' => 'Tariff 2',
                                'usageFrom' => 0,
                                'validFrom' => '2022-01-01',
                                'validUntil' => '2022-12-31',
                                'workingPriceNet' => 0.20,
                                'basePriceNet' => 50.00
                            ],
                            [
                                'name' => 'Tariff 3',
                                'usageFrom' => 3001,
                                'validFrom' => '2022-01-01',
                                'validUntil' => '2022-12-31',
                                'workingPriceNet' => 0.15,
                                'basePriceNet' => 40.00
                            ],
                            [
                                'name' => 'Tariff 4',
                                'usageFrom' => 5001,
                                'validFrom' => '2022-01-01',
                                'validUntil' => '2022-12-31',
                                'workingPriceNet' => 0.12,
                                'basePriceNet' => 35.00
                            ]
                        ]
                    ],
                    [
                        'name' => 'Electricity Advanced',
                        'validFrom' => '2021-01-01',
                        'validUntil' => '2022-12-31',
                        'tariff' => [
                            [
                                'name' => 'Tariff 1',
                                'usageFrom' => 0,
                                'validFrom' => '2021-01-01',
                                'validUntil' => '2021-12-31',
                                'workingPriceNet' => 0.25,
                                'basePriceNet' => 50.00
                            ],
                            [
                                'name' => 'Tariff 2',
                                'usageFrom' => 0,
                                'validFrom' => '2022-01-01',
                                'validUntil' => '2022-12-31',
                                'workingPriceNet' => 0.25,
                                'basePriceNet' => 50.00
                            ],
                            [
                                'name' => 'Tariff 3',
                                'usageFrom' => 3001,
                                'validFrom' => '2022-01-01',
                                'validUntil' => '2022-12-31',
                                'workingPriceNet' => 0.18,
                                'basePriceNet' => 41.00
                            ],
                            [
                                'name' => 'Tariff 4',
                                'usageFrom' => 5001,
                                'validFrom' => '2022-01-01',
                                'validUntil' => '2022-12-31',
                                'workingPriceNet' => 0.15,
                                'basePriceNet' => 38.00
                            ],
                        ],
                    ],
                ]
            ),
            array_map(
                fn (array $bonusAsArray): Bonus => Bonus::fromArray($bonusAsArray),
                [
                    [
                        'name' => 'BONUS-A',
                        'usageFrom' => 0,
                        'validFrom' => '2021-01-01',
                        'validUntil' => '2022-12-31',
                        'value' => 5,
                        'paymentAfterMonths' => 0
                    ],
                    [
                        'name' => 'BONUS-B',
                        'usageFrom' => 0,
                        'validFrom' => '2021-01-01',
                        'validUntil' => '2022-12-31',
                        'value' => 5,
                        'paymentAfterMonths' => 6
                    ],
                    [
                        'name' => 'BONUS-C',
                        'usageFrom' => 2500,
                        'validFrom' => '2021-01-01',
                        'validUntil' => '2022-12-31',
                        'value' => 2.5,
                        'paymentAfterMonths' => 3
                    ],
                    [
                        'name' => 'BONUS-D',
                        'usageFrom' => 4500,
                        'validFrom' => '2021-01-01',
                        'validUntil' => '2022-12-31',
                        'value' => 1.25,
                        'paymentAfterMonths' => 9
                    ],
                ]
            )
        );
    }

    private function expectation(): Result
    {
        $result = new Result();
        $result->products = [];

        $product = new ResultProduct();
        $product->productName = 'Electricity Simple';
        $product->basePriceNet = '40';
        $product->workingPriceNet = '0.15';
        $product->monthlyPayments = [];

        $monthlyPaymentsAsArray = [
            1 => '53.23',
            2 => '53.23',
            3 => '53.23',
            4 => '51.83',
            5 => '51.83',
            6 => '51.83',
            7 => '49.03',
            8 => '49.03',
            9 => '49.03',
            10 => '49.03',
            11 => '49.03',
            12 => '49.03',
        ];

        foreach ($monthlyPaymentsAsArray as $month => $amount) {
            $monthlyPayment = new MonthlyPayment();
            $monthlyPayment->month = (string) $month;
            $monthlyPayment->amount = $amount;

            $product->monthlyPayments[] = $monthlyPayment;
        }

        $result->products[] = $product;

        $product = new ResultProduct();
        $product->productName = 'Electricity Advanced';
        $product->basePriceNet = '41';
        $product->workingPriceNet = '0.18';
        $product->monthlyPayments = [];

        $monthlyPaymentsAsArray = [
            1 => '63.21',
            2 => '63.21',
            3 => '63.21',
            4 => '61.55',
            5 => '61.55',
            6 => '61.55',
            7 => '58.22',
            8 => '58.22',
            9 => '58.22',
            10 => '58.22',
            11 => '58.22',
            12 => '58.22',
        ];

        foreach ($monthlyPaymentsAsArray as $month => $amount) {
            $monthlyPayment = new MonthlyPayment();
            $monthlyPayment->month = (string) $month;
            $monthlyPayment->amount = $amount;

            $product->monthlyPayments[] = $monthlyPayment;
        }

        $result->products[] = $product;

        return $result;
    }
}
