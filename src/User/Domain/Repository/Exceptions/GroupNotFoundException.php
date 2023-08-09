<?php

declare(strict_types=1);

namespace App\User\Domain\Repository\Exceptions;

use App\Shared\Domain\DomainError;
use App\User\Domain\Model\Group\GroupId;

final class GroupNotFoundException extends DomainError implements GroupRepositoryException
{
    private const ERROR_CODE = 'group_not_found';
    private const ERROR_MESSAGE = 'The group <%s> does not exist';

    public function __construct(private readonly GroupId $id)
    {
        parent::__construct();
    }

    public function errorCode(): string
    {
        return self::ERROR_CODE;
    }

    protected function errorMessage(): string
    {
        return sprintf(self::ERROR_MESSAGE, $this->id->value());
    }
}
