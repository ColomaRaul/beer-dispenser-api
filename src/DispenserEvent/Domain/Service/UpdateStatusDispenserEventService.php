<?php
declare(strict_types=1);

namespace App\DispenserEvent\Domain\Service;

use App\Dispenser\Domain\Repository\DispenserRepositoryInterface;
use App\DispenserEvent\Domain\Repository\DispenserEventRepositoryInterface;

final class UpdateStatusDispenserEventService
{
    public function __construct(
        private DispenserEventRepositoryInterface $dispenserEventRepository,
        private DispenserRepositoryInterface $dispenserRepository,
    )
    {
    }

}
