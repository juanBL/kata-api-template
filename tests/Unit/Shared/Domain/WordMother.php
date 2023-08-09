<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Domain;

final class WordMother
{
    public static function create(): string
    {
        return MotherCreator::random()->word();
    }
}
