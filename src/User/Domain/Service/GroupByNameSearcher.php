<?php

declare(strict_types=1);

namespace App\User\Domain\Service;

use App\User\Domain\Model\Group;
use App\User\Domain\Model\Group\GroupName;
use App\User\Domain\Repository\Exceptions\DatabaseGroupRepositoryException;
use App\User\Domain\Repository\GroupRepositoryInterface;

final class GroupByNameSearcher
{
    public function __construct(private readonly GroupRepositoryInterface $repository)
    {
    }

    /**
     * @throws DatabaseGroupRepositoryException
     */
    public function __invoke(GroupName $name): ?Group
    {
        return $this->repository->searchByName($name);
    }
}
