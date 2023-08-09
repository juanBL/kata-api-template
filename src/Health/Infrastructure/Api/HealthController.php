<?php

namespace App\Health\Infrastructure\Api;

use App\Health\Application\Model\GetHealthResponse;
use App\Health\Application\Query\GetHealthQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

class HealthController extends AbstractController
{
    use HandleTrait;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function __invoke(): JsonResponse
    {
        /** @var GetHealthResponse $healthResponse */
        $healthResponse = $this->handle(new GetHealthQuery());

        return $this->json(
            $healthResponse,
            $healthResponse->getStatus() > 0 ? Response::HTTP_OK : Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }
}
