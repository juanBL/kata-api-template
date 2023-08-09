<?php

declare(strict_types=1);

namespace App\Tests\Unit\User\Domain\Model;

use App\Tests\Unit\User\Domain\Model\User\UserIdMother;
use App\Tests\Unit\User\Domain\Model\User\UserNameMother;
use App\User\Domain\Model\User;

final class UserMother
{
    public static function create(): User
    {
        return User::create(
            UserIdMother::create(),
            UserNameMother::create(),
        );
    }
}
