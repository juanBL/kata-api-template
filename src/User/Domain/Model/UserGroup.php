<?php

declare(strict_types=1);

namespace App\User\Domain\Model;

use App\Shared\Domain\Aggregate\AggregateRoot;
use App\User\Domain\Model\Group\GroupId;
use App\User\Domain\Model\User\UserId;
use App\User\Domain\Model\UserGroup\UserGroupId;
use App\User\Domain\Repository\Exceptions\DatabaseGroupRepositoryException;
use App\User\Domain\Repository\Exceptions\DatabaseUserGroupRepositoryException;
use App\User\Domain\Repository\Exceptions\DatabaseUserRepositoryException;
use App\User\Domain\Repository\Exceptions\UserGroupAlreadyExistException;
use App\User\Domain\Service\GroupFinder;
use App\User\Domain\Service\UserFinder;
use App\User\Domain\Service\UserGroupByUserAndGroupSearcher;

class UserGroup extends AggregateRoot
{
    private User $user;
    private Group $group;

    public function __construct(
        private readonly UserGroupId $id,
        private readonly UserId $userId,
        private readonly GroupId $groupId
    ) {
    }

    public static function create(UserGroupId $id, UserId $userId, GroupId $groupId): self
    {
        return new self($id, $userId, $groupId);
    }

    public function id(): UserGroupId
    {
        return $this->id;
    }

    public function userId(): UserId
    {
        return $this->userId;
    }

    public function groupId(): GroupId
    {
        return $this->groupId;
    }

    /**
     * @throws DatabaseUserRepositoryException
     * @throws DatabaseGroupRepositoryException
     * @throws DatabaseUserGroupRepositoryException
     */
    public function addUserToGroup(
        UserFinder $userFinder,
        GroupFinder $groupFinder,
        UserGroupByUserAndGroupSearcher $userGroupByUserAndGroupSearcher,
    ): void {
        $user = $userFinder->__invoke($this->userId());
        $group = $groupFinder->__invoke($this->groupId());

        $userGroup = $userGroupByUserAndGroupSearcher->__invoke($user, $group);

        if ($userGroup instanceof UserGroup) {
            throw new UserGroupAlreadyExistException($this->userId(), $this->groupId());
        }

        $this->user = $user;
        $this->group = $group;
    }
}
