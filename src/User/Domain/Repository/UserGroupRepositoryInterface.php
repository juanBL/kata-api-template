<?php

declare(strict_types=1);

namespace App\User\Domain\Repository;


use App\User\Domain\Model\Group;
use App\User\Domain\Model\User;
use App\User\Domain\Model\UserGroup;
use App\User\Domain\Repository\Exceptions\DatabaseUserGroupRepositoryException;

interface UserGroupRepositoryInterface
{
    /**
     * @throws DatabaseUserGroupRepositoryException
     */
    public function save(UserGroup $userGroup): void;

    /**
     * @throws DatabaseUserGroupRepositoryException
     */
    public function delete(UserGroup $userGroup): void;

    /**
     * @throws DatabaseUserGroupRepositoryException
     */
    public function userExistInGroup(User $user, Group $group): ?UserGroup;

    /**
     * @throws DatabaseUserGroupRepositoryException
     * @return UserGroup[]
     */
    public function userGroupsByGroup(Group $group): array;
}
