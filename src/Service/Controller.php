<?php
/**
 * Contains definition of the controller class.
 *
 * @package vtos/php-refactoring-practice
 * @author  Vitaly Potenko <potenkov@gmail.com>
 */

declare(strict_types=1);

namespace DownPaymentCalculator\Service;

use DateTime;
use DownPaymentCalculator\Calculation\Common\NonNegativeInteger;
use DownPaymentCalculator\Calculation\Configuration\Bonus;
use DownPaymentCalculator\Calculation\Configuration\Configuration;
use DownPaymentCalculator\Calculation\Configuration\Product;
use DownPaymentCalculator\Calculation\Parameters\Parameters;
use DownPaymentCalculator\Calculation\Parameters\Vat;
use DownPaymentCalculator\Request\Request;
use DownPaymentCalculator\View\Renderer;

final class Controller
{
    private DownPaymentCalculator $downPaymentCalculator;

    private Renderer $resultRenderer;

    public function __construct(DownPaymentCalculator $downPaymentCalculator, Renderer $resultRenderer)
    {
        $this->downPaymentCalculator = $downPaymentCalculator;
        $this->resultRenderer = $resultRenderer;
    }

    public function run(Request $request): string {
        $result = $this->downPaymentCalculator->calculate(
            $this->extractCalculationParametersFromRequest($request),
            $this->extractCalculationConfigurationFromRequest($request),
            new DateTime('now')
        );

        return $this->resultRenderer->render($result);
    }

    private function extractCalculationParametersFromRequest(Request $request): Parameters
    {
        return new Parameters(
            new NonNegativeInteger((int) $request->yearlyUsage),
            new Vat((float) $request->vat)
        );
    }

    private function extractCalculationConfigurationFromRequest(Request $request): Configuration
    {
        return new Configuration(
            new NonNegativeInteger((int) $request->downPaymentInterval),
            array_map(
                fn (array $productAsArray): Product => Product::fromArray($productAsArray),
                $request->product
            ),
            array_map(
                fn (array $bonusAsArray): Bonus => Bonus::fromArray($bonusAsArray),
                $request->bonus
            )
        );
    }
}
