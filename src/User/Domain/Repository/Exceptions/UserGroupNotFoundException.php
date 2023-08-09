<?php

declare(strict_types=1);

namespace App\User\Domain\Repository\Exceptions;

use App\Shared\Domain\DomainError;
use App\User\Domain\Model\Group\GroupId;
use App\User\Domain\Model\User\UserId;

final class UserGroupNotFoundException extends DomainError implements GroupRepositoryException
{
    private const ERROR_CODE = 'user_group_not_found';
    private const ERROR_MESSAGE = 'The user with id <%s> is not in the group with id <%s>';

    public function __construct(private readonly UserId $userId, private readonly GroupId $groupId)
    {
        parent::__construct();
    }

    public function errorCode(): string
    {
        return self::ERROR_CODE;
    }

    protected function errorMessage(): string
    {
        return sprintf(self::ERROR_MESSAGE, $this->userId->value(), $this->groupId->value());
    }
}
