<?php

declare(strict_types=1);

namespace App\Tests\Unit\User\Application\Command;


use App\Tests\Unit\User\Domain\Model\User\UserIdMother;
use App\Tests\Unit\User\Domain\Model\User\UserNameMother;
use App\User\Application\Command\CreateUserCommand;
use App\User\Domain\Model\User\UserId;
use App\User\Domain\Model\User\UserName;

final class CreateUserCommandMother
{
    public static function create(?UserId $id = null, ?UserName $name = null): CreateUserCommand
    {
        return new CreateUserCommand(
            $id?->value() ?? UserIdMother::create()->value(),
            $name?->value() ?? UserNameMother::create()->value()
        );
    }
}
