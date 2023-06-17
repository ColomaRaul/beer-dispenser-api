<?php
declare(strict_types=1);

namespace App\Tests\Unit\Dispenser\Application\Service;

use App\Dispenser\Application\Command\CreateDispenserCommand;
use App\Dispenser\Application\Service\CreateDispenserHandler;
use PHPUnit\Framework\TestCase;

class CreateDispenserHandlerTest extends TestCase
{
    public function testCreateDispenserResponseOk(): void
    {
        $createDispenserHandler = new CreateDispenserHandler();
        ($createDispenserHandler)(new CreateDispenserCommand());

        $this->assertTrue(true);
    }

}
