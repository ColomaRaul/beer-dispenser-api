<?php
declare(strict_types=1);

namespace App\Dispenser\Domain\Model;

use App\Dispenser\Domain\Model\ValueObject\DispenserStatusType;
use App\Shared\Domain\ValueObject\DateTimeValue;
use App\Shared\Domain\ValueObject\Uuid;

final class Dispenser
{
    private const DEFAULT_PRICE_BY_LITRE = 12.25;

    private function __construct(
        private Uuid $id,
        private float $flowVolume,
        private DispenserStatusType $status = DispenserStatusType::CLOSE,
        private float $priceByLitre = self::DEFAULT_PRICE_BY_LITRE,
        private ?DateTimeValue $openTime = null,
        private ?DateTimeValue $closeTime = null,
    ) {
    }

    public static function create(Uuid $id, float $flowVolume): self
    {
        return new self($id, $flowVolume);
    }

    public static function reconstitute(
        Uuid $id,
        float $flowVolume,
        DispenserStatusType $status,
        float $priceByLitre,
        ?DateTimeValue $openTime,
        ?DateTimeValue $closeTime
    ): self {
        return new self($id, $flowVolume, $status, $priceByLitre, $openTime, $closeTime);
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function flowVolume(): float
    {
        return $this->flowVolume;
    }

    public function status(): DispenserStatusType
    {
        return $this->status;
    }

    public function priceByLitre(): float
    {
        return $this->priceByLitre;
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
