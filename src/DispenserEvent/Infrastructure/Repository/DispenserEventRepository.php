<?php
declare(strict_types=1);

namespace App\DispenserEvent\Infrastructure\Repository;

use App\DispenserEvent\Domain\Model\DispenserEvent;
use App\DispenserEvent\Domain\Repository\DispenserEventRepositoryInterface;
use Doctrine\DBAL\Connection;
use Psr\Log\LoggerInterface;

final class DispenserEventRepository implements DispenserEventRepositoryInterface
{
    private const TABLE_NAME = 'dispenser_event';

    private Connection $connection;
    private LoggerInterface $logger;

    public function __construct(Connection $connection, LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->connection = $connection;
    }

    public function save(DispenserEvent $dispenserEvent): void
    {
        return;
    }
}
