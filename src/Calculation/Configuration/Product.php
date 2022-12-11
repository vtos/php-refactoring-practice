<?php
/**
 * Contains definition of a value object denoting a product to calculate the down payment for.
 *
 * @package vtos/php-refactoring-practice
 * @author  Vitaly Potenko <potenkov@gmail.com>
 */

declare(strict_types=1);

namespace DownPaymentCalculator\Calculation\Configuration;

use DownPaymentCalculator\Calculation\Common\Name;
use DownPaymentCalculator\Calculation\Common\ValidityInterval;
use DownPaymentCalculator\Calculation\DataExtraction;
use InvalidArgumentException;

final class Product
{
    use DataExtraction;

    private Name $name;

    private ValidityInterval $validityInterval;

    /**
     * @var Tariff[] $tariffs
     */
    private array $tariffs;

    public function __construct(Name $name, ValidityInterval $validityInterval, array $tariffs)
    {
        if (empty($tariffs)) {
            throw new InvalidArgumentException('At least one tariff is required.');
        }

        $this->name = $name;
        $this->validityInterval = $validityInterval;
        $this->tariffs = $tariffs;
    }

    public function name(): Name
    {
        return $this->name;
    }

    public function validityInterval(): ValidityInterval
    {
        return $this->validityInterval;
    }

    /**
     * @return Tariff[]
     */
    public function tariffs(): array
    {
        return $this->tariffs;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            Name::fromString(self::extractStringOrNull($data, 'name')),
            ValidityInterval::fromFormattedStrings(
                self::extractStringOrNull($data, 'validFrom'),
                self::extractStringOrNull($data, 'validUntil')
            ),
            array_map(
                fn (array $tariffAsArray): Tariff => Tariff::fromArray($tariffAsArray),
                $data['tariff'] ?? []
            )
        );
    }
}
