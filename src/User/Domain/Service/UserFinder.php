<?php

declare(strict_types=1);

namespace App\User\Domain\Service;

use App\User\Domain\Model\User;
use App\User\Domain\Model\User\UserId;
use App\User\Domain\Repository\Exceptions\DatabaseUserRepositoryException;
use App\User\Domain\Repository\Exceptions\UserNotFoundException;
use App\User\Domain\Repository\UserRepositoryInterface;

final class UserFinder
{
    public function __construct(private readonly UserRepositoryInterface $repository)
    {
    }

    /**
     * @throws DatabaseUserRepositoryException
     */
    public function __invoke(UserId $id): ?User
    {
        $user = $this->repository->search($id);

        if (!$user instanceof User) {
            throw new UserNotFoundException($id);
        }

        return $user;
    }
}
