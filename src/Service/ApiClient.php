<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiClient
{
    private $client;
    private $apiUrl;
    private $apiKey;

    public function __construct(HttpClientInterface $client, string $apiUrl, string $apiKey)
    {
        $this->client = $client;
        $this->apiUrl = $apiUrl;
        $this->apiKey = $apiKey;
    }

    public function getData(string $endpoint, array $params = []): array
    {
        $response = $this->client->request('GET', $this->apiUrl . '/' . $endpoint, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
            ],
            'query' => $params,
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Error fetching data from API');
        }

        return $response->toArray();
    }
}
