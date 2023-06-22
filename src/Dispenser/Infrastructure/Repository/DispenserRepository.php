<?php
declare(strict_types=1);

namespace App\Dispenser\Infrastructure\Repository;

use App\Dispenser\Domain\Model\Dispenser;
use App\Dispenser\Domain\Repository\DispenserRepositoryInterface;
use App\Dispenser\Domain\Repository\Exceptions\DispenserNotInsertedRepositoryException;
use App\Shared\Domain\ValueObject\Uuid;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Psr\Log\LoggerInterface;

final class DispenserRepository implements DispenserRepositoryInterface
{
    private const TABLE_NAME = 'dispenser';

    private Connection $connection;
    private LoggerInterface $logger;

    public function __construct(Connection $connection, LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->connection = $connection;
    }

    /**
     * @throws DispenserNotInsertedRepositoryException
     */
    public function save(Dispenser $dispenser): void
    {
        try {
            $queryBuilder = $this->connection->createQueryBuilder();
            $queryBuilder
                ->insert(self::TABLE_NAME)
                ->values([
                    'id' => ':id',
                    'flow_volume' => ':flow_volume',
                    'price_by_litre' => ':price_by_litre',
                    'amount' => ':amount',
                ])
                ->setParameter('id', $dispenser->id()->value())
                ->setParameter('flow_volume', $dispenser->flowVolume())
                ->setParameter('price_by_litre', $dispenser->priceByLitre())
                ->setParameter('amount', $dispenser->amount());
            $queryBuilder->executeQuery();
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
            throw new DispenserNotInsertedRepositoryException($e->getMessage());
        }
    }

    public function getById(Uuid $id): ?Dispenser
    {
        try {
            $queryBuilder = $this->connection->createQueryBuilder();
            $queryBuilder->select('*')
                ->from(self::TABLE_NAME, 'dis')
                ->where('dis.id = :id')
                ->setParameter('id', $id->value());

            $result = $queryBuilder->executeQuery()->fetchOne();

            if (false === $result) {
                return null;
            }

            return Dispenser::reconstitute(
                Uuid::from($result['id']),
                $result['flow_volume'],
                $result['price_by_litre'],
                $result['amount'],
            );
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
            throw $e;
        }
    }
}
