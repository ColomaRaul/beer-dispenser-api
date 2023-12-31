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
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

final class UpdateStatusDispenserEventHandlerTest extends TestCase
{
    private UpdateStatusDispenserEventHandler $handler;
    private MockObject $mockDispenserRepository;
    private MockObject $mockDispenserEventRepository;

    protected function setUp(): void
    {
        $this->mockDispenserRepository = $this->createMock(DispenserRepositoryInterface::class);
        $this->mockDispenserEventRepository = $this->createMock(DispenserEventRepositoryInterface::class);
        $logger = $this->createMock(LoggerInterface::class);
        $service = new UpdateStatusDispenserEventService($this->mockDispenserEventRepository, $this->mockDispenserRepository);

        $this->handler = new UpdateStatusDispenserEventHandler($service, $logger);

        parent::setUp();
    }

    public function test_given_data_when_update_status_then_response_ok(): void
    {
        $dispenserId = Uuid::generate();
        $this->mockDispenserRepository->expects($this->once())->method('getById')->willReturn(
            DispenserObjectMother::create($dispenserId->value()),
        );

        ($this->handler)(new UpdateStatusDispenserEventCommand(
            $dispenserId,
            DispenserStatusType::OPEN,
            DateTimeValue::createFromString('2023-06-22T02:00:00Z'),
        ));
    }

    public function test_given_data_when_update_status_then_throw_exception_not_found_dispenser(): void
    {
        $this->mockDispenserRepository->expects($this->once())->method('getById')->willReturn(null);

        $this->expectException(DispenserNotFoundApplicationException::class);

        ($this->handler)(new UpdateStatusDispenserEventCommand(
            Uuid::generate(),
            DispenserStatusType::OPEN,
            DateTimeValue::createFromString('2023-06-22T02:00:00Z'),
        ));
    }

    public function test_given_data_when_update_status_then_throw_exception_already_opened(): void
    {
        $dispenserId = Uuid::generate();
        $this->mockDispenserRepository->expects($this->once())->method('getById')->willReturn(DispenserObjectMother::create($dispenserId->value()));
        $this->mockDispenserEventRepository->expects($this->once())->method('lastOpenedDispenserEventByDispenser')->willReturn(
            DispenserEventObjectMother::create(dispenserId: $dispenserId->value(), openedAt: '2023-06-22T02:00:00Z')
        );

        $this->expectException(DispenserEventAlreadyUpdateSameStatusApplicationException::class);

        ($this->handler)(new UpdateStatusDispenserEventCommand(
            $dispenserId,
            DispenserStatusType::OPEN,
            DateTimeValue::createFromString('2023-06-22T02:00:00Z'),
        ));
    }

    public function test_given_data_when_update_status_then_throw_exception_already_closed(): void
    {
        $dispenserId = Uuid::generate();
        $this->mockDispenserRepository->expects($this->once())->method('getById')->willReturn(DispenserObjectMother::create($dispenserId->value()));
        $this->mockDispenserEventRepository->expects($this->once())->method('lastOpenedDispenserEventByDispenser')->willReturn(
            DispenserEventObjectMother::create(dispenserId: $dispenserId->value(), openedAt: '2023-06-22T02:00:00Z', closedAt: '2023-06-22T02:10:00Z')
        );

        $this->expectException(DispenserEventAlreadyUpdateSameStatusApplicationException::class);

        ($this->handler)(new UpdateStatusDispenserEventCommand(
            $dispenserId,
            DispenserStatusType::CLOSE,
            DateTimeValue::createFromString('2023-06-22T02:00:00Z'),
        ));
    }
}
