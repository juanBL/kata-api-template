<?php

declare(strict_types=1);

namespace App\User\Domain\Service;

use App\User\Domain\Model\Group;
use App\User\Domain\Model\UserGroup;
use App\User\Domain\Repository\Exceptions\DatabaseUserGroupRepositoryException;
use App\User\Domain\Repository\UserGroupRepositoryInterface;

final class UserGroupsByGroupIdFinder
{
    public function __construct(private readonly UserGroupRepositoryInterface $repository)
    {
    }

    /**
     * @return UserGroup[]
     * @throws DatabaseUserGroupRepositoryException
     */
    public function __invoke(Group $group): array
    {
        return $this->repository->userGroupsByGroup($group);
    }
}
