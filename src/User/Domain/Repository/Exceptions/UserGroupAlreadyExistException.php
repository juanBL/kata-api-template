<?php

declare(strict_types=1);

namespace App\User\Domain\Repository\Exceptions;

use App\Shared\Domain\DomainError;
use App\User\Domain\Model\Group\GroupId;
use App\User\Domain\Model\User\UserId;

final class UserGroupAlreadyExistException extends DomainError implements UserRepositoryException
{
    private const ERROR_CODE = 'user_is_already_in_group';
    private const ERROR_MESSAGE = 'The user with id <%s> is already in the group <%s>';

    public function __construct(private readonly UserId $id, private readonly GroupId $groupId)
    {
        parent::__construct();
    }

    public function errorCode(): string
    {
        return self::ERROR_CODE;
    }

    protected function errorMessage(): string
    {
        return sprintf(self::ERROR_MESSAGE, $this->id->value(), $this->groupId->value());
    }
}
