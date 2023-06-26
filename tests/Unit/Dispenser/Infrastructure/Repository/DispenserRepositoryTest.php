<?php
declare(strict_types=1);

namespace App\Tests\Unit\Dispenser\Infrastructure\Repository;

use App\Dispenser\Domain\Model\Dispenser;
use App\Dispenser\Domain\Repository\Exceptions\DispenserNotInsertedRepositoryException;
use App\Dispenser\Infrastructure\Repository\DispenserRepository;
use App\Shared\Domain\ValueObject\Money;
use App\Shared\Domain\ValueObject\Uuid;
use App\Tests\Unit\Dispenser\Domain\Model\DispenserObjectMother;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Query\QueryBuilder;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class DispenserRepositoryTest extends TestCase
{
    private Connection $connection;
    private LoggerInterface $logger;
    private DispenserRepository $repository;

    protected function setUp(): void
    {
        $this->connection = $this->createMock(Connection::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->repository = new DispenserRepository($this->connection, $this->logger);
    }

    public function test_given_data_when_save_then_throw_error(): void
    {
        $dispenser = DispenserObjectMother::create();

        $this->connection
            ->expects($this->once())
            ->method('prepare')
            ->willThrowException(new Exception());

        $this->logger
            ->expects($this->once())
            ->method('error');

        $this->expectException(DispenserNotInsertedRepositoryException::class);

        $this->repository->save($dispenser);
    }

    public function test_given_data_when_get_by_id_then_throw_exception(): void
    {
        $this->connection
            ->expects($this->once())
            ->method('createQueryBuilder')
            ->willThrowException(new Exception());

        $this->logger
            ->expects($this->once())
            ->method('error');

        $this->expectException(Exception::class);

        $this->repository->getById(Uuid::generate());
    }
}