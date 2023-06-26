<?php
declare(strict_types=1);

namespace App\Dispenser\Domain\Service;

use App\Dispenser\Domain\Exception\DispenserNotFoundException;
use App\Dispenser\Domain\Model\Dispenser;
use App\Dispenser\Domain\Repository\DispenserRepositoryInterface;
use App\DispenserEvent\Domain\Model\DispenserEvent;
use App\DispenserEvent\Domain\Repository\DispenserEventRepositoryInterface;
use App\Shared\Domain\ValueObject\Money;
use App\Shared\Domain\ValueObject\Uuid;

final class GetDispenserSpentService
{
    public function __construct(
        private DispenserRepositoryInterface $dispenserRepository,
        private DispenserEventRepositoryInterface $dispenserEventRepository
    ) {
    }

    public function execute(Uuid $dispenserId): array
    {
        $dispenser = $this->dispenserRepository->getById($dispenserId);

        if (null === $dispenser) {
            throw new DispenserNotFoundException();
        }

        $allUsages = $this->dispenserEventRepository->allByDispenser($dispenserId);
        $usagesMapped = [];

        /** @var DispenserEvent $dispenserEvent */
        foreach ($allUsages as $dispenserEvent) {
            $totalSpent = $this->totalSpent($dispenserEvent, $dispenser);

            $usagesMapped[] = [
                'opened_at' => $dispenserEvent->openedAt()?->toAtomString(),
                'closed_at' => $dispenserEvent->closedAt()?->toAtomString(),
                'flow_volume' => $dispenser->flowVolume(),
                'total_spent' => $totalSpent->toFloat(),
            ];
        }

        return [
            'amount' => $dispenser->amount(),
            'usages' => $usagesMapped,
        ];
    }

    private function totalSpent(DispenserEvent $dispenserEvent, Dispenser $dispenser): Money
    {
        $totalSpent = $dispenserEvent->totalSpent();

        if ($dispenserEvent->isClose()) {
            return $totalSpent;
        }

        $oldTotalSpent = $dispenserEvent->totalSpent();

        $dispenserEvent->calculateSpent($dispenser->flowVolume(), $dispenser->priceByLitre());
        $newTotalSpent = $dispenserEvent->totalSpent();

        $diff = $newTotalSpent->diff($oldTotalSpent);

        $dispenser->incrementAmount($diff);
        $this->dispenserRepository->save($dispenser);
        $this->dispenserEventRepository->save($dispenserEvent);

        return $newTotalSpent;
    }
}