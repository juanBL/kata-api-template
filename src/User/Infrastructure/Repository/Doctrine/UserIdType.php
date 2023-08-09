<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Repository\Doctrine;

use App\Shared\Infrastructure\Persistence\Doctrine\UuidType;
use App\User\Domain\Model\User\UserId;

final class UserIdType extends UuidType
{
    protected function typeClassName(): string
    {
        return UserId::class;
    }
}
