<?php

declare(strict_types=1);

namespace App\User\Domain\Repository\Exceptions;

use App\Shared\Domain\DomainError;
use App\User\Domain\Model\User\UserName;

final class UserAlreadyExistException extends DomainError implements UserRepositoryException
{
    private const ERROR_CODE = 'user_already_exist';
    private const ERROR_MESSAGE = 'The user with name <%s> already exist';

    public function __construct(private readonly UserName $name)
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
