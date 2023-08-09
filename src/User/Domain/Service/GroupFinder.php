<?php

declare(strict_types=1);

namespace App\User\Domain\Service;

use App\User\Domain\Model\Group;
use App\User\Domain\Model\Group\GroupId;
use App\User\Domain\Repository\Exceptions\DatabaseGroupRepositoryException;
use App\User\Domain\Repository\Exceptions\GroupNotFoundException;
use App\User\Domain\Repository\GroupRepositoryInterface;

final class GroupFinder
{
    public function __construct(private readonly GroupRepositoryInterface $repository)
    {
    }

    /**
     * @throws DatabaseGroupRepositoryException
     */
    public function __invoke(GroupId $id): ?Group
    {
        $group = $this->repository->search($id);

        if (!$group instanceof Group) {
            throw new GroupNotFoundException($id);
        }

        return $group;
    }
}
