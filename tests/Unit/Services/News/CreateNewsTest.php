<?php

namespace Tests\Unit\Services\News;

use Tests\TestCase;
use App\Services\News\CreateNews;
use App\Services\News\Contracts\NewsRepository;
use App\Services\News\NewsBuilder;
use App\Services\News\NewsSourceBuilder;
use DateTimeImmutable;

class CreateNewsTest extends TestCase
{
    public function testRunCreatesNews()
    {
        $articles = $this->getArticles();

        $news = [];
        foreach ($articles as $article) {
            $news[] = NewsBuilder::build(
                title: $article['title'],
                description: $article['description'],
                content: $article['content'],
                source: NewsSourceBuilder::build($article['source']['name'], $article['source']['id']),
                publishDate: new DateTimeImmutable($article['publishedAt']),
            );
        }

        $newsRepositoryMock = $this->createMock(NewsRepository::class);
        $newsRepositoryMock->expects($this->exactly(2))
            ->method('createNews')
            ->willReturnOnConsecutiveCalls(...$news);

        $createNewsService = new CreateNews($newsRepositoryMock);

        $result = $createNewsService->run($news);

        $this->assertEquals($news, $result);
    }
}