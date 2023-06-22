<?php
declare(strict_types=1);

namespace App\DispenserEvent\Application\Command;

use App\Shared\Application\Command\CommandInterface;
use App\Shared\Domain\ValueObject\DateTimeValue;
use App\Shared\Domain\ValueObject\DispenserStatusType;
use App\Shared\Domain\ValueObject\Uuid;

final class UpdateStatusDispenserEventCommand implements CommandInterface
{
    public function __construct(private Uuid $dispenserId, private DispenserStatusType $status, private DateTimeValue $updatedAt)
    {
    }

    public function dispenserId(): Uuid
    {
        return $this->dispenserId;
    }

    public function status(): DispenserStatusType
    {
        return $this->status;
    }

    public function updatedAt(): DateTimeValue
    {
        return $this->updatedAt;
    }
}
