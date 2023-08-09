<?php

declare(strict_types=1);

namespace App\User\Domain\Repository\Exceptions;

use Exception;

class DatabaseGroupRepositoryException extends Exception implements GroupRepositoryException
{
}
