<?php

declare(strict_types=1);

namespace App\Tests\Unit\User\Domain\Model;

use App\Tests\Unit\User\Domain\Model\Group\GroupIdMother;
use App\Tests\Unit\User\Domain\Model\Group\GroupNameMother;
use App\User\Domain\Model\Group;

final class GroupMother
{
    public static function create(): Group
    {
        return Group::create(
            GroupIdMother::create(),
            GroupNameMother::create(),
        );
    }
}
