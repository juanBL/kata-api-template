<?php

declare(strict_types=1);

namespace App\Tests\Unit\User\Application\Command;


use App\Tests\Unit\User\Domain\Model\Group\GroupIdMother;
use App\Tests\Unit\User\Domain\Model\User\UserIdMother;
use App\User\Application\Command\DeleteUserGroupCommand;
use App\User\Domain\Model\Group\GroupId;
use App\User\Domain\Model\User\UserId;

final class DeleteUserGroupCommandMother
{
    public static function create(?UserId $userId = null, ?GroupId $groupId = null): DeleteUserGroupCommand
    {
        return new DeleteUserGroupCommand(
            $userId?->value() ?? UserIdMother::create()->value(),
            $groupId?->value() ?? GroupIdMother::create()->value()
        );
    }
}
