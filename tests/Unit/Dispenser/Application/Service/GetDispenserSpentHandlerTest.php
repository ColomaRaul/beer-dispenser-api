<?php
declare(strict_types=1);

namespace App\Tests\Unit\Dispenser\Application\Service;

use App\Dispenser\Application\Query\GetDispenserSpentQuery;
use App\Dispenser\Application\Service\GetDispenserSpentHandler;
use App\Dispenser\Domain\Repository\DispenserRepositoryInterface;
use App\Dispenser\Domain\Service\GetDispenserSpentService;
use App\DispenserEvent\Domain\Repository\DispenserEventRepositoryInterface;
use App\Shared\Domain\ValueObject\Money;
use App\Shared\Domain\ValueObject\Uuid;
use App\Tests\Unit\Dispenser\Domain\Model\DispenserObjectMother;
use App\Tests\Unit\DispenserEvent\Domain\Model\DispenserEventObjectMother;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class GetDispenserSpentHandlerTest extends TestCase
{
    private GetDispenserSpentHandler $handler;
    private MockObject $dispenserRepository;
    private MockObject $dispenserEventRepository;

    protected function setUp(): void
    {
        $this->dispenserRepository = $this->createMock(DispenserRepositoryInterface::class);
        $this->dispenserEventRepository = $this->createMock(DispenserEventRepositoryInterface::class);
        $this->handler = new GetDispenserSpentHandler(new GetDispenserSpentService($this->dispenserRepository, $this->dispenserEventRepository));

        parent::setUp();
    }

    public function test_given_dispenser_uuid_when_execute_handler_then_response_ok(): void
    {
        $dispenserId = Uuid::generate();
        $this->dispenserRepository->expects($this->once())->method('getById')->willReturn(DispenserObjectMother::create(id: $dispenserId->value(), amount: Money::from(1234)));
        $this->dispenserEventRepository->expects($this->once())->method('allByDispenser')->willReturn([DispenserEventObjectMother::create(dispenserId: $dispenserId->value(),totalSpent: 0)]);

        $response = ($this->handler)(new GetDispenserSpentQuery($dispenserId));

        $this->assertEquals(1234, $response->amount()->value());
        $this->assertIsArray($response->usages());
    }
}
