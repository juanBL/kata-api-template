<?php

declare(strict_types=1);

namespace App\Tests\Unit\User\Application\Service;

use App\Tests\Unit\User\Application\Command\CreateGroupCommandMother;
use App\Tests\Unit\User\Domain\Model\GroupMother;
use App\User\Application\Service\CreateGroupHandler;
use App\User\Domain\Model\Group;
use App\User\Domain\Repository\Exceptions\GroupAlreadyExistException;
use App\User\Domain\Repository\GroupRepositoryInterface;
use PHPUnit\Framework\TestCase;

class CreateGroupHandlerTest extends TestCase
{
    private CreateGroupHandler $handler;
    private GroupRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->createMock(GroupRepositoryInterface::class);
        $this->handler = new CreateGroupHandler($this->repository);
    }

    public function testItShouldCreateAGroup(): void
    {
        $group = GroupMother::create();
        $command = CreateGroupCommandMother::create($group->id(), $group->name());

        $this->repository->method('searchByName')->with($group->name())->willReturn(null);
        $this->repository->method('save')->with($this->equalToWithDelta($group, 1));

        $this->assertInstanceOf(Group::class, $group);

        $this->handler->__invoke($command);
    }

    public function testItShouldNotCreateAGroupWithAnExistingUserName(): void
    {
        $group = GroupMother::create();
        $command = CreateGroupCommandMother::create($group->id(), $group->name());

        $this->repository->method('searchByName')->with($group->name())->willReturn($group);

        $exception = new GroupAlreadyExistException($group->name());
        $this->assertEquals($exception->errorCode(), 'group_already_exist');
        $this->expectException($exception::class);

        $this->handler->__invoke($command);
    }
}
