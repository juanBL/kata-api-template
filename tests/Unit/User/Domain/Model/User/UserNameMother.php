<?php

declare(strict_types=1);

namespace App\Tests\Unit\User\Domain\Model\User;

use App\Tests\Unit\Shared\Domain\WordMother;
use App\User\Domain\Model\User\UserName;

final class UserNameMother
{
    public static function create(?string $value = null): UserName
    {
        return new UserName($value ?? WordMother::create());
    }
}
