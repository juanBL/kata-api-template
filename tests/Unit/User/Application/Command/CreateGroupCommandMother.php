<?php

declare(strict_types=1);

namespace App\Tests\Unit\User\Application\Command;


use App\Tests\Unit\User\Domain\Model\Group\GroupIdMother;
use App\Tests\Unit\User\Domain\Model\Group\GroupNameMother;
use App\User\Application\Command\CreateGroupCommand;
use App\User\Domain\Model\Group\GroupId;
use App\User\Domain\Model\Group\GroupName;

final class CreateGroupCommandMother
{
    public static function create(?GroupId $id = null, ?GroupName $name = null): CreateGroupCommand
    {
        return new CreateGroupCommand(
            $id?->value() ?? GroupIdMother::create()->value(),
            $name?->value() ?? GroupNameMother::create()->value()
        );
    }
}
