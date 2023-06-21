<?php
declare(strict_types=1);

namespace App\Dispenser\Domain\Repository;

use App\Dispenser\Domain\Model\Dispenser;

interface DispenserRepositoryInterface
{
    public function save(Dispenser $dispenser): void;

    public function getById(string $id): ?Dispenser;
}
