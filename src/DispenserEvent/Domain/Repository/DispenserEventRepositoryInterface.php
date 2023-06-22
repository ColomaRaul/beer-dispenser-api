<?php
declare(strict_types=1);

namespace App\DispenserEvent\Domain\Repository;

use App\DispenserEvent\Domain\Model\DispenserEvent;

interface DispenserEventRepositoryInterface
{
    public function save(DispenserEvent $dispenserEvent): void;
}
