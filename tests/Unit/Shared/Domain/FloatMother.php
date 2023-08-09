<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Domain;

final class FloatMother
{
    public static function create(): float
    {
        return self::random(1);
    }

    public static function random(int $min, int $max = PHP_INT_MAX): float
    {
        return MotherCreator::random()->randomFloat(2, $min, $max);
    }
}
