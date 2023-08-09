<?php

declare(strict_types=1);

namespace App\Health\Domain\Repository;

use App\Health\Domain\Model\Health;
use App\Health\Domain\Repository\Exceptions\DatabaseNotHealthyRepositoryException;

interface HealthRepositoryInterface
{
    /**
     * @throws DatabaseNotHealthyRepositoryException
     */
    public function health(): Health;
}
