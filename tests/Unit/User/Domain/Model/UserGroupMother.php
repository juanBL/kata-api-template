<?php

declare(strict_types=1);

namespace App\Tests\Unit\User\Domain\Model;

use App\Tests\Unit\User\Domain\Model\Group\GroupIdMother;
use App\Tests\Unit\User\Domain\Model\User\UserIdMother;
use App\Tests\Unit\User\Domain\Model\UserGroup\UserGroupIdMother;
use App\User\Domain\Model\UserGroup;

final class UserGroupMother
{
    public static function create(): UserGroup
    {
        return UserGroup::create(
            UserGroupIdMother::create(),
            UserIdMother::create(),
            GroupIdMother::create(),
        );
    }
}
