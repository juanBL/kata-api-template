<?php

declare(strict_types=1);

namespace App\User\Application\Command;

final class DeleteUserCommand
{
    public function __construct(private readonly string $id)
    {
    }

    public function id(): string
    {
        return $this->id;
    }
}
