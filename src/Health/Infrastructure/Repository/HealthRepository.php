<?php

declare(strict_types=1);

namespace App\Health\Infrastructure\Repository;

use App\Health\Domain\Model\Health;
use App\Health\Domain\Repository\Exceptions\DatabaseNotHealthyRepositoryException;
use App\Health\Domain\Repository\HealthRepositoryInterface;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Psr\Log\LoggerInterface;

class HealthRepository implements HealthRepositoryInterface
{
    private Connection $connection;
    private LoggerInterface $logger;

    public function __construct(Connection $connection, LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->connection = $connection;
    }

    /**
     * @throws DatabaseNotHealthyRepositoryException
     */
    public function health(): Health
    {
        try {
            $stmt = $this->connection->prepare("SELECT 1+1");
            return new Health($stmt->executeQuery()->fetchOne());
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
            throw new DatabaseNotHealthyRepositoryException($e->getMessage());
        }
    }
}
