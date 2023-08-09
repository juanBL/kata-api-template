<?php

declare(strict_types=1);

namespace App\Tests\Unit\User\Application\Service;

use App\Tests\Unit\User\Application\Command\CreateUserCommandMother;
use App\Tests\Unit\User\Domain\Model\UserMother;
use App\User\Application\Service\CreateUserHandler;
use App\User\Domain\Model\User;
use App\User\Domain\Repository\Exceptions\UserAlreadyExistException;
use App\User\Domain\Repository\UserRepositoryInterface;
use PHPUnit\Framework\TestCase;

class CreateUserHandlerTest extends TestCase
{
    private CreateUserHandler $handler;
    private UserRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->createMock(UserRepositoryInterface::class);
        $this->handler = new CreateUserHandler($this->repository);
    }

    public function testItShouldCreateAUser(): void
    {
        $user = UserMother::create();
        $command = CreateUserCommandMother::create($user->id(), $user->name());

        $this->repository->method('searchByName')->with($user->name())->willReturn(null);
        $this->repository->method('save')->with($this->equalToWithDelta($user, 1));

        $this->assertInstanceOf(User::class, $user);

        $this->handler->__invoke($command);
    }

    public function testItShouldNotCreateAUserWithAnExistingUserName(): void
    {
        $user = UserMother::create();
        $command = CreateUserCommandMother::create($user->id(), $user->name());

        $this->repository->method('searchByName')->with($user->name())->willReturn($user);

        $exception = new UserAlreadyExistException($user->name());
        $this->assertEquals($exception->errorCode(), 'user_already_exist');
        $this->expectException($exception::class);

        $this->handler->__invoke($command);
    }
}
