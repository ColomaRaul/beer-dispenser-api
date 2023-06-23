<?php
declare(strict_types=1);

namespace App\Tests\Application\DispenserEvent\Application\Service;

use App\Shared\Domain\ValueObject\DateTimeValue;
use App\Shared\Domain\ValueObject\Uuid;
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

    public function test_given_correct_data_when_update_dispenser_event_status_then_return_correct_response(): void
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

    public function test_given_wrong_data_when_update_dispenser_event_status_then_return_conflict_response(): void
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

        $this->client->request('PUT', sprintf('/api/dispenser/%s/status', $dispenserId), content: $payload);
        $responseThree = $this->client->getResponse();
        $this->assertEquals('409', $responseThree->getStatusCode());
    }

    public function test_given_other_dispenser_data_when_update_dispenser_event_status_then_return_not_found_response(): void
    {
        $payload = json_encode(['flow_volume' => 0.056]);
        $this->client->request('POST', '/api/dispenser', content: $payload);

        $response = $this->client->getResponse();
        $responseDecoded = json_decode($response->getContent(), true);

        $this->assertIsArray($responseDecoded);
        $this->assertArrayHasKey('flow_volume', $responseDecoded);
        $this->assertArrayHasKey('id', $responseDecoded);

        $payload = json_encode(['status' => 'open', 'updated_at' => DateTimeValue::create()->toAtomString()]);
        $this->client->request('PUT', sprintf('/api/dispenser/%s/status', Uuid::generate()->value()), content: $payload);

        $responseTwo = $this->client->getResponse();

        $this->assertEquals('404', $responseTwo->getStatusCode());
    }

    public function test_given_malformed_data_when_update_dispenser_event_status_then_return_generic_exception(): void
    {
        $payload = json_encode(['flow_volume' => 0.056]);
        $this->client->request('POST', '/api/dispenser', content: $payload);

        $response = $this->client->getResponse();
        $responseDecoded = json_decode($response->getContent(), true);

        $this->assertIsArray($responseDecoded);
        $this->assertArrayHasKey('flow_volume', $responseDecoded);
        $this->assertArrayHasKey('id', $responseDecoded);

        $payload = json_encode(['status' => 'open', 'updated_at' => DateTimeValue::create()->toAtomString()]);
        $this->client->request('PUT', sprintf('/api/dispenser/%s/status', 'sss'), content: $payload);

        $responseTwo = $this->client->getResponse();

        $this->assertEquals('500', $responseTwo->getStatusCode());
    }
}
