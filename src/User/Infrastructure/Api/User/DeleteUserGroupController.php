<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Api\User;

use App\Shared\Infrastructure\Symfony\ApiController;
use App\Shared\Infrastructure\Symfony\ApiExceptionsHttpStatusCodeMapping;
use App\User\Application\Command\DeleteUserGroupCommand;
use App\User\Domain\Repository\Exceptions\UserAlreadyExistException;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

class DeleteUserGroupController extends ApiController
{
    use HandleTrait;

    public function __construct(MessageBusInterface $messageBus, ApiExceptionsHttpStatusCodeMapping $exceptionHandler)
    {
        $this->messageBus = $messageBus;
        parent::__construct($exceptionHandler);
    }

    #[OA\Delete(
        description: "This call deletes a user group.",
        summary: "Delete a user group.",
        tags: ["User"],
        responses: [
            new OA\Response(
                response: 200,
                description: 'User group deleted successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'code',
                            type: 'string',
                            example: 'Success'
                        ),
                        new OA\Property(
                            property: 'message',
                            type: 'string',
                            example: 'Operation Successful'
                        )
                    ],
                    type: 'object'
                )
            )
        ]
    )]
    public function __invoke(string $userId, string $groupId, Request $request): JsonResponse
    {
        $this->handle(new DeleteUserGroupCommand($userId, $groupId));

        return $this->json(['code' => 'Success', 'message' => 'Operation Successful'], Response::HTTP_OK);
    }

    /**
     * @return array<class-string, int>
     */
    protected function exceptions(): array
    {
        return [UserAlreadyExistException::class => Response::HTTP_CONFLICT];
    }
}
