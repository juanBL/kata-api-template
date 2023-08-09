<?php

declare(strict_types=1);

namespace App\User\Application\Service;

use App\User\Application\Command\CreateUserCommand;
use App\User\Domain\Model\User;
use App\User\Domain\Model\User\UserId;
use App\User\Domain\Model\User\UserName;
use App\User\Domain\Repository\Exceptions\DatabaseUserRepositoryException;
use App\User\Domain\Repository\Exceptions\UserAlreadyExistException;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\Service\UserByNameSearcher;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class CreateUserHandler implements MessageHandlerInterface
{
    private UserByNameSearcher $userByNameSearcher;

    public function __construct(private readonly UserRepositoryInterface $repository)
    {
        $this->userByNameSearcher = new UserByNameSearcher($repository);
    }

    /**
     * @throws DatabaseUserRepositoryException
     */
    public function __invoke(CreateUserCommand $command): void
    {
        $user = $this->userByNameSearcher->__invoke(new UserName($command->name()));

        if ($user instanceof User) {
            throw new UserAlreadyExistException(new UserName($command->name()));
        }

        $user = User::create(new UserId($command->id()), new UserName($command->name()));
        $this->repository->save($user);
    }
}
