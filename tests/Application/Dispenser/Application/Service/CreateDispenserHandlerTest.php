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

    public function test_given_correct_data_when_create_dispenser_then_return_ok(): void
    {
        $payload = json_encode(['flow_volume' => 0.056]);
        $this->client->request('POST', '/api/dispenser', content: $payload);

        $response = $this->client->getResponse();
        $responseDecoded = json_decode($response->getContent(), true);

        $this->assertIsArray($responseDecoded);
        $this->assertArrayHasKey('flow_volume', $responseDecoded);
        $this->assertArrayHasKey('id', $responseDecoded);
    }

    public function test_given_wrong_data_when_create_dispenser_then_return_generic_exception(): void
    {
        $payload = json_encode(['flow_volumees' => 0.056]);
        $this->client->request('POST', '/api/dispenser', content: $payload);

        $response = $this->client->getResponse();
        $this->assertEquals('500', $response->getStatusCode());
    }
}
