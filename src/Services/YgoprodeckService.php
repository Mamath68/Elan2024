<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class YgoprodeckService
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getCards(int $page = 1, int $num = 8): array
    {
        $response = $this->client->request(
            'GET',
            'https://db.ygoprodeck.com/api/v7/cardinfo.php',
            [
                'query' => [
                    'num' => $num,
                    'offset' => ($page - 1) * $num,
                    'sort' => 'id',
                    'format' => 'tcg'
                ],
            ]
        );

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Failed to fetch data from YGOPRODeck API');
        }

        return $response->toArray();
    }

    public function getCardDetails(int $id): array
    {
        $response = $this->client->request(
            'GET',
            'https://db.ygoprodeck.com/api/v7/cardinfo.php',
            [
                'query' => [
                    'id' => $id,
                ],
            ]
        );

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Failed to fetch data from YGOPRODeck API');
        }

        $data = $response->toArray();
        return $data['data'][0];
    }

    public function getCardsByArchetype(string $archetype, int $page = 1, int $num = 8): array
    {
        $response = $this->client->request(
            'GET',
            'https://db.ygoprodeck.com/api/v7/cardinfo.php',
            [
                'query' => [
                    'archetype' => $archetype,
                    'num' => $num,
                    'offset' => ($page - 1) * $num,
                    'sort' => 'id',
                    'format' => 'tcg'
                ],
            ]
        );

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Failed to fetch data from YGOPRODeck API');
        }

        return $response->toArray();
    }
}
