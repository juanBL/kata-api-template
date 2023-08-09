<?php

declare(strict_types=1);

namespace App\Tests\Unit\Health\Application\Service;

use App\Health\Application\Query\GetHealthQuery;
use App\Health\Application\Service\GetHealthHandler;
use App\Health\Domain\Repository\Exceptions\DatabaseNotHealthyRepositoryException;
use App\Health\Domain\Repository\HealthRepositoryInterface;
use PHPUnit\Framework\TestCase;

class GetHealthHandlerTest extends TestCase
{
    private GetHealthHandler $getHealthHandler;

    private HealthRepositoryInterface $healthRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->healthRepository = $this->createMock(HealthRepositoryInterface::class);
        $this->getHealthHandler = new GetHealthHandler($this->healthRepository);
    }

    public function testReturnGetHealthResponseOk(): void
    {
        $handlerResponse = ($this->getHealthHandler)(new GetHealthQuery());

        $this->assertEquals(1, $handlerResponse->getStatus());
    }

    public function testReturnGetHealthResponseFailIfRepositoryFails(): void
    {
        $this->healthRepository
            ->method('health')
            ->will($this->throwException(new DatabaseNotHealthyRepositoryException()));
        $handlerResponse = ($this->getHealthHandler)(new GetHealthQuery());

        $this->assertEquals(-1, $handlerResponse->getStatus());
    }
}
