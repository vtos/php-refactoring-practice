<?php
/**
 * Contains definition of a value object for a name.
 *
 * @package vtos/php-refactoring-practice
 * @author  Vitaly Potenko <potenkov@gmail.com>
 */

declare(strict_types=1);

namespace DownPaymentCalculator\Calculation\Common;

use InvalidArgumentException;

final class Name
{
    private string $name;

    public function __construct(string $name)
    {
        if (empty(trim($name))) {
            throw new InvalidArgumentException('The name cannot be empty.');
        }

        $this->name = $name;
    }

    public function asString(): string
    {
        return $this->name;
    }

    public static function fromString(string $name): self
    {
        return new self($name);
    }
}
