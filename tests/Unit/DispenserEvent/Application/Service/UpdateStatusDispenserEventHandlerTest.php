<?php
declare(strict_types=1);

namespace App\Tests\Unit\DispenserEvent\Application\Service;

use App\Dispenser\Domain\Repository\DispenserRepositoryInterface;
use App\DispenserEvent\Application\Command\UpdateStatusDispenserEventCommand;
use App\DispenserEvent\Application\Service\UpdateStatusDispenserEventHandler;
use App\DispenserEvent\Domain\Repository\DispenserEventRepositoryInterface;
use App\DispenserEvent\Domain\Service\UpdateStatusDispenserEventService;
use App\Shared\Domain\ValueObject\DateTimeValue;
use App\Shared\Domain\ValueObject\DispenserStatusType;
use App\Shared\Domain\ValueObject\Uuid;
use App\Tests\Unit\DispenserEvent\Domain\Model\DispenserObjectMother;
use PHPUnit\Framework\TestCase;

final class UpdateStatusDispenserEventHandlerTest extends TestCase
{
    public function testUpdateStatusDispenserEventResponseOk(): void
    {
        $dispenserId = Uuid::generate();
        $mockDispenserRepository = $this->createMock(DispenserRepositoryInterface::class);
        $mockDispenserRepository->expects($this->once())->method('getById')->willReturn(
            DispenserObjectMother::create($dispenserId->value()),
        );
        $mockDispenserEventRepository = $this->createMock(DispenserEventRepositoryInterface::class);
        $updateStatusDispenserEventService = new UpdateStatusDispenserEventService($mockDispenserEventRepository, $mockDispenserRepository);

        $updateStatusDispenserEventHandler = new UpdateStatusDispenserEventHandler($updateStatusDispenserEventService);

        ($updateStatusDispenserEventHandler)(new UpdateStatusDispenserEventCommand(
            $dispenserId,
            DispenserStatusType::OPEN,
            DateTimeValue::createFromString('2023-06-22T02:00:00Z'),
        ));
    }
}
