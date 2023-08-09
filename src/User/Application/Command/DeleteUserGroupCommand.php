<?php

declare(strict_types=1);

namespace App\User\Application\Command;

final class DeleteUserGroupCommand
{
    public function __construct(private readonly string $userId, private readonly string $groupId)
    {
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
