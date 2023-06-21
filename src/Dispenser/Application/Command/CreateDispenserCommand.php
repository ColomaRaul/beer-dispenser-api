<?php

namespace App\Dispenser\Application\Command;

use App\Shared\Domain\ValueObject\Uuid;

class CreateDispenserCommand
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
