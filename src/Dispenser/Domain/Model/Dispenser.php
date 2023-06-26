<?php
declare(strict_types=1);

namespace App\Dispenser\Domain\Model;

use App\Shared\Domain\ValueObject\DispenserStatusType;
use App\Shared\Domain\ValueObject\Money;
use App\Shared\Domain\ValueObject\Uuid;

final class Dispenser
{
    private const DEFAULT_PRICE_BY_LITRE = 1225;

    private function __construct(
        private Uuid $id,
        private float $flowVolume,
        private ?Money $priceByLitre = null,
        private ?Money $amount = null,
    ) {
        $this->priceByLitre = $priceByLitre ?? Money::from(self::DEFAULT_PRICE_BY_LITRE);
        $this->amount = $amount ?? Money::from(0);
    }

    public static function create(Uuid $id, float $flowVolume): self
    {
        return new self($id, $flowVolume);
    }

    public static function reconstitute(
        Uuid $id,
        float $flowVolume,
        Money $priceByLitre,
        Money $amount,
    ): self {
        return new self($id, $flowVolume, $priceByLitre, $amount);
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function flowVolume(): float
    {
        return $this->flowVolume;
    }

    public function priceByLitre(): Money
    {
        return $this->priceByLitre;
    }

    public function amount(): Money
    {
        return $this->amount;
    }

    public function incrementAmount(Money $addAmount): self
    {
        $this->amount = $this->amount->add($addAmount);

        return $this;
    }
}
