<?php

namespace App\Tests\Controller;

use App\Service\ApiClient;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    private $apiClient;

    protected function setUp(): void
    {
        $this->apiClient = $this->createMock(ApiClient::class);
    }

    public function testIndexWithoutQuery()
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Recherche de films');
        $this->assertSelectorNotExists('.movie-item');
        $this->assertSelectorNotExists('.pagination');
    }

    public function testIndexWithQuery()
    {
        $this->apiClient
            ->method('getData')
            ->willReturn([
                'results' => [
                    ['title' => 'Film 1'],
                    ['title' => 'Film 2'],
                ],
                'total_pages' => 1,
                'page' => 1,
            ]);

        $client = static::createClient();

        $client->getContainer()->set(ApiClient::class, $this->apiClient);

        $client->request('GET', '/', ['query' => 'test']);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.movie-item');
        $this->assertAnySelectorTextContains('.movie-item', 'Film 1');
        $this->assertAnySelectorTextContains('.movie-item', 'Film 2');
        $this->assertSelectorExists('.pagination');
    }

    public function testIndexWithApiException()
    {
        $this->apiClient
            ->method('getData')
            ->will($this->throwException(new \Exception('API error')));

        $client = static::createClient();

        $client->getContainer()->set(ApiClient::class, $this->apiClient);

        $client->request('GET', '/', ['query' => 'test']);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.flash-error');
        $this->assertSelectorTextContains('.flash-error', 'Une erreur est survenue lors de la recherche des films.');
    }



    public function testIndexWithBigQuery()
    {
        $this->apiClient
            ->method('getData')
            ->willReturn([
                'results' => array_map(function($i) {
                    return ['title' => "Film $i"];
                }, range(1, 20)),
                'total_pages' => 25,
                'page' => 1,
            ]);

        $client = static::createClient();

        $client->getContainer()->set(ApiClient::class, $this->apiClient);

        $client->request('GET', '/', ['query' => 'test']);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.movie-item');
        $this->assertAnySelectorTextContains('.movie-item', 'Film 1');
        $this->assertAnySelectorTextContains('.movie-item', 'Film 10');
        $this->assertAnySelectorTextContains('.movie-item', 'Film 20');
        $this->assertAnySelectorTextNotContains('.movie-item', 'Film 50');
        $this->assertAnySelectorTextNotContains('.movie-item', 'Film 100');
        $this->assertAnySelectorTextNotContains('.movie-item', 'Film 500');
        $this->assertSelectorExists('.pagination');
        $this->assertAnySelectorTextContains('.page-item', '1');
        $this->assertAnySelectorTextContains('.page-item', '25');
        $this->assertAnySelectorTextContains('.page-item', '...');
        $this->assertAnySelectorTextNotContains('.page-item', '500');
    }
}
