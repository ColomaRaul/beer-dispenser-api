<?php
declare(strict_types=1);

namespace App\Tests\Application\DispenserEvent\Application\Service;

use App\Shared\Domain\ValueObject\DateTimeValue;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UpdateStatusDispenserEventHandlerTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
    }

    public function testReturnUpdateDispenserEventStatusResponseOk(): void
    {
        $payload = json_encode(['flow_volume' => 0.056]);
        $this->client->request('POST', '/api/dispenser', content: $payload);

        $response = $this->client->getResponse();
        $responseDecoded = json_decode($response->getContent(), true);

        $this->assertIsArray($responseDecoded);
        $this->assertArrayHasKey('flow_volume', $responseDecoded);
        $this->assertArrayHasKey('id', $responseDecoded);

        $dispenserId = $responseDecoded['id'];

        $payload = json_encode(['status' => 'open', 'updated_at' => DateTimeValue::create()->toAtomString()]);
        $this->client->request('PUT', sprintf('/api/dispenser/%s/status', $dispenserId), content: $payload);

        $responseTwo = $this->client->getResponse();

        $this->assertEquals('202', $responseTwo->getStatusCode());
    }
}
