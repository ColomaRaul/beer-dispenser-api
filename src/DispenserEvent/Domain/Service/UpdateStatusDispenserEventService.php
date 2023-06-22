<?php
declare(strict_types=1);

namespace App\DispenserEvent\Domain\Service;

use App\Dispenser\Domain\Exception\DispenserNotFoundException;
use App\Dispenser\Domain\Repository\DispenserRepositoryInterface;
use App\DispenserEvent\Domain\Model\DispenserEvent;
use App\DispenserEvent\Domain\Repository\DispenserEventRepositoryInterface;
use App\Shared\Domain\ValueObject\DateTimeValue;
use App\Shared\Domain\ValueObject\DispenserStatusType;
use App\Shared\Domain\ValueObject\Uuid;

final class UpdateStatusDispenserEventService
{
    public function __construct(
        private DispenserEventRepositoryInterface $dispenserEventRepository,
        private DispenserRepositoryInterface $dispenserRepository,
    ) {
    }

    /**
     * @throws DispenserNotFoundException
     */
    public function updateStatus(Uuid $dispenserId, DispenserStatusType $status, DateTimeValue $updatedAt): void
    {
        $dispenser = $this->dispenserRepository->getById($dispenserId);

        if (null === $dispenser) {
            throw new DispenserNotFoundException();
        }

        $dispenserEvent = $this->dispenserEventRepository->lastOpenedDispenserEventByDispenser($dispenserId);

        if (null === $dispenserEvent && $status == DispenserStatusType::OPEN) {
            $dispenserEvent = DispenserEvent::create(Uuid::generate(), $dispenserId, $updatedAt);
        }

        $dispenserEvent->updateStatus($status, $updatedAt);

        // TODO launch event if is necessary
    }
}
