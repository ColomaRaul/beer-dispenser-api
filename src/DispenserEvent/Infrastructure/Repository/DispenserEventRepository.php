<?php
declare(strict_types=1);

namespace App\DispenserEvent\Infrastructure\Repository;

use App\DispenserEvent\Domain\Model\DispenserEvent;
use App\DispenserEvent\Domain\Repository\DispenserEventRepositoryInterface;
use App\Shared\Domain\ValueObject\DateTimeValue;
use App\Shared\Domain\ValueObject\Uuid;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
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

    public function lastOpenedDispenserEventByDispenser(Uuid $dispenserId): ?DispenserEvent
    {
        try {
            $queryBuilder = $this->connection->createQueryBuilder();
            $queryBuilder
                ->select('*')
                ->from(self::TABLE_NAME, 'de')
                ->where('de.dispenser_id = :dispenserId')
                ->andWhere('de.closed_at is not null')
                ->orderBy('de.updated_at', 'DESC')
                ->setParameter('dispenserId', $dispenserId->value());

            $result = $queryBuilder->executeQuery()->fetchOne();

            if (false === $result) {
                return null;
            }

            return DispenserEvent::reconstitute(
                Uuid::from($result['id']),
                Uuid::from($result['dispenser_id']),
                DateTimeValue::createFromString($result['updated_at']),
                null !== $result['opened_at'] ? DateTimeValue::createFromString($result['opened_at']) : null,
                null !== $result['closed_at'] ? DateTimeValue::createFromString($result['closed_at']) : null,
                $result['total_spent'],
            );
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
            throw $e;
        }
    }
}
