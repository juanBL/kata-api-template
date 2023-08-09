<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Repository;

use App\Shared\Infrastructure\Persistence\Doctrine\DoctrineRepository;
use App\User\Domain\Model\Group;
use App\User\Domain\Model\User;
use App\User\Domain\Model\UserGroup;
use App\User\Domain\Repository\Exceptions\DatabaseUserGroupRepositoryException;
use App\User\Domain\Repository\UserGroupRepositoryInterface;
use Doctrine\ORM\Exception\ORMException;
use Throwable;

class UserGroupRepository extends DoctrineRepository implements UserGroupRepositoryInterface
{

    public function aggregateRootFQCN(): string
    {
        return UserGroup::class;
    }

    /**
     * @throws DatabaseUserGroupRepositoryException
     */
    public function save(UserGroup $userGroup): void
    {
        try {
            $this->persist($userGroup);
        } catch (ORMException|Throwable) {
            throw new DatabaseUserGroupRepositoryException('Unexpected API error');
        }
    }

    /**
     * @throws DatabaseUserGroupRepositoryException
     */
    public function delete(UserGroup $userGroup): void
    {
        try {
            $this->remove($userGroup);
        } catch (Throwable) {
            throw new DatabaseUserGroupRepositoryException('Unexpected API error');
        }
    }

    public function userExistInGroup(User $user, Group $group): ?UserGroup
    {
        try {
            return $this->repository()->findOneBy(['user' => $user, 'group' => $group]);
        } catch (Throwable) {
            throw new DatabaseUserGroupRepositoryException('Unexpected API error');
        }
    }

    public function userGroupsByGroup(Group $group): array
    {
        try {
            return $this->repository()->findBy(['group' => $group]);
        } catch (Throwable) {
            throw new DatabaseUserGroupRepositoryException('Unexpected API error');
        }
    }
}
