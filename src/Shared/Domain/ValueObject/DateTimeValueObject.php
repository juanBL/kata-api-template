<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use DateInterval;
use DateTime;
use DateTimeInterface;

abstract class DateTimeValueObject
{
    public const FORMAT = DateTimeInterface::ATOM;

    public function __construct(public DateTime $value)
    {
    }

    protected static function ensureIsValidDate(string $value, string $format): DateTime|bool|null
    {
        return DateTime::createFromFormat($format, $value);
    }

    public function equals(DateTimeValueObject $dateTime): bool
    {
        return
            $this->value->format(self::FORMAT)
            === $dateTime->value()->format(self::FORMAT);
    }

    public function value(): DateTime
    {
        return $this->value;
    }

    public function gt(DateTimeValueObject $dateTime): bool
    {
        return
            $this->value->format(self::FORMAT)
            > $dateTime->value()->format(self::FORMAT);
    }

    public function gte(DateTimeValueObject $dateTime): bool
    {
        return
            $this->value->format(self::FORMAT)
            >= $dateTime->value()->format(self::FORMAT);
    }

    public function lt(DateTimeValueObject $dateTime): bool
    {
        return
            $this->value->format(self::FORMAT)
            < $dateTime->value()->format(self::FORMAT);
    }

    public function lte(DateTimeValueObject $dateTime): bool
    {
        return
            $this->value->format(self::FORMAT)
            <= $dateTime->value()->format(self::FORMAT);
    }

    public function diff(DateTimeValueObject $other): DateInterval
    {
        return $this->value->diff($other->value());
    }

    public function toString(): string
    {
        return $this->value()->format(self::FORMAT);
    }
}
