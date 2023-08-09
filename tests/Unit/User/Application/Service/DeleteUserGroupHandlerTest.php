<?php

declare(strict_types=1);

namespace App\Tests\Unit\User\Application\Service;

use App\Tests\Unit\User\Application\Command\DeleteUserGroupCommandMother;
use App\Tests\Unit\User\Domain\Model\GroupMother;
use App\Tests\Unit\User\Domain\Model\UserGroupMother;
use App\Tests\Unit\User\Domain\Model\UserMother;
use App\User\Application\Service\DeleteUserGroupHandler;
use App\User\Domain\Model\UserGroup;
use App\User\Domain\Repository\Exceptions\UserGroupNotFoundException;
use App\User\Domain\Repository\GroupRepositoryInterface;
use App\User\Domain\Repository\UserGroupRepositoryInterface;
use App\User\Domain\Repository\UserRepositoryInterface;
use PHPUnit\Framework\TestCase;

class DeleteUserGroupHandlerTest extends TestCase
{
    private DeleteUserGroupHandler $handler;
    private UserGroupRepositoryInterface $repository;
    private UserRepositoryInterface $userRepository;
    private GroupRepositoryInterface $groupRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->groupRepository = $this->createMock(GroupRepositoryInterface::class);

        $this->repository = $this->createMock(UserGroupRepositoryInterface::class);

        $this->handler = new DeleteUserGroupHandler($this->repository, $this->userRepository, $this->groupRepository);
    }

    public function testItShouldDeleteAnUserGroup(): void
    {
        $user = UserMother::create();
        $group = GroupMother::create();
        $userGroup = UserGroupMother::create();

        $command = DeleteUserGroupCommandMother::create($user->id(), $group->id());

        $this->userRepository->method('search')->with($user->id())->willReturn($user);
        $this->groupRepository->method('search')->with($group->id())->willReturn($group);
        $this->repository->method('userExistInGroup')->with(
            $this->equalToWithDelta($user, 1),
            $this->equalToWithDelta($group, 1)
        )->willReturn($userGroup);

        $this->assertInstanceOf(UserGroup::class, $userGroup);

        $this->handler->__invoke($command);
    }

    public function testItShouldNotDeleteAnUserGroup11(): void
    {
        $user = UserMother::create();
        $group = GroupMother::create();

        $command = DeleteUserGroupCommandMother::create($user->id(), $group->id());

        $this->userRepository->method('search')->with($user->id())->willReturn($user);
        $this->groupRepository->method('search')->with($group->id())->willReturn($group);
        $this->repository->method('userExistInGroup')->with(
            $this->equalToWithDelta($user, 1),
            $this->equalToWithDelta($group, 1)
        )->willReturn(null);

        $exception = new UserGroupNotFoundException($user->id(), $group->id());
        $this->assertEquals($exception->errorCode(), 'user_group_not_found');
        $this->expectException($exception::class);

        $this->handler->__invoke($command);
    }
}
