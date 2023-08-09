<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Symfony;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use function Lambdish\Phunctional\each;

abstract class ApiController extends AbstractController
{
    public function __construct(ApiExceptionsHttpStatusCodeMapping $exceptionHandler)
    {
        each(
            fn(int $httpCode, string $exceptionClass) => $exceptionHandler->register($exceptionClass, $httpCode),
            $this->exceptions()
        );
    }

    abstract protected function exceptions(): array;
}
