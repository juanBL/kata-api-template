<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Api\Group;

use OpenApi\Attributes as OA;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Infrastructure\Symfony\ApiController;
use App\Shared\Infrastructure\Symfony\ApiExceptionsHttpStatusCodeMapping;
use App\User\Application\Command\CreateGroupCommand;
use App\User\Domain\Repository\Exceptions\GroupAlreadyExistException;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

class PostGroupController extends ApiController
{
    use HandleTrait;

    public function __construct(MessageBusInterface $messageBus, ApiExceptionsHttpStatusCodeMapping $exceptionHandler)
    {
        $this->messageBus = $messageBus;
        parent::__construct($exceptionHandler);
    }

    #[OA\Post(
        description: "This call creates a new group.",
        summary: "Create a new group.",
        requestBody: new OA\RequestBody(
            description: "Group information",
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'name',
                        description: 'Group name',
                        type: 'string',
                        example: 'group1'
                    )
                ]
            )
        ),
        tags: ["Group"],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Group created successfully',
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
                            example: 'group1'
                        )
                    ],
                    type: 'object'
                )
            )
        ]
    )]
    public function __invoke(Request $request): JsonResponse
    {
        $groupId = Uuid::random()->value();
        $name = (string)$request->get('name');

        if (!$name) {
            throw new InvalidArgumentException('Unexpected API error');
        }

        $this->handle(new CreateGroupCommand($groupId, $name));

        return $this->json([
            'id' => $groupId,
            'name' => $name,
        ], Response::HTTP_CREATED, ['location' => sprintf('/groups/%s', $groupId)]);
    }

    /**
     * @return array<class-string, int>
     */
    protected function exceptions(): array
    {
        return [GroupAlreadyExistException::class => Response::HTTP_CONFLICT];
    }
}
