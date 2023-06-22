<?php
declare(strict_types=1);

namespace App\DispenserEvent\Application\Service;

use App\DispenserEvent\Application\Command\UpdateStatusDispenserEventCommand;
use App\DispenserEvent\Domain\Service\UpdateStatusDispenserEventService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class UpdateStatusDispenserEventHandler implements MessageHandlerInterface
{
    public function __construct(private UpdateStatusDispenserEventService $service)
    {
    }

    public function __invoke(UpdateStatusDispenserEventCommand $command): void
    {
        return;
    }
}