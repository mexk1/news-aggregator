<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use DatabaseMigrations;

    protected function getArticles(): array 
    {
        return [
            [
                'title' => 'Article 1',
                'description' => 'Description 1',
                'content' => 'Content 1',
                'original_url' => 'https://example.com/article-1',
                'original_image_url' => 'https://example.com/article-1.jpg',
                'author' => 'Author 1',
                'publishedAt' => '2021-01-01T00:00:00Z',
                'category' => 'Category 1',
                'source' => [
                    'name' => 'Source 1',
                    'id' => 1
                ]
            ],
            [
                'title' => 'Article 2',
                'description' => 'Description 2',
                'content' => 'Content 2',
                'original_url' => 'https://example.com/article-2',
                'original_image_url' => 'https://example.com/article-2.jpg',
                'author' => 'Author 2',
                'publishedAt' => '2021-01-01T00:00:00Z',
                'category' => 'Category 1',
                'source' => [
                    'name' => 'Source 1',
                    'id' => 1
                ]
            ],
        ];
    }
}
