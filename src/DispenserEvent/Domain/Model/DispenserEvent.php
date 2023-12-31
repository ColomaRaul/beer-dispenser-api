<?php
declare(strict_types=1);

namespace App\DispenserEvent\Domain\Model;

use App\DispenserEvent\Domain\Exception\DispenserAlreadyUpdateSameStatusDomainException;
use App\Shared\Domain\ValueObject\DateTimeValue;
use App\Shared\Domain\ValueObject\DispenserStatusType;
use App\Shared\Domain\ValueObject\Money;
use App\Shared\Domain\ValueObject\Uuid;

final class DispenserEvent
{
    private Uuid $id;
    private Uuid $dispenserId;
    private DateTimeValue $updatedAt;
    private ?DateTimeValue $openedAt = null;
    private ?DateTimeValue $closedAt = null;
    private ?Money $totalSpent = null;

    public static function create(
        Uuid $id,
        Uuid $dispenserId,
        DateTimeValue $updatedAt,
    ): self {
        $self = new self();
        $self->id = $id;
        $self->dispenserId = $dispenserId;
        $self->updatedAt = $updatedAt;
        $self->totalSpent = Money::from(0);

        return $self;
    }

    public static function reconstitute(
        Uuid $id,
        Uuid $dispenserId,
        DateTimeValue $updatedAt,
        ?DateTimeValue $openedAt,
        ?DateTimeValue $closedAt,
        Money $totalSpent,
    ): self {
        $self = new self();
        $self->id = $id;
        $self->dispenserId = $dispenserId;
        $self->updatedAt = $updatedAt;
        $self->openedAt = $openedAt;
        $self->closedAt = $closedAt;
        $self->totalSpent = $totalSpent;

        return $self;
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

        $totalSeconds = $this->openedAt->secondsBetweenDates($closedAt);

        $totalLitre = $flowVolume * $totalSeconds;
        $this->totalSpent = Money::from((int) round($totalLitre * $priceByLitre->value()));

        return $this;
    }

    public function updateStatus(DispenserStatusType $status, DateTimeValue $updatedAt): void
    {
        if ($this->isOpen() && $status == DispenserStatusType::OPEN) {
            throw new DispenserAlreadyUpdateSameStatusDomainException('Dispenser is already opened');
        }

        if ($this->isClose() && $status == DispenserStatusType::CLOSE) {
            throw new DispenserAlreadyUpdateSameStatusDomainException('Dispenser is already closed');
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
