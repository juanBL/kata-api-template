<?php

declare(strict_types=1);

namespace App\Tests\Unit\User\Domain\Model\Group;

use App\Tests\Unit\Shared\Domain\UuidMother;
use App\User\Domain\Model\Group\GroupId;
use App\User\Domain\Model\User\UserId;

final class GroupIdMother
{
    public static function create(?string $value = null): GroupId
    {
        return new GroupId($value ?? UuidMother::create());
    }
}
