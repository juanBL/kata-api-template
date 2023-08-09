<?php

declare(strict_types=1);

namespace App\Shared\Domain;

use DateTimeInterface;

final class Utils
{
    public static function toSnakeCase(string $text): string
    {
        return ctype_lower($text) ? $text : strtolower((string)preg_replace('/([^A-Z\s])([A-Z])/', "$1_$2", $text));
    }

    public static function dateToString(DateTimeInterface $date): string
    {
        return $date->format(DateTimeInterface::ATOM);
    }
}
