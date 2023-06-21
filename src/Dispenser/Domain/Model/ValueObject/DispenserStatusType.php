<?php
declare(strict_types=1);

namespace App\Dispenser\Domain\Model\ValueObject;

enum DispenserStatusType: string
{
    case OPEN = 'open';
    case CLOSE = 'close';
}
