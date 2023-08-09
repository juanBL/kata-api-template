<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use DateInterval;
use DateTime;
use DateTimeInterface;

abstract class NullableDateTimeValueObject
{
    public const FORMAT = DateTimeInterface::ATOM;

    public function __construct(public ?DateTime $value = null)
    {
    }

    protected static function ensureIsValidDate(string $value, string $format): ?DateTime
    {
        return DateTime::createFromFormat($format, $value);
    }

    public function equals(NullableDateTimeValueObject $dateTime): bool
    {
        return
            $this->value->format(self::FORMAT)
            === $dateTime->value()->format(self::FORMAT);
    }

    public function value(): ?DateTime
    {
        return $this->value;
    }

    public function gt(NullableDateTimeValueObject $dateTime): bool
    {
        return
            $this->value->format(self::FORMAT)
            > $dateTime->value()->format(self::FORMAT);
    }

    public function gte(NullableDateTimeValueObject $dateTime): bool
    {
        return
            $this->value->format(self::FORMAT)
            >= $dateTime->value()->format(self::FORMAT);
    }

    public function lt(NullableDateTimeValueObject $dateTime): bool
    {
        return
            $this->value->format(self::FORMAT)
            < $dateTime->value()->format(self::FORMAT);
    }

    public function lte(NullableDateTimeValueObject $dateTime): bool
    {
        return
            $this->value->format(self::FORMAT)
            <= $dateTime->value()->format(self::FORMAT);
    }

    public function diff(NullableDateTimeValueObject $other): DateInterval
    {
        return $this->value->diff($other->value());
    }

    public function toString(): ?string
    {
        if (is_null($this->value)) {
            return null;
        }
        return $this->value()->format(self::FORMAT);
    }
}
