<?php
/**
 * Contains definition of a value object denoting validity period for a product, tariff, bonus, etc.
 *
 * @package vtos/php-refactoring-practice
 * @author  Vitaly Potenko <potenkov@gmail.com>
 */

declare(strict_types=1);

namespace DownPaymentCalculator\Calculation\Common;

use DateTime;
use Exception;
use InvalidArgumentException;

final class ValidityInterval
{
    private DateTime $from;

    private DateTime $until;

    public function __construct(DateTime $from, DateTime $until)
    {
        if ($from->getTimestamp() >= $until->getTimestamp()) {
            throw new InvalidArgumentException('The \'until\' date cannot be earlier than or equal to the \'from\' date.');
        }

        $this->from = $from;
        $this->until = $until;
    }

    public function from(): DateTime
    {
        return $this->from;
    }

    public function until(): DateTime
    {
        return $this->until;
    }

    public function coversDate(DateTime $date): bool
    {
        return $date->getTimestamp() >= $this->from->getTimestamp()
            && $date->getTimestamp() <= $this->until->getTimestamp()
        ;
    }

    /**
     * @throws Exception
     */
    public static function fromFormattedStrings(string $from, string $until): self
    {
        return new self(new DateTime($from), new DateTime($until));
    }
}
