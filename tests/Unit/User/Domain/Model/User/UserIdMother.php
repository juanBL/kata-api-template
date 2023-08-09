<?php

declare(strict_types=1);

namespace App\Tests\Unit\User\Domain\Model\User;

use App\Tests\Unit\Shared\Domain\UuidMother;
use App\User\Domain\Model\User\UserId;

final class UserIdMother
{
    public static function create(?string $value = null): UserId
    {
        return new UserId($value ?? UuidMother::create());
    }
}
