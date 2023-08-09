<?php

declare(strict_types=1);

namespace App\User\Application\Service;

use App\User\Application\Command\CreateUserGroupCommand;
use App\User\Domain\Model\Group\GroupId;
use App\User\Domain\Model\User\UserId;
use App\User\Domain\Model\UserGroup;
use App\User\Domain\Model\UserGroup\UserGroupId;
use App\User\Domain\Repository\Exceptions\DatabaseGroupRepositoryException;
use App\User\Domain\Repository\Exceptions\DatabaseUserGroupRepositoryException;
use App\User\Domain\Repository\Exceptions\DatabaseUserRepositoryException;
use App\User\Domain\Repository\GroupRepositoryInterface;
use App\User\Domain\Repository\UserGroupRepositoryInterface;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\Service\GroupFinder;
use App\User\Domain\Service\UserFinder;
use App\User\Domain\Service\UserGroupByUserAndGroupSearcher;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class CreateUserGroupHandler implements MessageHandlerInterface
{
    private UserFinder $userFinder;
    private GroupFinder $groupFinder;
    private UserGroupByUserAndGroupSearcher $userGroupByUserAndGroupSearcher;

    public function __construct(
        UserRepositoryInterface $userRepository,
        GroupRepositoryInterface $groupRepository,
        private readonly UserGroupRepositoryInterface $userGroupRepository,
    ) {
        $this->userFinder = new UserFinder($userRepository);
        $this->groupFinder = new GroupFinder($groupRepository);
        $this->userGroupByUserAndGroupSearcher = new UserGroupByUserAndGroupSearcher($userGroupRepository);
    }

    /**
     * @throws DatabaseUserRepositoryException
     * @throws DatabaseGroupRepositoryException
     * @throws DatabaseUserGroupRepositoryException
     */
    public function __invoke(CreateUserGroupCommand $command): void
    {
        $userGroup = UserGroup::create(
            new UserGroupId($command->id()),
            new UserId($command->userId()),
            new GroupId($command->groupId())
        );

        $userGroup->addUserToGroup($this->userFinder, $this->groupFinder, $this->userGroupByUserAndGroupSearcher);
        $this->userGroupRepository->save($userGroup);
    }
}
