<?php 

namespace App\Services\News\Sources\NewsApi;

use App\Services\News\Contracts\Adapter as Contract;
use App\Services\News\Contracts\News;
use App\Services\News\NewsBuilder;
use App\Services\News\NewsSourceBuilder;
use DateTimeImmutable;

class NewsApiNewsAdapter extends Contract
{
    public function adapt(array $properties): News
    {
        return NewsBuilder::build(
            title: $properties['title'],
            description: $properties['description'],
            content: $properties['content'],
            source: NewsSourceBuilder::build($properties['source']['name'], $properties['source']['id']),
            publishDate: new DateTimeImmutable($properties['publishedAt']),
            author: $properties['author'],
            categories: [$properties['category']],
        );
    }
    
}
