<?php
/**
 * Contains a functional test to cover general behaviour of the application.
 *
 * @package vtos/php-refactoring-practice
 * @author  Vitaly Potenko <potenkov@gmail.com>
 */

declare(strict_types=1);

namespace DownPaymentCalculator\Tests\Functional;

use PHPUnit\Framework\TestCase;

class DownPaymentCalculationTest extends TestCase
{
    /**
     * This test runs the application and checks its output. This is the way to keep behaviour of the application under control
     * during refactoring. The test should be run after each refactoring commit to make sure the application still runs correctly.
     */
    public function test_it_calculates_and_outputs_down_payment_in_both_JSON_and_HTML(): void
    {
        ob_start();
        require_once(__DIR__ . '/../../down_payment_calculator.php');
        $output = ob_get_clean();

        self::assertEquals($this->expectation(), $output);
    }

    private function expectation(): string
    {
        $html = "<div>";
        $html .= "<div>";
        $html .= "<p>Product Name: Electricity Simple</p>";
        $html .= "<p>Tariff Base Price Net: 40 EUR</p>";
        $html .= "<p>Tariff Working Price Net: 0.15 Cent</p>";
        $html .= "</div>";
        $html .= "<div>";
        $html .= "<p>Monthly down payment: 1 - 53.23 EUR</p>\n";
        $html .= "<p>Monthly down payment: 2 - 53.23 EUR</p>\n";
        $html .= "<p>Monthly down payment: 3 - 53.23 EUR</p>\n";
        $html .= "<p>Monthly down payment: 4 - 51.83 EUR</p>\n";
        $html .= "<p>Monthly down payment: 5 - 51.83 EUR</p>\n";
        $html .= "<p>Monthly down payment: 6 - 51.83 EUR</p>\n";
        $html .= "<p>Monthly down payment: 7 - 49.03 EUR</p>\n";
        $html .= "<p>Monthly down payment: 8 - 49.03 EUR</p>\n";
        $html .= "<p>Monthly down payment: 9 - 49.03 EUR</p>\n";
        $html .= "<p>Monthly down payment: 10 - 49.03 EUR</p>\n";
        $html .= "<p>Monthly down payment: 11 - 49.03 EUR</p>\n";
        $html .= "<p>Monthly down payment: 12 - 49.03 EUR</p>\n";
        $html .= "</div>";
        $html .= "<div>";
        $html .= "<p>Product Name: Electricity Advanced</p>";
        $html .= "<p>Tariff Base Price Net: 41 EUR</p>";
        $html .= "<p>Tariff Working Price Net: 0.18 Cent</p>";
        $html .= "</div>";
        $html .= "<div>";
        $html .= "<p>Monthly down payment: 1 - 63.21 EUR</p>\n";
        $html .= "<p>Monthly down payment: 2 - 63.21 EUR</p>\n";
        $html .= "<p>Monthly down payment: 3 - 63.21 EUR</p>\n";
        $html .= "<p>Monthly down payment: 4 - 61.55 EUR</p>\n";
        $html .= "<p>Monthly down payment: 5 - 61.55 EUR</p>\n";
        $html .= "<p>Monthly down payment: 6 - 61.55 EUR</p>\n";
        $html .= "<p>Monthly down payment: 7 - 58.22 EUR</p>\n";
        $html .= "<p>Monthly down payment: 8 - 58.22 EUR</p>\n";
        $html .= "<p>Monthly down payment: 9 - 58.22 EUR</p>\n";
        $html .= "<p>Monthly down payment: 10 - 58.22 EUR</p>\n";
        $html .= "<p>Monthly down payment: 11 - 58.22 EUR</p>\n";
        $html .= "<p>Monthly down payment: 12 - 58.22 EUR</p>\n";
        $html .= "</div>";
        $html .= "</div>";

        $json = '[{"productName":"Electricity Simple","basePriceNet":40,"workingPriceNet":0.15,"downPayment":{"1":53.23,"2":53.23,"3":53.23,"4":51.83,"5":51.83,"6":51.83,"7":49.03,"8":49.03,"9":49.03,"10":49.03,"11":49.03,"12":49.03}},{"productName":"Electricity Advanced","basePriceNet":41,"workingPriceNet":0.18,"downPayment":{"1":63.21,"2":63.21,"3":63.21,"4":61.55,"5":61.55,"6":61.55,"7":58.22,"8":58.22,"9":58.22,"10":58.22,"11":58.22,"12":58.22}}]';

        return $html . "\n" . $json;
    }
}
