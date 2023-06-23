<?php
declare(strict_types=1);

namespace App\Tests\Unit\Dispenser\Domain\Model;

use App\Dispenser\Domain\Model\Dispenser;
use App\Shared\Domain\ValueObject\Money;
use App\Shared\Domain\ValueObject\Uuid;

final class DispenserObjectMother
{
    public static function create(
        ?string $id = null,
        ?float $flowVolume = null,
        ?Money $priceByLitre = null,
        ?Money $amount = null,
    ): Dispenser {
        return Dispenser::reconstitute(
            null === $id ? Uuid::generate() : Uuid::from($id),
            $flowVolume ?? 0.064,
            $priceByLitre ?? Money::from(1225),
            $amount ?? Money::from(0),
        );
    }
}