<?php
declare(strict_types=1);

namespace App\Tests\Unit\DispenserEvent\Application\Service;

use App\Shared\Domain\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;

final class UpdateStatusDispenserEventHandlerTest extends TestCase
{
    public function testUpdateStatusDispenserEventResponseOk(): void
    {
        $updateStatusDispenserEventHandler = new UpdateStatusDispenserEventHandler();

        ($updateStatusDispenserEventHandler)(new UpdateStatusDispenserEventCommand(
            Uuid::from('6a329acf-1bdb-48a8-a73d-72eb19c2f0a2'),
            'open',
            '2023-06-22T02:00:00Z'
        ));
    }
}
