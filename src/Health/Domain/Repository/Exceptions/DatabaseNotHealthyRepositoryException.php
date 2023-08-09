<?php

declare(strict_types=1);

namespace App\Health\Domain\Repository\Exceptions;

use Exception;

class DatabaseNotHealthyRepositoryException extends Exception implements HealthRepositoryException
{
}
