<?php
declare(strict_types=1);

namespace App\Tests\Unit\DispenserEvent\Domain\Model;

use App\DispenserEvent\Domain\Model\DispenserEvent;
use App\Shared\Domain\ValueObject\DateTimeValue;
use App\Shared\Domain\ValueObject\Money;
use App\Shared\Domain\ValueObject\Uuid;

final class DispenserEventObjectMother
{
    public static function create(
        ?string $id = null,
        ?string $dispenserId = null,
        ?string $updatedAt = null,
        string $openedAt = null,
        string $closedAt = null,
        ?int $totalSpent = null,
    ): DispenserEvent {
        return DispenserEvent::reconstitute(
            null === $id ? Uuid::generate() : Uuid::from($id),
            null === $dispenserId ? Uuid::generate() : Uuid::from($dispenserId),
            null === $updatedAt ? DateTimeValue::create() : DateTimeValue::createFromString($updatedAt),
            null === $openedAt ? null : DateTimeValue::createFromString($openedAt),
            null === $closedAt ? null : DateTimeValue::createFromString($closedAt),
            null === $totalSpent ? Money::from(1234) : Money::from($totalSpent),
        );
    }
}