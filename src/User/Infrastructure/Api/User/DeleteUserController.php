<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Api\User;

use OpenApi\Attributes as OA;
use App\Shared\Infrastructure\Symfony\ApiController;
use App\Shared\Infrastructure\Symfony\ApiExceptionsHttpStatusCodeMapping;
use App\User\Application\Command\DeleteUserCommand;
use App\User\Domain\Repository\Exceptions\DatabaseUserRepositoryException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

class DeleteUserController extends ApiController
{
    use HandleTrait;

    public function __construct(MessageBusInterface $messageBus, ApiExceptionsHttpStatusCodeMapping $exceptionHandler)
    {
        $this->messageBus = $messageBus;
        parent::__construct($exceptionHandler);
    }

    #[OA\Delete(
        description: "This call deletes a user.",
        summary: "Delete a user.",
        tags: ["User"],
        responses: [
            new OA\Response(
                response: 200,
                description: 'User deleted successfully',
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
    public function __invoke(string $id, Request $request): Response
    {
        $this->handle(new DeleteUserCommand($id));

        return $this->json(['code' => 'Success', 'message' => 'Operation Successful'], Response::HTTP_OK);
    }

    /**
     * @return array<class-string, int>
     */
    protected function exceptions(): array
    {
        return [DatabaseUserRepositoryException::class => Response::HTTP_INTERNAL_SERVER_ERROR];
    }
}
