<?php
declare(strict_types=1);

namespace App\Tests\Unit\DispenserEvent\Application\Service;

use App\Dispenser\Domain\Repository\DispenserRepositoryInterface;
use App\DispenserEvent\Application\Command\UpdateStatusDispenserEventCommand;
use App\DispenserEvent\Application\Exception\DispenserEventAlreadyUpdateSameStatusApplicationException;
use App\DispenserEvent\Application\Exception\DispenserNotFoundApplicationException;
use App\DispenserEvent\Application\Service\UpdateStatusDispenserEventHandler;
use App\DispenserEvent\Domain\Repository\DispenserEventRepositoryInterface;
use App\DispenserEvent\Domain\Service\UpdateStatusDispenserEventService;
use App\Shared\Domain\ValueObject\DateTimeValue;
use App\Shared\Domain\ValueObject\DispenserStatusType;
use App\Shared\Domain\ValueObject\Uuid;
use App\Tests\Unit\Dispenser\Domain\Model\DispenserObjectMother;
use App\Tests\Unit\DispenserEvent\Domain\Model\DispenserEventObjectMother;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

final class UpdateStatusDispenserEventHandlerTest extends TestCase
{
    public function testUpdateStatusDispenserEventResponseOk(): void
    {
        $dispenserId = Uuid::generate();
        $mockDispenserRepository = $this->createMock(DispenserRepositoryInterface::class);
        $logger = $this->createMock(LoggerInterface::class);
        $mockDispenserRepository->expects($this->once())->method('getById')->willReturn(
            DispenserObjectMother::create($dispenserId->value()),
        );
        $mockDispenserEventRepository = $this->createMock(DispenserEventRepositoryInterface::class);
        $updateStatusDispenserEventService = new UpdateStatusDispenserEventService($mockDispenserEventRepository, $mockDispenserRepository);

        $updateStatusDispenserEventHandler = new UpdateStatusDispenserEventHandler($updateStatusDispenserEventService, $logger);

        ($updateStatusDispenserEventHandler)(new UpdateStatusDispenserEventCommand(
            $dispenserId,
            DispenserStatusType::OPEN,
            DateTimeValue::createFromString('2023-06-22T02:00:00Z'),
        ));
    }

    public function test_given_data_when_update_status_then_throw_exception_not_found_dispenser(): void
    {
        $mockDispenserRepository = $this->createMock(DispenserRepositoryInterface::class);
        $logger = $this->createMock(LoggerInterface::class);
        $mockDispenserRepository->expects($this->once())->method('getById')->willReturn(null);
        $mockDispenserEventRepository = $this->createMock(DispenserEventRepositoryInterface::class);

        $updateStatusDispenserEventService = new UpdateStatusDispenserEventService($mockDispenserEventRepository, $mockDispenserRepository);
        $updateStatusDispenserEventHandler = new UpdateStatusDispenserEventHandler($updateStatusDispenserEventService, $logger);

        $this->expectException(DispenserNotFoundApplicationException::class);

        ($updateStatusDispenserEventHandler)(new UpdateStatusDispenserEventCommand(
            Uuid::generate(),
            DispenserStatusType::OPEN,
            DateTimeValue::createFromString('2023-06-22T02:00:00Z'),
        ));
    }

    public function test_given_data_when_update_status_then_throw_exception_already_opened_closed(): void
    {
        $dispenserId = Uuid::generate();
        $mockDispenserRepository = $this->createMock(DispenserRepositoryInterface::class);
        $logger = $this->createMock(LoggerInterface::class);
        $mockDispenserRepository->expects($this->once())->method('getById')->willReturn(DispenserObjectMother::create($dispenserId->value()));
        $mockDispenserEventRepository = $this->createMock(DispenserEventRepositoryInterface::class);
        $mockDispenserEventRepository->expects($this->once())->method('lastOpenedDispenserEventByDispenser')->willReturn(
            DispenserEventObjectMother::create(dispenserId: $dispenserId->value(), openedAt: '2023-06-22T02:00:00Z')
        );

        $updateStatusDispenserEventService = new UpdateStatusDispenserEventService($mockDispenserEventRepository, $mockDispenserRepository);
        $updateStatusDispenserEventHandler = new UpdateStatusDispenserEventHandler($updateStatusDispenserEventService, $logger);

        $this->expectException(DispenserEventAlreadyUpdateSameStatusApplicationException::class);

        ($updateStatusDispenserEventHandler)(new UpdateStatusDispenserEventCommand(
            $dispenserId,
            DispenserStatusType::OPEN,
            DateTimeValue::createFromString('2023-06-22T02:00:00Z'),
        ));
    }
}
