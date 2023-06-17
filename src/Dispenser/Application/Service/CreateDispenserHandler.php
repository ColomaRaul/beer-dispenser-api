<?php
declare(strict_types=1);

namespace App\Dispenser\Application\Service;

use App\Dispenser\Application\Command\CreateDispenserCommand;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreateDispenserHandler implements MessageHandlerInterface
{
    public function __invoke(CreateDispenserCommand $command): void
    {
        return;
    }
}
