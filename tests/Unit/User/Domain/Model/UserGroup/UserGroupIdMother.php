<?php

declare(strict_types=1);

namespace App\Tests\Unit\User\Domain\Model\UserGroup;

use App\Tests\Unit\Shared\Domain\UuidMother;
use App\User\Domain\Model\UserGroup\UserGroupId;

final class UserGroupIdMother
{
    public static function create(?string $value = null): UserGroupId
    {
        return new UserGroupId($value ?? UuidMother::create());
    }
}
