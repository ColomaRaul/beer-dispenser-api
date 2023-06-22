<?php
declare(strict_types=1);

namespace App\Dispenser\Application\Service;

use App\Dispenser\Application\Command\CreateDispenserCommand;
use App\Dispenser\Domain\Repository\Exceptions\DispenserRepositoryException;
use App\Dispenser\Domain\Service\CreateDispenserService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreateDispenserHandler implements MessageHandlerInterface
{
    public function __construct(private CreateDispenserService $service)
    {
    }

    public function __invoke(CreateDispenserCommand $command): void
    {
        $this->service->create($command->id(), $command->flowVolume());
    }
}
