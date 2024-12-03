<?php

namespace Tests\Unit\Services\News\Sources\NewsApi;

use Tests\TestCase;
use App\Services\News\Sources\NewsApi\NewsApiPuller;
use App\Services\News\Sources\NewsApi\NewsApiClient;
use App\Services\News\Sources\NewsApi\NewsApiNewsAdapter;
use DateTimeImmutable;

class NewsApiPullerTest extends TestCase
{
    private NewsApiClient $newsApiClient;
    private NewsApiPuller $newsApiPuller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->newsApiClient = $this->createMock(NewsApiClient::class);
        $this->newsApiPuller = new NewsApiPuller($this->newsApiClient);
    }

    public function testPullWithDefaultPage(): void
    {
        $this->newsApiClient
            ->method('getEverything')
            ->willReturn([
                'articles' => $this->getArticles(),
            ]);

        $news = $this->newsApiPuller->pull();

        $this->assertCount(2, $news);
        $this->assertEquals($this->getArticles()[0]['title'], $news[0]->getTitle());
        $this->assertInstanceOf(NewsApiNewsAdapter::class, $this->newsApiPuller->getAddapter());
    }

    public function testPullWithCustomPage(): void
    {
        $this->newsApiClient
            ->method('getEverything')
            ->willReturn([
                'articles' => $this->getArticles(),
            ]);

        $news = $this->newsApiPuller->pull(null, 2);

        $this->assertCount(2, $news);
        $this->assertInstanceOf(NewsApiNewsAdapter::class, $this->newsApiPuller->getAddapter());
    }

    public function testPullWithPerPageLimit(): void
    {
        $this->newsApiClient
            ->method('getEverything')
            ->willReturn([
                'articles' => $this->getArticles(),
            ]);

        $news = $this->newsApiPuller->pull(null, 1, 150);

        $this->assertCount(2, $news);
        $this->assertInstanceOf(NewsApiNewsAdapter::class, $this->newsApiPuller->getAddapter());
    }

    public function testPullWithDateRange(): void
    {
        $this->newsApiClient
            ->method('getEverything')
            ->willReturn([
                'articles' => $this->getArticles(),
            ]);

        $from = new DateTimeImmutable('2023-01-01');
        $to = new DateTimeImmutable('2023-12-31');
        $news = $this->newsApiPuller->pull(null, 1, 100, null, [], $from, $to);

        $this->assertCount(2, $news);
        $this->assertInstanceOf(NewsApiNewsAdapter::class, $this->newsApiPuller->getAddapter());
    }
}