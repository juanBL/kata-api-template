<?php

declare(strict_types=1);

namespace App\User\Domain\Repository\Exceptions;

use App\Shared\Domain\DomainError;
use App\User\Domain\Model\Group\GroupName;

final class GroupAlreadyExistException extends DomainError implements GroupRepositoryException
{
    private const ERROR_CODE = 'group_already_exist';
    private const ERROR_MESSAGE = 'The group with name <%s> already exist';

    public function __construct(private readonly GroupName $name)
    {
        parent::__construct();
    }

    public function errorCode(): string
    {
        return self::ERROR_CODE;
    }

    protected function errorMessage(): string
    {
        return sprintf(self::ERROR_MESSAGE, $this->name->value());
    }
}
