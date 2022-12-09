<?php
/**
 * The entry point of the legacy application, basically a single script to run.
 *
 * @package vtos/php-refactoring-practice
 * @author  Vitaly Potenko <potenkov@gmail.com>
 */

require_once(__DIR__ . '/vendor/autoload.php');

use DownPaymentCalculator\Request\Request;
use DownPaymentCalculator\Service\DownPaymentCalculator;

class Controller
{
    private DownPaymentCalculator $downPaymentCalculator;

    public function __construct(DownPaymentCalculator $downPaymentCalculator)
    {
        $this->downPaymentCalculator = $downPaymentCalculator;
    }

    public function runJSON(Request $request): string
    {
        $data = $this->downPaymentCalculator->calculate($request);

        return $this->downPaymentCalculator->printJSON($data['products']);
    }

    public function runHTML(Request $request): string
    {
        $data = $this->downPaymentCalculator->calculate($request);

        return $this->downPaymentCalculator->printHTML($data['products']);
    }
}

//// ---------------------------

$request = new Request();
$request->postalCode = '10789';
$request->vat = 19.00;
$request->yearlyUsage = 3500;
$request->downPaymentInterval = 12;
$request->product = [
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
            ]
        ]
    ],
];
$request->bonus = [
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
    ]
];

echo (new Controller(new DownPaymentCalculator()))->runHTML($request);
echo "\n";
echo (new Controller(new DownPaymentCalculator()))->runJSON($request);
