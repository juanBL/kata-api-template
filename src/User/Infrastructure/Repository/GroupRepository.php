<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Repository;

use App\Shared\Infrastructure\Persistence\Doctrine\DoctrineRepository;
use App\User\Domain\Model\Group;
use App\User\Domain\Model\Group\GroupName;
use App\User\Domain\Model\Group\GroupId;
use App\User\Domain\Repository\Exceptions\DatabaseGroupRepositoryException;
use App\User\Domain\Repository\GroupRepositoryInterface;
use Doctrine\ORM\Exception\ORMException;
use Throwable;

class GroupRepository extends DoctrineRepository implements GroupRepositoryInterface
{

    public function aggregateRootFQCN(): string
    {
        return Group::class;
    }

    /**
     * @throws DatabaseGroupRepositoryException
     */
    public function save(Group $group): void
    {
        try {
            $this->persist($group);
        } catch (ORMException|Throwable) {
            throw new DatabaseGroupRepositoryException('Unexpected API error');
        }
    }

    /**
     * @throws DatabaseGroupRepositoryException
     */
    public function delete(Group $group): void
    {
        try {
            $this->remove($group);
        } catch (Throwable) {
            throw new DatabaseGroupRepositoryException('Unexpected API error');
        }
    }

    /**
     * @throws DatabaseGroupRepositoryException
     */
    public function search(GroupId $id): ?Group
    {
        try {
            return $this->repository()->find($id);
        } catch (Throwable) {
            throw new DatabaseGroupRepositoryException('Unexpected API error');
        }
    }

    /**
     * @throws DatabaseGroupRepositoryException
     */
    public function searchByName(GroupName $name): ?Group
    {
        try {
            return $this->repository()->findOneBy(['name.value' => $name->value()]);
        } catch (Throwable) {
            throw new DatabaseGroupRepositoryException('Unexpected API error');
        }
    }
}
