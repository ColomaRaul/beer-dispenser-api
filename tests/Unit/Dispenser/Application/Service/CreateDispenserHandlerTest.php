<?php
declare(strict_types=1);

namespace App\Tests\Unit\Dispenser\Application\Service;

use App\Dispenser\Application\Command\CreateDispenserCommand;
use App\Dispenser\Application\Service\CreateDispenserHandler;
use App\Dispenser\Domain\Repository\DispenserRepositoryInterface;
use App\Dispenser\Domain\Service\CreateDispenserService;
use App\Shared\Domain\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;

class CreateDispenserHandlerTest extends TestCase
{
    public function testCreateDispenserResponseOk(): void
    {
        $dispenserRepository = $this->createMock(DispenserRepositoryInterface::class);
        $dispenserRepository->expects($this->once())->method('save');
        $createDispenserService = new CreateDispenserService($dispenserRepository);
        $createDispenserHandler = new CreateDispenserHandler($createDispenserService);
        ($createDispenserHandler)(new CreateDispenserCommand(Uuid::from('6a329acf-1bdb-48a8-a73d-72eb19c2f0a2'), 0.1));

        $this->assertTrue(true);
    }

}
