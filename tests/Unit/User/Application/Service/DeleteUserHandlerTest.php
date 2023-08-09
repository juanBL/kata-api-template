<?php

declare(strict_types=1);

namespace App\Tests\Unit\User\Application\Service;

use App\Tests\Unit\User\Application\Command\CreateUserCommandMother;
use App\Tests\Unit\User\Application\Command\DeleteUserCommandMother;
use App\Tests\Unit\User\Domain\Model\UserMother;
use App\User\Application\Service\DeleteUserHandler;
use App\User\Domain\Model\User;
use App\User\Domain\Repository\Exceptions\UserAlreadyExistException;
use App\User\Domain\Repository\UserRepositoryInterface;
use PHPUnit\Framework\TestCase;

class DeleteUserHandlerTest extends TestCase
{
    private DeleteUserHandler $handler;
    private UserRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->createMock(UserRepositoryInterface::class);
        $this->handler = new DeleteUserHandler($this->repository);
    }

    public function testItShouldDeleteAnUser(): void
    {
        $user = UserMother::create();
        $command = DeleteUserCommandMother::create($user->id());

        $this->repository->method('search')->with($user->id())->willReturn($user);
        $this->repository->method('delete')->with($this->equalToWithDelta($user, 1));

        $this->assertInstanceOf(User::class, $user);

        $this->handler->__invoke($command);
    }
}
