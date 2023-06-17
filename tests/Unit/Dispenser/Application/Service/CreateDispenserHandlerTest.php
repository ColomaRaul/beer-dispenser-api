<?php
declare(strict_types=1);

namespace App\Tests\Unit\Dispenser\Application\Service;

use PHPUnit\Framework\TestCase;

class CreateDispenserHandlerTest extends TestCase
{
    public function testCreateDispenserResponseOk(): void
    {
        $createHandlerDispenser = new CreateDispenserHandler();

        ($createHandlerDispenser)(new CreateDispenserCommand());
    }
}
