<?php
declare(strict_types=1);

namespace App\Tests\Unit\DispenserEvent\Infrastructure\Repository;

use App\DispenserEvent\Domain\Model\DispenserEvent;
use App\DispenserEvent\Infrastructure\Repository\DispenserEventRepository;
use App\Shared\Domain\ValueObject\Uuid;
use App\Tests\Unit\DispenserEvent\Domain\Model\DispenserEventObjectMother;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class DispenserEventRepositoryTest extends TestCase
{
    private Connection $connection;
    private LoggerInterface $logger;
    private DispenserEventRepository $repository;

    protected function setUp(): void
    {
        $this->connection = $this->createMock(Connection::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->repository = new DispenserEventRepository($this->connection, $this->logger);
    }

    public function test_given_data_when_save_then_throw_error(): void
    {
        $dispenserEvent = DispenserEventObjectMother::create();

        $this->connection
            ->expects($this->once())
            ->method('prepare')
            ->willThrowException(new Exception());

        $this->logger
            ->expects($this->once())
            ->method('error');

        $this->expectException(Exception::class);

        $this->repository->save($dispenserEvent);
    }

    public function test_given_data_when_get_last_dispenser_then_throw_error(): void
    {
        $this->connection
            ->expects($this->once())
            ->method('createQueryBuilder')
            ->willThrowException(new Exception());

        $this->logger
            ->expects($this->once())
            ->method('error');

        $this->expectException(Exception::class);

        $this->repository->lastOpenedDispenserEventByDispenser(Uuid::generate());
    }
}