<?php

declare(strict_types=1);

namespace App\User\Domain\Model;

use App\Shared\Domain\Aggregate\AggregateRoot;
use App\User\Domain\Model\User\UserId;
use App\User\Domain\Model\User\UserName;

class User extends AggregateRoot
{
    public function __construct(private readonly UserId $id, private readonly UserName $name)
    {
    }

    public static function create(UserId $id, UserName $name): self
    {
        return new self($id, $name);
    }

    public function id(): UserId
    {
        return $this->id;
    }

    public function name(): UserName
    {
        return $this->name;
    }
}
