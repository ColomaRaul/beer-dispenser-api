<?php
declare(strict_types=1);

namespace App\Dispenser\Domain\Repository;

use App\Dispenser\Domain\Model\Dispenser;
use App\Shared\Domain\ValueObject\Uuid;

interface DispenserRepositoryInterface
{
    public function save(Dispenser $dispenser): void;

    public function getById(Uuid $id): ?Dispenser;
}
