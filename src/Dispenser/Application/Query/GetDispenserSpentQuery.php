<?php
declare(strict_types=1);

namespace App\Dispenser\Application\Query;

use App\Shared\Application\Query\QueryInterface;
use App\Shared\Domain\ValueObject\Uuid;

final class GetDispenserSpentQuery implements QueryInterface
{
    public function __construct(private Uuid $id)
    {
    }

    public function id(): Uuid
    {
        return $this->id;
    }
}
