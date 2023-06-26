<?php
declare(strict_types=1);

namespace App\DispenserEvent\Application\Service;

use App\Dispenser\Domain\Exception\DispenserNotFoundException;
use App\DispenserEvent\Application\Command\UpdateStatusDispenserEventCommand;
use App\DispenserEvent\Application\Exception\DispenserEventAlreadyUpdateSameStatusApplicationException;
use App\DispenserEvent\Application\Exception\DispenserNotFoundApplicationException;
use App\DispenserEvent\Domain\Exception\DispenserAlreadyUpdateSameStatusDomainException;
use App\DispenserEvent\Domain\Service\UpdateStatusDispenserEventService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class UpdateStatusDispenserEventHandler implements MessageHandlerInterface
{
    public function __construct(
        private UpdateStatusDispenserEventService $service,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * @throws DispenserEventAlreadyUpdateSameStatusApplicationException
     * @throws DispenserNotFoundApplicationException
     */
    public function __invoke(UpdateStatusDispenserEventCommand $command): void
    {
        try {
            $this->service->updateStatus($command->dispenserId(), $command->status(), $command->updatedAt());
        } catch (DispenserAlreadyUpdateSameStatusDomainException $e) {
            $this->logger->error($e->getMessage());
            throw new DispenserEventAlreadyUpdateSameStatusApplicationException($e->getMessage());
        } catch (DispenserNotFoundException $e) {
            $this->logger->error($e->getMessage());
            throw new DispenserNotFoundApplicationException();
        }
    }
}