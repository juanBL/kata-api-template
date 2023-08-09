<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Repository\Doctrine;

use App\Shared\Infrastructure\Persistence\Doctrine\UuidType;
use App\User\Domain\Model\Group\GroupId;

final class GroupIdType extends UuidType
{
    protected function typeClassName(): string
    {
        return GroupId::class;
    }
}
