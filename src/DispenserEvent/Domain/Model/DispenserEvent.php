<?php
declare(strict_types=1);

namespace App\DispenserEvent\Domain\Model;

use App\Shared\Domain\ValueObject\DateTimeValue;
use App\Shared\Domain\ValueObject\Uuid;

final class DispenserEvent
{
    private function __construct(
        private int $id,
        private Uuid $dispenserId,
        private DateTimeValue $updatedAt,
        private ?DateTimeValue $openedAt,
        private ?DateTimeValue $closedAt,
        private float $totalSpent = 0.0,
    ) {

    }

    public function id(): int
    {
        return $this->id;
    }

    public function dispenserId(): Uuid
    {
        return $this->dispenserId;
    }

    public function updatedAt(): DateTimeValue
    {
        return $this->updatedAt;
    }

    public function openedAt(): ?DateTimeValue
    {
        return $this->openedAt;
    }

    public function closedAt(): ?DateTimeValue
    {
        return $this->closedAt;
    }

    public function totalSpent(): float
    {
        return $this->totalSpent;
    }

    public function openTap(): self
    {
        $this->openedAt = $this->updatedAt();

        return $this;
    }

    public function closeTap(): self
    {
        $this->closedAt = $this->updatedAt();

        return $this;
    }

    public function calculateSpent(): self
    {
        return $this;
    }
}
