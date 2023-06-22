<?php
declare(strict_types=1);

namespace App\Tests\Unit\DispenserEvent\Domain\Model;

use App\Dispenser\Domain\Model\Dispenser;
use App\Shared\Domain\ValueObject\Uuid;

final class DispenserObjectMother
{
    public static function create(
        ?string $id = null,
        ?float $flowVolume = null,
        ?float $priceByLitre = null,
        ?float $amount = null,
    ): Dispenser {
        return Dispenser::reconstitute(
            null === $id ? Uuid::generate() : Uuid::from($id),
            $flowVolume ?? 0.064,
            $priceByLitre ?? 12.25,
            $amount ?? 0,
        );
    }
}