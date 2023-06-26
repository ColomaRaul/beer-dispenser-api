<?php
declare(strict_types=1);

namespace App\Dispenser\Domain\Service;

use App\Dispenser\Domain\Model\Dispenser;
use App\Dispenser\Domain\Repository\DispenserRepositoryInterface;
use App\Shared\Domain\ValueObject\Uuid;

final class CreateDispenserService
{
    public function __construct(private DispenserRepositoryInterface $dispenserRepository)
    {
    }

    public function create(Uuid $id, float $flowVolume): void
    {
        $dispenser = Dispenser::create($id, $flowVolume);

        $this->dispenserRepository->save($dispenser);
    }
}
