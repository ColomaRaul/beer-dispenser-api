<?php
declare(strict_types=1);

namespace App\DispenserEvent\Application\Exception;

use Throwable;

final class DispenserNotFoundApplicationException extends \Exception implements DispenserEventApplicationException
{
    public function __construct(string $message = 'Requested dispenser does not exist', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
