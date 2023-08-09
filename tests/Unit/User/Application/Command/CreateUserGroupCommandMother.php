<?php

declare(strict_types=1);

namespace App\Tests\Unit\User\Application\Command;


use App\Tests\Unit\User\Domain\Model\User\UserIdMother;
use App\Tests\Unit\User\Domain\Model\User\UserNameMother;
use App\User\Application\Command\CreateUserGroupCommand;
use App\User\Domain\Model\Group\GroupId;
use App\User\Domain\Model\User\UserId;
use App\User\Domain\Model\UserGroup\UserGroupId;

final class CreateUserGroupCommandMother
{
    public static function create(
        ?UserGroupId $id = null,
        ?UserId $userId = null,
        ?GroupId $groupId = null
    ): CreateUserGroupCommand {
        return new CreateUserGroupCommand(
            $id?->value() ?? UserIdMother::create()->value(),
            $userId?->value() ?? UserNameMother::create()->value(),
            $groupId?->value() ?? UserNameMother::create()->value()
        );
    }
}
