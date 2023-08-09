<?php

declare(strict_types=1);

namespace App\Tests\Unit\User\Domain\Model\Group;

use App\Tests\Unit\Shared\Domain\WordMother;
use App\User\Domain\Model\Group\GroupName;
use App\User\Domain\Model\User\UserName;

final class GroupNameMother
{
    public static function create(?string $value = null): GroupName
    {
        return new GroupName($value ?? WordMother::create());
    }
}
