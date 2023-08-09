<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Api\User;

use OpenApi\Attributes as OA;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Infrastructure\Symfony\ApiController;
use App\Shared\Infrastructure\Symfony\ApiExceptionsHttpStatusCodeMapping;
use App\User\Application\Command\CreateUserCommand;
use App\User\Domain\Repository\Exceptions\UserAlreadyExistException;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

class PostUserController extends ApiController
{
    use HandleTrait;

    public function __construct(MessageBusInterface $messageBus, ApiExceptionsHttpStatusCodeMapping $exceptionHandler)
    {
        $this->messageBus = $messageBus;
        parent::__construct($exceptionHandler);
    }

    #[OA\Post(
        description: "This call creates a new user.",
        summary: "Create a new user.",
        requestBody: new OA\RequestBody(
            description: "User information",
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'name',
                        description: 'User name',
                        type: 'string',
                        example: 'juan'
                    )
                ]
            )
        ),
        tags: ["User"],
        responses: [
            new OA\Response(
                response: 201,
                description: 'User created successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'id',
                            type: 'string',
                            example: 'c25ca0b3-c558-488f-b997-784ff7bb7e13'
                        ),
                        new OA\Property(
                            property: 'name',
                            type: 'string',
                            example: 'juan'
                        )
                    ],
                    type: 'object'
                )
            )
        ]
    )]
    public function __invoke(Request $request): JsonResponse
    {
        $userId = Uuid::random()->value();
        $name = (string)$request->get('name');

        if (!$name) {
            throw new InvalidArgumentException('Unexpected API error');
        }

        $this->handle(new CreateUserCommand($userId, $name));

        return $this->json([
            'id' => $userId,
            'name' => $name,
        ], Response::HTTP_CREATED, ['location' => sprintf('/users/%s', $userId)]);
    }

    /**
     * @return array<class-string, int>
     */
    protected function exceptions(): array
    {
        return [UserAlreadyExistException::class => Response::HTTP_CONFLICT];
    }
}
