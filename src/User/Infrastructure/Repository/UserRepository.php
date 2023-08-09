<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Repository;

use App\Shared\Infrastructure\Persistence\Doctrine\DoctrineRepository;
use App\User\Domain\Model\User;
use App\User\Domain\Model\User\UserId;
use App\User\Domain\Model\User\UserName;
use App\User\Domain\Repository\Exceptions\DatabaseUserRepositoryException;
use App\User\Domain\Repository\UserRepositoryInterface;
use Doctrine\ORM\Exception\ORMException;
use Throwable;

class UserRepository extends DoctrineRepository implements UserRepositoryInterface
{

    public function aggregateRootFQCN(): string
    {
        return User::class;
    }

    /**
     * @throws DatabaseUserRepositoryException
     */
    public function save(User $user): void
    {
        try {
            $this->persist($user);
        } catch (ORMException|Throwable) {
            throw new DatabaseUserRepositoryException('Unexpected API error');
        }
    }

    /**
     * @throws DatabaseUserRepositoryException
     */
    public function delete(User $user): void
    {
        try {
            $this->remove($user);
        } catch (Throwable) {
            throw new DatabaseUserRepositoryException('Unexpected API error');
        }
    }

    /**
     * @throws DatabaseUserRepositoryException
     */
    public function search(UserId $id): ?User
    {
        try {
            return $this->repository()->find($id);
        } catch (Throwable) {
            throw new DatabaseUserRepositoryException('Unexpected API error');
        }
    }

    /**
     * @throws DatabaseUserRepositoryException
     */
    public function searchByName(UserName $name): ?User
    {
        try {
            return $this->repository()->findOneBy(['name.value' => $name->value()]);
        } catch (Throwable) {
            throw new DatabaseUserRepositoryException('Unexpected API error');
        }
    }
}
