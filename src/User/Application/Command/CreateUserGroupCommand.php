<?php

declare(strict_types=1);

namespace App\User\Application\Command;

final class CreateUserGroupCommand
{
    public function __construct(
        private readonly string $id,
        private readonly string $userId,
        private readonly string $groupId
    ) {
    }

    public function id(): string
    {
        return $this->id;
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function groupId(): string
    {
        return $this->groupId;
    }
}
