<?php
declare(strict_types=1);

namespace App\Dispenser\Infrastructure\Repository;

use App\Dispenser\Domain\Model\Dispenser;
use App\Dispenser\Domain\Repository\DispenserRepositoryInterface;
use App\Dispenser\Domain\Repository\Exceptions\DispenserNotInsertedRepositoryException;
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
                    'status' => ':status',
                    'price_by_litre' => ':price_by_litre',
                    'amount' => ':amount',
                ])
                ->setParameter('id', $dispenser->id()->value())
                ->setParameter('flow_volume', $dispenser->flowVolume())
                ->setParameter('status', $dispenser->status()->value)
                ->setParameter('price_by_litre', $dispenser->priceByLitre())
                ->setParameter('amount', $dispenser->amount());
            $queryBuilder->executeQuery();
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
            throw new DispenserNotInsertedRepositoryException($e->getMessage());
        }
    }

    public function getById(string $id): ?Dispenser
    {
        return null;
    }
}
