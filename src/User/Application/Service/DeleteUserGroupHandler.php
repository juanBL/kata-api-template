<?php

declare(strict_types=1);

namespace App\User\Application\Service;

use App\User\Application\Command\DeleteUserGroupCommand;
use App\User\Domain\Model\Group\GroupId;
use App\User\Domain\Model\User\UserId;
use App\User\Domain\Model\UserGroup;
use App\User\Domain\Repository\Exceptions\DatabaseGroupRepositoryException;
use App\User\Domain\Repository\Exceptions\DatabaseUserGroupRepositoryException;
use App\User\Domain\Repository\Exceptions\DatabaseUserRepositoryException;
use App\User\Domain\Repository\Exceptions\UserGroupNotFoundException;
use App\User\Domain\Repository\GroupRepositoryInterface;
use App\User\Domain\Repository\UserGroupRepositoryInterface;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\Service\GroupFinder;
use App\User\Domain\Service\UserFinder;
use App\User\Domain\Service\UserGroupByUserAndGroupSearcher;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class DeleteUserGroupHandler implements MessageHandlerInterface
{
    private UserFinder $userFinder;
    private GroupFinder $groupFinder;
    private UserGroupByUserAndGroupSearcher $searcher;

    public function __construct(
        private readonly UserGroupRepositoryInterface $repository,
        UserRepositoryInterface $userRepository,
        GroupRepositoryInterface $groupRepository,
    ) {
        $this->userFinder = new UserFinder($userRepository);
        $this->groupFinder = new GroupFinder($groupRepository);
        $this->searcher = new UserGroupByUserAndGroupSearcher($this->repository);
    }

    /**
     * @throws DatabaseUserRepositoryException
     * @throws DatabaseGroupRepositoryException
     * @throws DatabaseUserGroupRepositoryException
     */
    public function __invoke(DeleteUserGroupCommand $command): void
    {
        $user = $this->userFinder->__invoke(new UserId($command->userId()));
        $group = $this->groupFinder->__invoke(new GroupId($command->groupId()));
        $userGroup = $this->searcher->__invoke($user, $group);

        if (!$userGroup instanceof UserGroup) {
            throw new UserGroupNotFoundException($user->id(), $group->id());
        }

        $this->repository->delete($userGroup);
    }
}
