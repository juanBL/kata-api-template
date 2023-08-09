<?php

declare(strict_types=1);

namespace App\Tests\Unit\User\Application\Service;

use App\Tests\Unit\User\Application\Command\DeleteGroupCommandMother;
use App\Tests\Unit\User\Domain\Model\GroupMother;
use App\Tests\Unit\User\Domain\Model\UserGroupMother;
use App\User\Application\Service\DeleteGroupHandler;
use App\User\Domain\Model\Group;
use App\User\Domain\Repository\Exceptions\GroupStillHaveUsersException;
use App\User\Domain\Repository\GroupRepositoryInterface;
use App\User\Domain\Repository\UserGroupRepositoryInterface;
use PHPUnit\Framework\TestCase;

class DeleteGroupHandlerTest extends TestCase
{
    private DeleteGroupHandler $handler;
    private GroupRepositoryInterface $repository;
    private UserGroupRepositoryInterface $userGroupRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->createMock(GroupRepositoryInterface::class);
        $this->userGroupRepository = $this->createMock(UserGroupRepositoryInterface::class);
        $this->handler = new DeleteGroupHandler($this->repository, $this->userGroupRepository);
    }

    public function testItShouldDeleteAGroup(): void
    {
        $group = GroupMother::create();
        $command = DeleteGroupCommandMother::create($group->id());

        $this->repository->method('search')->with($group->id())->willReturn($group);
        $this->userGroupRepository->method('userGroupsByGroup')->with($this->equalToWithDelta($group, 1))->willReturn([]
        );

        $this->assertInstanceOf(Group::class, $group);

        $this->handler->__invoke($command);
    }

    public function testItShouldNotDeleteAGroup(): void
    {
        $group = GroupMother::create();
        $userGroup = UserGroupMother::create();
        $command = DeleteGroupCommandMother::create($group->id());

        $this->repository->method('search')->with($group->id())->willReturn($group);
        $this->userGroupRepository->method('userGroupsByGroup')->with($this->equalToWithDelta($group, 1))->willReturn(
            [$userGroup]
        );

        $exception = new GroupStillHaveUsersException($group->id());
        $this->assertEquals($exception->errorCode(), 'group_still_have_users');
        $this->expectException($exception::class);

        $this->handler->__invoke($command);
    }
}
