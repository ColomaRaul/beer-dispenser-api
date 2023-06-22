<?php
declare(strict_types=1);

namespace App\DispenserEvent\Domain\Repository;

use App\DispenserEvent\Domain\Model\DispenserEvent;
use App\Shared\Domain\ValueObject\Uuid;

interface DispenserEventRepositoryInterface
{
    public function save(DispenserEvent $dispenserEvent): void;

    public function lastOpenedDispenserEventByDispenser(Uuid $dispenserId): ?DispenserEvent;
}
