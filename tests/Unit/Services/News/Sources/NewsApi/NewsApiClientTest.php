<?php

namespace Tests\Unit\Services\News\Sources\NewsApi;

use App\Services\News\Sources\NewsApi\NewsApiClient;
use GuzzleHttp\Psr7\Response;
use Tests\TestCase;
use Mockery;

class NewsApiClientTest extends TestCase
{
    public function testConfig(): void 
    {
        config([
            'news_api.base_url' => 'https://newsapi.org/v2/',
            'news_api.api_key' => 'your-api-key',
        ]);
    
        $instance = new NewsApiClient();

        /**
         * @see https://github.com/guzzle/guzzle/issues/3114#issuecomment-1627228395
         */
        $this->assertEquals('https://newsapi.org/v2/', $instance->getConfig('base_uri'));
        $this->assertEquals('your-api-key', $instance->getConfig('headers')['X-Api-Key']);
    }

    public function testGetTopHeadlines()
    {
        $mockResponse = new Response(200, [], json_encode(['status' => 'ok', 'articles' => []]));
        /**
         * @var NewsApiClient $newsApiClient
         */
        $newsApiClient = Mockery::mock(NewsApiClient::class)->makePartial();
        $newsApiClient->shouldReceive('get')
            ->with('top-headlines', ['query' => ['country' => 'us']])
            ->andReturn($mockResponse);

        $result = $newsApiClient->getTopHeadlines(['country' => 'us']);
        $this->assertIsArray($result);
        $this->assertEquals('ok', $result['status']);
    }

    public function testGetSources()
    {
        $mockResponse = new Response(200, [], json_encode(['status' => 'ok', 'sources' => []]));
        /**
         * @var NewsApiClient $newsApiClient
         */
        $newsApiClient = Mockery::mock(NewsApiClient::class)->makePartial();
        $newsApiClient->shouldReceive('get')
            ->with('sources')
            ->andReturn($mockResponse);

        $result = $newsApiClient->getSources();
        $this->assertIsArray($result);
        $this->assertEquals('ok', $result['status']);
    }

    public function testGetEverything()
    {
        $mockResponse = new Response(200, [], json_encode(['status' => 'ok', 'articles' => []]));
        /**
         * @var NewsApiClient $newsApiClient
         */
        $newsApiClient = Mockery::mock(NewsApiClient::class)->makePartial();
        $newsApiClient->shouldReceive('get')
            ->with('everything', ['query' => ['q' => 'bitcoin']])
            ->andReturn($mockResponse);

        $result = $newsApiClient->getEverything(['q' => 'bitcoin']);
        $this->assertIsArray($result);
        $this->assertEquals('ok', $result['status']);
    }
}