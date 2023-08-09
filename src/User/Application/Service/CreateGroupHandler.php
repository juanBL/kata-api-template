<?php

declare(strict_types=1);

namespace App\User\Application\Service;

use App\User\Application\Command\CreateGroupCommand;
use App\User\Domain\Model\Group;
use App\User\Domain\Model\Group\GroupId;
use App\User\Domain\Model\Group\GroupName;
use App\User\Domain\Repository\Exceptions\DatabaseGroupRepositoryException;
use App\User\Domain\Repository\Exceptions\GroupAlreadyExistException;
use App\User\Domain\Repository\GroupRepositoryInterface;
use App\User\Domain\Service\GroupByNameSearcher;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class CreateGroupHandler implements MessageHandlerInterface
{
    private GroupByNameSearcher $groupByNameSearcher;

    public function __construct(private readonly GroupRepositoryInterface $repository)
    {
        $this->groupByNameSearcher = new GroupByNameSearcher($repository);
    }

    /**
     * @throws DatabaseGroupRepositoryException
     */
    public function __invoke(CreateGroupCommand $command): void
    {
        $group = $this->groupByNameSearcher->__invoke(new GroupName($command->name()));

        if ($group instanceof Group) {
            throw new GroupAlreadyExistException(new GroupName($command->name()));
        }

        $group = Group::create(new GroupId($command->id()), new GroupName($command->name()));
        $this->repository->save($group);
    }
}
