<?php

declare(strict_types=1);

namespace App\User\Domain\Service;

use App\User\Domain\Model\Group;
use App\User\Domain\Model\User;
use App\User\Domain\Model\UserGroup;
use App\User\Domain\Repository\Exceptions\DatabaseUserGroupRepositoryException;
use App\User\Domain\Repository\UserGroupRepositoryInterface;

final class UserGroupByUserAndGroupSearcher
{
    public function __construct(private readonly UserGroupRepositoryInterface $repository)
    {
    }

    /**
     * @throws DatabaseUserGroupRepositoryException
     */
    public function __invoke(User $user, Group $group): ?UserGroup
    {
        return $this->repository->userExistInGroup($user, $group);
    }
}
