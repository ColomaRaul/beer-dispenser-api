<?php
declare(strict_types=1);

namespace App\Tests\Application\Dispenser\Application\Service;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateDispenserHandlerTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
    }

    public function testReturnCreateDispenserResponseOk(): void
    {
        $payload = ['flow_volume' => 0.056];
        $this->client->request('POST', '/api/dispenser', $payload);

        $response = $this->client->getResponse();
        $responseDecoded = json_decode($response->getContent(), true);

        $this->assertIsArray($responseDecoded);
        $this->assertArrayHasKey('flow_volume', $responseDecoded);
        $this->assertArrayHasKey('id', $responseDecoded);
    }
}
