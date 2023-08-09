<?php

declare(strict_types=1);

namespace App\Tests\Unit\User\Application\Command;


use App\Tests\Unit\User\Domain\Model\Group\GroupIdMother;
use App\User\Application\Command\DeleteGroupCommand;
use App\User\Domain\Model\Group\GroupId;

final class DeleteGroupCommandMother
{
    public static function create(?GroupId $id = null): DeleteGroupCommand
    {
        return new DeleteGroupCommand($id?->value() ?? GroupIdMother::create()->value());
    }
}
