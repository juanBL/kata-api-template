<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Symfony;

use App\Shared\Domain\DomainError;
use App\Shared\Domain\Utils;
use RuntimeException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Throwable;

final class ApiExceptionListener
{
    public function __construct(private readonly ApiExceptionsHttpStatusCodeMapping $exceptionHandler)
    {
    }

    public function onException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $response = new JsonResponse();

        if (!$exception instanceof RuntimeException) {
            $response->setContent(
                json_encode(
                    [
                        'code' => $this->exceptionCodeFor($exception),
                        'error' => $exception->getMessage(),
                    ]
                )
            );
            $event->setResponse($response);
            $response->setStatusCode($this->exceptionHandler->statusCodeFor($exception::class));
        }

        if ($exception instanceof HandlerFailedException) {
            $response->setContent(
                json_encode(
                    [
                        //'code' => $this->exceptionCodeFor($exception->getPrevious()),
                        'error' => $exception->getPrevious()->getMessage(),
                    ]
                )
            );
            $event->setResponse($response);
            $response->setStatusCode($this->exceptionHandler->statusCodeFor($exception->getPrevious()::class));
            $event->setResponse($response);
        }
    }

    private function exceptionCodeFor(Throwable $error): string
    {
        $domainErrorClass = DomainError::class;

        return $error instanceof $domainErrorClass ? $error->errorCode() : Utils::toSnakeCase($error::class);
    }
}
