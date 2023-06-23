<?php
declare(strict_types=1);

namespace App\DispenserEvent\Infrastructure\Repository;

use App\DispenserEvent\Domain\Model\DispenserEvent;
use App\DispenserEvent\Domain\Repository\DispenserEventRepositoryInterface;
use App\Shared\Domain\ValueObject\DateTimeValue;
use App\Shared\Domain\ValueObject\Money;
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
        try {
            $sql = sprintf('
                    INSERT INTO %s (id, dispenser_id, updated_at, opened_at, closed_at, total_spent)
                    VALUES (:id, :dispenser_id, :updated_at, :opened_at, :closed_at, :total_spent)
                    ON CONFLICT (id) DO UPDATE SET 
                        dispenser_id = :dispenser_id,
                        updated_at = :updated_at,
                        opened_at = :opened_at,
                        closed_at = :closed_at,
                        total_spent = :total_spent
                    ', self::TABLE_NAME);
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue('id', $dispenserEvent->id()->value());
            $stmt->bindValue('dispenser_id', $dispenserEvent->dispenserId()->value());
            $stmt->bindValue('updated_at', $dispenserEvent->updatedAt()->toAtomString());
            $stmt->bindValue('opened_at', $dispenserEvent->openedAt()?->toAtomString());
            $stmt->bindValue('closed_at', $dispenserEvent->closedAt()?->toAtomString());
            $stmt->bindValue('total_spent', $dispenserEvent->totalSpent()->value());
            $stmt->executeQuery();
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
            throw $e;
        }
    }

    public function lastOpenedDispenserEventByDispenser(Uuid $dispenserId): ?DispenserEvent
    {
        try {
            $queryBuilder = $this->connection->createQueryBuilder();
            $queryBuilder
                ->select('*')
                ->from(self::TABLE_NAME, 'de')
                ->where('de.dispenser_id = :dispenserId')
                ->andWhere('de.closed_at is null')
                ->orderBy('de.updated_at', 'DESC')
                ->setParameter('dispenserId', $dispenserId->value());

            $result = $queryBuilder->executeQuery()->fetchAssociative();

            if (false === $result) {
                return null;
            }

            return DispenserEvent::reconstitute(
                Uuid::from($result['id']),
                Uuid::from($result['dispenser_id']),
                DateTimeValue::createFromString($result['updated_at']),
                null !== $result['opened_at'] ? DateTimeValue::createFromString($result['opened_at']) : null,
                null !== $result['closed_at'] ? DateTimeValue::createFromString($result['closed_at']) : null,
                Money::from($result['total_spent']),
            );
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
            throw $e;
        }
    }
}
