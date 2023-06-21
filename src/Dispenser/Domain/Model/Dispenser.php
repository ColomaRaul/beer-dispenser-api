<?php
declare(strict_types=1);

namespace App\Dispenser\Domain\Model;

use App\Shared\Domain\ValueObject\DateTimeValue;
use App\Shared\Domain\ValueObject\Uuid;

final class Dispenser
{
    private function __construct(
        private Uuid           $id,
        private float          $flowVolume,
        private bool           $isOpen = false,
        private ?DateTimeValue $openTime = null,
        private ?DateTimeValue $closeTime = null,
    ) {
    }

    public static function create(Uuid $id, float $flowVolume): self
    {
        return new self($id, $flowVolume);
    }

    public static function reconstitute(
        Uuid           $id,
        float          $flowVolume,
        bool           $isOpen,
        ?DateTimeValue $openTime,
        ?DateTimeValue $closeTime
    ): self {
        return new self($id, $flowVolume, $isOpen, $openTime, $closeTime);
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function flowVolume(): float
    {
        return $this->flowVolume;
    }

    public function isOpen(): bool
    {
        return $this->isOpen;
    }

    public function openTime(): ?DateTimeValue
    {
        return $this->openTime;
    }

    public function closeTime(): ?DateTimeValue
    {
        return $this->closeTime;
    }
}
