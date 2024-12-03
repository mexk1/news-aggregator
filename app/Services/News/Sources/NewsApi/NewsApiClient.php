<?php 

namespace App\Services\News\Sources\NewsApi;

use \GuzzleHttp\Client;

class NewsApiClient extends Client
{
    public function __construct()
    {
        parent::__construct([
            'base_uri' => config('news_api.base_url'),
            'headers' => [
                'X-Api-Key' => config('news_api.api_key'),
            ],
        ]);
    }
    
    public function getTopHeadlines(array $query): array
    {
        $response = $this->get('top-headlines', compact('query'));
        return json_decode($response->getBody()->getContents(), true);
    }
    
    public function getSources(): array
    {
        $response = $this->get('sources');
        return json_decode($response->getBody()->getContents(), true);
    }
    
    public function getEverything(array $query): array
    {
        $response = $this->get('everything', compact('query'));
        return json_decode($response->getBody()->getContents(), true);
    }
}