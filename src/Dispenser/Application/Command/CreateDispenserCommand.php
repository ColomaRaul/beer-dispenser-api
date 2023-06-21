<?php

namespace App\Dispenser\Application\Command;

use App\Shared\Application\Command\CommandInterface;
use App\Shared\Domain\ValueObject\Uuid;

class CreateDispenserCommand implements CommandInterface
{
    public function __construct(private Uuid $id, private float $flowVolume)
    {
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function flowVolume(): float
    {
        return $this->flowVolume;
    }
}
