<?php
declare(strict_types=1);

namespace App\Tests\Application\Dispenser\Application\Service;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GetDispenserSpentHandlerTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
    }

    public function test_given_correct_data_when_get_spending_then_return_correct_response(): void
    {
        $payload = json_encode(['flow_volume' => 0.056]);
        $this->client->request('POST', '/api/dispenser', content: $payload);

        $response = $this->client->getResponse();
        $responseDecoded = json_decode($response->getContent(), true);

        $this->assertIsArray($responseDecoded);
        $this->assertArrayHasKey('flow_volume', $responseDecoded);
        $this->assertArrayHasKey('id', $responseDecoded);

        $dispenserId = $responseDecoded['id'];

        $payload = json_encode(['status' => 'open', 'updated_at' => '2023-01-01T00:00:00+00:00']);
        $this->client->request('PUT', sprintf('/api/dispenser/%s/status', $dispenserId), content: $payload);

        $payload = json_encode(['status' => 'close', 'updated_at' => '2023-01-01T00:00:10+00:00']);
        $this->client->request('PUT', sprintf('/api/dispenser/%s/status', $dispenserId), content: $payload);

        $this->client->request('GET', sprintf('/api/dispenser/%s/spending', $dispenserId));

        $response = $this->client->getResponse();
        $responseDecoded = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('amount', $responseDecoded);
        $this->assertArrayHasKey('usages', $responseDecoded);

        $this->assertEquals(1, count($responseDecoded['usages']));
        $this->assertEquals(6.86, $responseDecoded['amount']);
    }

    public function test_given_correct_data_with_still_open_tap_when_get_spending_then_return_correct_response(): void
    {
        $payload = json_encode(['flow_volume' => 0.056]);
        $this->client->request('POST', '/api/dispenser', content: $payload);

        $response = $this->client->getResponse();
        $responseDecoded = json_decode($response->getContent(), true);

        $this->assertIsArray($responseDecoded);
        $this->assertArrayHasKey('flow_volume', $responseDecoded);
        $this->assertArrayHasKey('id', $responseDecoded);

        $dispenserId = $responseDecoded['id'];

        $payload = json_encode(['status' => 'open', 'updated_at' => '2023-01-01T00:00:00+00:00']);
        $this->client->request('PUT', sprintf('/api/dispenser/%s/status', $dispenserId), content: $payload);

        $this->client->request('GET', sprintf('/api/dispenser/%s/spending', $dispenserId));

        $response = $this->client->getResponse();
        $responseDecoded = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('amount', $responseDecoded);
        $this->assertArrayHasKey('usages', $responseDecoded);

        $this->assertEquals(1, count($responseDecoded['usages']));
        $this->assertGreaterThan(0, $responseDecoded['amount']);
    }

    public function test_given_malformed_data_when_spending_then_return_generic_exception(): void
    {
        $dispenserId = '@';
        $this->client->request('GET', sprintf('/api/dispenser/%s/spending', $dispenserId));

        $response = $this->client->getResponse();

        $this->assertEquals('500', $response->getStatusCode());
    }
}
