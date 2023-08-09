<?php

declare(strict_types=1);

namespace App\User\Domain\Repository\Exceptions;

use App\Shared\Domain\DomainError;
use App\User\Domain\Model\User\UserId;

final class UserNotFoundException extends DomainError implements UserRepositoryException
{
    private const ERROR_CODE = 'user_not_found';
    private const ERROR_MESSAGE = 'The user <%s> does not exist';

    public function __construct(private readonly UserId $id)
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
