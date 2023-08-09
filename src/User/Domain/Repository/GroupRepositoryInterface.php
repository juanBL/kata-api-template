<?php

declare(strict_types=1);

namespace App\User\Domain\Repository;


use App\User\Domain\Model\Group;
use App\User\Domain\Model\Group\GroupId;
use App\User\Domain\Model\Group\GroupName;
use App\User\Domain\Repository\Exceptions\DatabaseGroupRepositoryException;

interface GroupRepositoryInterface
{
    /**
     * @throws DatabaseGroupRepositoryException
     */
    public function save(Group $group): void;

    /**
     * @throws DatabaseGroupRepositoryException
     */
    public function delete(Group $group): void;

    /**
     * @throws DatabaseGroupRepositoryException
     */
    public function search(GroupId $id): ?Group;

    /**
     * @throws DatabaseGroupRepositoryException
     */
    public function searchByName(GroupName $name): ?Group;
}
