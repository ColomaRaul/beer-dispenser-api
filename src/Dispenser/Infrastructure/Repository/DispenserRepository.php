<?php
declare(strict_types=1);

namespace App\Dispenser\Infrastructure\Repository;

use App\Dispenser\Domain\Model\Dispenser;
use App\Dispenser\Domain\Repository\DispenserRepositoryInterface;
use Doctrine\DBAL\Connection;
use Psr\Log\LoggerInterface;

final class DispenserRepository implements DispenserRepositoryInterface
{
    private Connection $connection;
    private LoggerInterface $logger;

    public function __construct(Connection $connection, LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->connection = $connection;
    }

    public function save(Dispenser $dispenser): void
    {
    }

    public function getById(string $id): ?Dispenser
    {
        return null;
    }
}
