<?php
declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

enum DispenserStatusType: string
{
    case OPEN = 'open';
    case CLOSE = 'close';
}
