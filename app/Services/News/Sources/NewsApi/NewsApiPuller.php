<?php

namespace App\Services\News\Sources\NewsApi;

use App\Services\News\Contracts\Adapter;
use \App\Services\News\Contracts\Puller as PullerContract;
use DateTimeImmutable;

class NewsApiPuller extends PullerContract
{
    public function __construct(private readonly NewsApiClient $newsApiClient)
    {
    }
    
    public function getAddapter(): Adapter
    {
        return new NewsApiNewsAdapter();
    }

    public function pull(
        string $category = null,
        int $page = 1,
        int $perPage = 100,
        ?string $query = null,
        ?array $authors = [],
        ?DateTimeImmutable $from = null,
        ?DateTimeImmutable $to = null,
    ): array {
        $query = compact('category');

        if ($perPage > 100) {
            $perPage = 100;
        }

        $response = $this
            ->newsApiClient
            ->getEverything($query);

        $news = [];
        
        foreach($response['articles'] as $article) {
            $news[] = $this->getAddapter()->adapt($article);
        }

        return $news;
    }
}