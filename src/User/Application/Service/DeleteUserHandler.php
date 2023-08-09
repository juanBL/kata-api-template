<?php

declare(strict_types=1);

namespace App\User\Application\Service;

use App\User\Application\Command\DeleteUserCommand;
use App\User\Domain\Model\User\UserId;
use App\User\Domain\Repository\Exceptions\DatabaseUserRepositoryException;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\Service\UserFinder;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class DeleteUserHandler implements MessageHandlerInterface
{
    private UserFinder $userFinder;

    public function __construct(private readonly UserRepositoryInterface $repository)
    {
        $this->userFinder = new UserFinder($repository);
    }

    /**
     * @throws DatabaseUserRepositoryException
     */
    public function __invoke(DeleteUserCommand $command): void
    {
        $user = $this->userFinder->__invoke(new UserId($command->id()));

        $this->repository->delete($user);
    }
}
