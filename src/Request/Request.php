<?php

declare(strict_types=1);

namespace DownPaymentCalculator\Request;

final class Request
{
    public string $postalCode; // coming from customer

    public string $yearlyUsage; // coming from customer

    public string $vat; // coming from customer

    public string $downPaymentInterval; // system setting

    public array $product; // system settings

    public array $bonus; // system settings
}
