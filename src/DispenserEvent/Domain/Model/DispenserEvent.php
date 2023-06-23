<?php
declare(strict_types=1);

namespace App\DispenserEvent\Domain\Model;

use App\DispenserEvent\Domain\Exception\DispenserAlreadyUpdateSameStatusException;
use App\Shared\Domain\ValueObject\DateTimeValue;
use App\Shared\Domain\ValueObject\DispenserStatusType;
use App\Shared\Domain\ValueObject\Money;
use App\Shared\Domain\ValueObject\Uuid;

final class DispenserEvent
{
    private function __construct(
        private Uuid $id,
        private Uuid $dispenserId,
        private DateTimeValue $updatedAt,
        private ?DateTimeValue $openedAt = null,
        private ?DateTimeValue $closedAt = null,
        private ?Money $totalSpent = null,
    ) {
        $this->totalSpent = $totalSpent ?? Money::from(0);
    }

    public static function create(
        Uuid $id,
        Uuid $dispenserId,
        DateTimeValue $updatedAt,
    ): self {
        return new self($id, $dispenserId, $updatedAt);
    }

    public static function reconstitute(
        Uuid $id,
        Uuid $dispenserId,
        DateTimeValue $updatedAt,
        ?DateTimeValue $openedAt,
        ?DateTimeValue $closedAt,
        Money $totalSpent,
    ): self {
        return new self($id, $dispenserId, $updatedAt, $openedAt, $closedAt, $totalSpent);
    }

    public function id(): Uuid
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

    public function totalSpent(): Money
    {
        return $this->totalSpent;
    }

    public function calculateSpent(float $flowVolume, Money $priceByLitre): self
    {
        $closedAt = $this->closedAt();
        if (null === $closedAt) {
            $closedAt = DateTimeValue::create();
        }

        $totalTimeOpened = $this->openedAt()->value()->diff($closedAt->value());
        $totalSeconds = $totalTimeOpened->format('%s');

        $totalLitre = $flowVolume * $totalSeconds;
        $this->totalSpent = Money::from((int) round($totalLitre * $priceByLitre->value()));

        return $this;
    }

    public function updateStatus(DispenserStatusType $status, DateTimeValue $updatedAt): void
    {
        if ($this->isOpen() && $status == DispenserStatusType::OPEN) {
            throw new DispenserAlreadyUpdateSameStatusException('Dispenser is already opened');
        }

        if ($this->isClose() && $status == DispenserStatusType::CLOSE) {
            throw new DispenserAlreadyUpdateSameStatusException('Dispenser is already closed');
        }

        if ($status == DispenserStatusType::OPEN) {
            $this->openedAt = $updatedAt;
            $this->updatedAt = $updatedAt;

            return;
        }

        if ($status == DispenserStatusType::CLOSE) {
            $this->closedAt = $updatedAt;
            $this->updatedAt = $updatedAt;
        }
    }

    public function isOpen(): bool
    {
        return null !== $this->openedAt() && null === $this->closedAt();
    }

    public function isClose(): bool
    {
        return (null === $this->openedAt() && null === $this->closedAt()) || (null !== $this->openedAt() && null !== $this->closedAt());
    }
}
