<?php
declare(strict_types=1);

namespace App\DispenserEvent\Domain\Exception;

final class DispenserAlreadyUpdateSameStatusException extends \Exception implements DispenserEventException
{

}
