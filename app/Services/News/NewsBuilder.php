<?php

namespace App\Services\News;

use App\Models\News as NewsModel;
use App\Services\News\Contracts\NewsCategory;
use App\Services\News\Contracts\NewsSource;
use DateTimeImmutable;

abstract class NewsBuilder
{
    public static function build(
        ?string $title = null,
        ?string $description = null,
        ?string $content = null,
        ?string $author = null,
        ?DateTimeImmutable $publishDate = null,
        ?array $categories = [],
        ?NewsSource $source = null,
    ): NewsModel
    {
        $attributes = compact(
            'title',
            'description',
            'content',
            'author',
            'categories'
        );

        $attributes['publish_date'] = $publishDate;
        
        foreach($attributes['categories'] as $key => $category) {
            if ($category instanceof NewsCategory) {
                continue;
            }

            unset($attributes['categories'][$key]);
        }

        return new NewsModel($attributes);
    }
}
