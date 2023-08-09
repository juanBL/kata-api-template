<?php

declare(strict_types=1);

namespace App\User\Application\Service;

use App\User\Application\Command\DeleteGroupCommand;
use App\User\Domain\Model\Group;
use App\User\Domain\Model\Group\GroupId;
use App\User\Domain\Repository\Exceptions\DatabaseGroupRepositoryException;
use App\User\Domain\Repository\Exceptions\DatabaseUserGroupRepositoryException;
use App\User\Domain\Repository\Exceptions\GroupStillHaveUsersException;
use App\User\Domain\Repository\GroupRepositoryInterface;
use App\User\Domain\Repository\UserGroupRepositoryInterface;
use App\User\Domain\Service\GroupFinder;
use App\User\Domain\Service\UserGroupsByGroupIdFinder;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class DeleteGroupHandler implements MessageHandlerInterface
{
    private GroupFinder $finder;
    private UserGroupsByGroupIdFinder $userGroupsByGroupIdFinder;

    public function __construct(
        private readonly GroupRepositoryInterface $repository,
        UserGroupRepositoryInterface $userGroupRepository
    ) {
        $this->finder = new GroupFinder($repository);
        $this->userGroupsByGroupIdFinder = new UserGroupsByGroupIdFinder($userGroupRepository);
    }

    /**
     * @throws DatabaseGroupRepositoryException
     * @throws DatabaseUserGroupRepositoryException
     */
    public function __invoke(DeleteGroupCommand $command): void
    {
        $groupId = new GroupId($command->id());
        $group = $this->finder->__invoke($groupId);

        $this->ensureGroupHasNoUsers($group, $groupId);

        $this->repository->delete($group);
    }

    /**
     * @throws DatabaseUserGroupRepositoryException
     */
    public function ensureGroupHasNoUsers(Group $group, GroupId $groupId): void
    {
        $userGroups = $this->userGroupsByGroupIdFinder->__invoke($group);
        if (!empty($userGroups)) {
            throw new GroupStillHaveUsersException($groupId);
        }
    }
}
