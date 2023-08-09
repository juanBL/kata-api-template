<?php

declare(strict_types=1);

namespace App\User\Domain\Service;

use App\User\Domain\Model\User;
use App\User\Domain\Model\User\UserName;
use App\User\Domain\Repository\Exceptions\DatabaseUserRepositoryException;
use App\User\Domain\Repository\UserRepositoryInterface;

final class UserByNameSearcher
{
    public function __construct(private readonly UserRepositoryInterface $repository)
    {
    }

    /**
     * @throws DatabaseUserRepositoryException
     */
    public function __invoke(UserName $name): ?User
    {
        return $this->repository->searchByName($name);
    }
}
