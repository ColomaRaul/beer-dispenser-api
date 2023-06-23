<?php
declare(strict_types=1);

namespace App\DispenserEvent\Domain\Exception;

final class DispenserAlreadyUpdateSameStatusDomainException extends \Exception implements DispenserEventDomainException
{

}
