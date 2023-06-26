<?php
declare(strict_types=1);

namespace App\Tests\Unit\DispenserEvent\Domain\Model;

use App\DispenserEvent\Domain\Exception\DispenserAlreadyUpdateSameStatusDomainException;
use App\Shared\Domain\ValueObject\DateTimeValue;
use App\Shared\Domain\ValueObject\DispenserStatusType;
use App\Shared\Domain\ValueObject\Money;
use PHPUnit\Framework\TestCase;

class DispenserEventTest extends TestCase
{
    public function test_given_dispenser_event_with_null_dates_when_update_to_open_then_response_ok(): void
    {
        $dispenserEvent = DispenserEventObjectMother::create();

        $dispenserEvent->updateStatus(DispenserStatusType::OPEN, DateTimeValue::create());

        $this->assertNotNull($dispenserEvent->openedAt());
        $this->assertTrue($dispenserEvent->isOpen());
    }

    public function test_given_dispenser_event_with_status_open_when_update_status_to_close_then_response_ok(): void
    {
        $dispenserEvent = DispenserEventObjectMother::create(openedAt: '2023-06-22T02:00:00Z');

        $dispenserEvent->updateStatus(DispenserStatusType::CLOSE, DateTimeValue::create());

        $this->assertNotNull($dispenserEvent->closedAt());
        $this->assertTrue($dispenserEvent->isClose());
    }

    public function test_given_dispenser_event_with_status_open_when_update_status_to_open_then_throw_exception(): void
    {
        $dispenserEvent = DispenserEventObjectMother::create(openedAt: '2023-06-22T02:00:00Z');

        $this->expectException(DispenserAlreadyUpdateSameStatusDomainException::class);

        $dispenserEvent->updateStatus(DispenserStatusType::OPEN, DateTimeValue::create());
    }

    public function test_given_dispenser_event_with_status_close_when_update_status_to_close_then_throw_exception(): void
    {
        $dispenserEvent = DispenserEventObjectMother::create(openedAt: '2023-06-22T02:00:00Z', closedAt: '2023-06-22T02:10:00Z');

        $this->expectException(DispenserAlreadyUpdateSameStatusDomainException::class);

        $dispenserEvent->updateStatus(DispenserStatusType::CLOSE, DateTimeValue::create());
    }

    public function test_given_dispenser_event_when_calculate_spent_then_return_correct_value(): void
    {
        $dispenserEvent = DispenserEventObjectMother::create(openedAt: '2023-06-22T02:00:00Z', closedAt: '2023-06-22T02:00:10Z', totalSpent: 0);

        $dispenserEvent->calculateSpent(0.2, Money::from(1225));

        $this->assertEquals(2450, $dispenserEvent->totalSpent()->value());
    }

    public function test_given_dispenser_event_when_calculate_spent_with_open_tap_then_return_correct_value(): void
    {
        $dispenserEvent = DispenserEventObjectMother::create(openedAt: '2023-06-22T02:00:00Z', totalSpent: 0);

        $dispenserEvent->calculateSpent(0.2, Money::from(1225));

        $this->assertGreaterThan(0, $dispenserEvent->totalSpent()->value());
    }
}
