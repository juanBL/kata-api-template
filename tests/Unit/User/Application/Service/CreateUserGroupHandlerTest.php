<?php

declare(strict_types=1);

namespace App\Tests\Unit\User\Application\Service;

use App\Tests\Unit\User\Application\Command\CreateUserGroupCommandMother;
use App\Tests\Unit\User\Domain\Model\GroupMother;
use App\Tests\Unit\User\Domain\Model\UserGroupMother;
use App\Tests\Unit\User\Domain\Model\UserMother;
use App\User\Application\Service\CreateUserGroupHandler;
use App\User\Domain\Model\Group;
use App\User\Domain\Repository\Exceptions\GroupNotFoundException;
use App\User\Domain\Repository\Exceptions\UserGroupAlreadyExistException;
use App\User\Domain\Repository\Exceptions\UserNotFoundException;
use App\User\Domain\Repository\GroupRepositoryInterface;
use App\User\Domain\Repository\UserGroupRepositoryInterface;
use App\User\Domain\Repository\UserRepositoryInterface;
use PHPUnit\Framework\TestCase;

class CreateUserGroupHandlerTest extends TestCase
{
    private CreateUserGroupHandler $handler;
    private UserGroupRepositoryInterface $repository;
    private UserRepositoryInterface $userRepository;
    private GroupRepositoryInterface $groupRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->groupRepository = $this->createMock(GroupRepositoryInterface::class);
        $this->repository = $this->createMock(UserGroupRepositoryInterface::class);
        $this->handler = new CreateUserGroupHandler($this->userRepository, $this->groupRepository, $this->repository);
    }

    public function testItShouldCreateAnUserGroup(): void
    {
        $user = UserMother::create();
        $group = GroupMother::create();
        $userGroup = UserGroupMother::create();

        $command = CreateUserGroupCommandMother::create($userGroup->id(), $user->id(), $group->id());

        $this->userRepository->method('search')->with($user->id())->willReturn($user);
        $this->groupRepository->method('search')->with($group->id())->willReturn($group);
        $this->repository->method('userExistInGroup')->with(
            $this->equalToWithDelta($user, 1),
            $this->equalToWithDelta($group, 1)
        )->willReturn(null);

        $this->assertInstanceOf(Group::class, $group);

        $this->handler->__invoke($command);
    }

    public function testItShouldNotCreateAnUserGroup(): void
    {
        $user = UserMother::create();
        $group = GroupMother::create();
        $userGroup = UserGroupMother::create();

        $command = CreateUserGroupCommandMother::create($userGroup->id(), $user->id(), $group->id());

        $this->userRepository->method('search')->with($user->id())->willReturn($user);
        $this->groupRepository->method('search')->with($group->id())->willReturn($group);
        $this->repository->method('userExistInGroup')->with(
            $this->equalToWithDelta($user, 1),
            $this->equalToWithDelta($group, 1)
        )->willReturn($userGroup);

        $exception = new UserGroupAlreadyExistException($user->id(), $group->id());
        $this->assertEquals($exception->errorCode(), 'user_is_already_in_group');
        $this->expectException($exception::class);

        $this->handler->__invoke($command);
    }

    public function testItShouldNotCreateAnUserGroupBecauseUserNotExist(): void
    {
        $user = UserMother::create();
        $group = GroupMother::create();
        $userGroup = UserGroupMother::create();

        $command = CreateUserGroupCommandMother::create($userGroup->id(), $user->id(), $group->id());

        $this->userRepository->method('search')->with($user->id())->willReturn(null);

        $exception = new UserNotFoundException($user->id());
        $this->assertEquals($exception->errorCode(), 'user_not_found');
        $this->expectException($exception::class);

        $this->handler->__invoke($command);
    }

    public function testItShouldNotCreateAnUserGroupBecauseGroupNotExist(): void
    {
        $user = UserMother::create();
        $group = GroupMother::create();
        $userGroup = UserGroupMother::create();

        $command = CreateUserGroupCommandMother::create($userGroup->id(), $user->id(), $group->id());

        $this->userRepository->method('search')->with($user->id())->willReturn($user);
        $this->groupRepository->method('search')->with($group->id())->willReturn(null);

        $exception = new GroupNotFoundException($group->id());
        $this->assertEquals($exception->errorCode(), 'group_not_found');
        $this->expectException($exception::class);

        $this->handler->__invoke($command);
    }
}
