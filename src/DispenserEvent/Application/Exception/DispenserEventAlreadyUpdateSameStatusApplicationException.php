<?php
declare(strict_types=1);

namespace App\DispenserEvent\Application\Exception;

use Throwable;

final class DispenserEventAlreadyUpdateSameStatusApplicationException extends \Exception implements DispenserEventApplicationException
{
    public function __construct(string $message = 'Dispenser is already opened/closed', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
