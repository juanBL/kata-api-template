<?php

declare(strict_types=1);

namespace App\Health\Application\Service;

use App\Health\Application\Model\GetHealthResponse;
use App\Health\Application\Query\GetHealthQuery;
use App\Health\Domain\Repository\Exceptions\HealthRepositoryException;
use App\Health\Domain\Repository\HealthRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class GetHealthHandler implements MessageHandlerInterface
{
    private HealthRepositoryInterface $healthRepository;

    public function __construct(HealthRepositoryInterface $healthRepository)
    {
        $this->healthRepository = $healthRepository;
    }

    public function __invoke(GetHealthQuery $getHealthQuery): GetHealthResponse
    {
        try {
            $this->healthRepository->health();
            return GetHealthResponse::ofSuccess('OK');
        } catch (HealthRepositoryException $e) {
            return GetHealthResponse::ofError($e->getMessage());
        }
    }
}
