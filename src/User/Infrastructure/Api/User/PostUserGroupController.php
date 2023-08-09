<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Api\User;

use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Infrastructure\Symfony\ApiController;
use App\Shared\Infrastructure\Symfony\ApiExceptionsHttpStatusCodeMapping;
use App\User\Application\Command\CreateUserGroupCommand;
use App\User\Domain\Repository\Exceptions\UserAlreadyExistException;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

class PostUserGroupController extends ApiController
{
    use HandleTrait;

    public function __construct(MessageBusInterface $messageBus, ApiExceptionsHttpStatusCodeMapping $exceptionHandler)
    {
        $this->messageBus = $messageBus;
        parent::__construct($exceptionHandler);
    }

    #[OA\Post(
        description: "This call creates a new user group.",
        summary: "Create a new user group.",
        tags: ["User"],
        responses: [
            new OA\Response(
                response: 201,
                description: 'User created successfully'
            )
        ]
    )]
    public function __invoke(string $userId, string $groupId, Request $request): JsonResponse
    {
        $userGroupId = Uuid::random()->value();

        $this->handle(new CreateUserGroupCommand($userGroupId, $userId, $groupId));

        return $this->json([], Response::HTTP_CREATED, ['location' => sprintf('/users/%s/groups/%s', $userId, $groupId)]
        );
    }

    /**
     * @return array<class-string, int>
     */
    protected function exceptions(): array
    {
        return [UserAlreadyExistException::class => Response::HTTP_CONFLICT];
    }
}
