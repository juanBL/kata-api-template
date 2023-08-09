<?php

declare(strict_types=1);

namespace App\User\Domain\Model;

use App\Shared\Domain\Aggregate\AggregateRoot;
use App\User\Domain\Model\Group\GroupId;
use App\User\Domain\Model\Group\GroupName;
use App\User\Domain\Repository\Exceptions\DatabaseUserGroupRepositoryException;
use App\User\Domain\Service\UserGroupsByGroupIdFinder;

class Group extends AggregateRoot
{
    public function __construct(private readonly GroupId $id, private readonly GroupName $name)
    {
    }

    public static function create(GroupId $id, GroupName $name): self
    {
        return new self($id, $name);
    }

    public function id(): GroupId
    {
        return $this->id;
    }

    public function name(): GroupName
    {
        return $this->name;
    }
}
