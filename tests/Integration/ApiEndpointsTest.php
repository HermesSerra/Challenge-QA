<?php

namespace ChallengeQA\Tests\Integration;

use PHPUnit\Framework\TestCase;

class ApiEndpointsTest extends TestCase
{
    private string $baseUrl = 'http://localhost';

    protected function setUp(): void
    {
        // These tests require the application to be running
        // Run: docker-compose up -d before executing these tests
    }

    public function testHealthEndpoint(): void
    {
        $response = $this->makeRequest('GET', '/health');
        
        $this->assertEquals(200, $response['status_code']);
        $this->assertTrue($response['success']);
        $this->assertArrayHasKey('status', $response['data']);
        $this->assertEquals('ok', $response['data']['status']);
    }

    public function testApiDocsEndpoint(): void
    {
        $response = $this->makeRequest('GET', '/');
        
        $this->assertEquals(200, $response['status_code']);
        $this->assertTrue($response['success']);
        $this->assertArrayHasKey('title', $response['data']);
        $this->assertEquals('Challenge QA API', $response['data']['title']);
        $this->assertArrayHasKey('endpoints', $response['data']);
    }


    private function makeRequest(string $method, string $endpoint, array $data = []): array
    {
        $url = $this->baseUrl . $endpoint;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: ' . strlen(json_encode($data))
            ]);
        }
        
        $response = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            $this->fail("cURL Error: $error");
        }
        
        $decodedResponse = json_decode($response, true);
        
        return [
            'status_code' => $statusCode,
            'success' => $statusCode >= 200 && $statusCode < 300,
            'data' => $decodedResponse,
            'raw_response' => $response
        ];
    }
}