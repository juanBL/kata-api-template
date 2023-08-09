<?php

declare(strict_types=1);

namespace App\User\Domain\Repository;

use App\User\Domain\Model\User;
use App\User\Domain\Model\User\UserId;
use App\User\Domain\Model\User\UserName;
use App\User\Domain\Repository\Exceptions\DatabaseUserRepositoryException;

interface UserRepositoryInterface
{
    /**
     * @throws DatabaseUserRepositoryException
     */
    public function save(User $user): void;

    /**
     * @throws DatabaseUserRepositoryException
     */
    public function delete(User $user): void;

    /**
     * @throws DatabaseUserRepositoryException
     */
    public function search(UserId $id): ?User;

    /**
     * @throws DatabaseUserRepositoryException
     */
    public function searchByName(UserName $name): ?User;
}
