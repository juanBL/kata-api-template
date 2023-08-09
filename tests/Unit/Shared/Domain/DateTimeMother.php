<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Domain;

use DateTime;

final class DateTimeMother
{
    public static function create(): DateTime
    {
        return self::random();
    }

    public static function random(): DateTime
    {
        return MotherCreator::random()->dateTime();
    }
}
