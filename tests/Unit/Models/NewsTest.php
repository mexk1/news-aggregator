<?php

namespace Tests\Unit;

use App\Models\News;
use App\Models\NewsSource;
use Tests\TestCase;
use DateTime;

class NewsTest extends TestCase
{
    public function test_get_id()
    {
        $news = News::factory()->create();
        $this->assertEquals($news->id, $news->getId());
    }

    public function test_get_external_id()
    {
        $news = News::factory()->create(['external_id' => '12345']);
        $this->assertEquals('12345', $news->getExternalId());
    }

    public function test_get_publish_date()
    {
        $date = new DateTime();
        $news = News::factory()->create(['publish_date' => $date]);
        $this->assertEquals($date, $news->getPublishDate());
    }

    public function test_get_title()
    {
        $news = News::factory()->create(['title' => 'Test Title']);
        $this->assertEquals('Test Title', $news->getTitle());
    }

    public function test_get_content()
    {
        $news = News::factory()->create(['content' => 'Test Content']);
        $this->assertEquals('Test Content', $news->getContent());
    }

    public function test_get_description()
    {
        $news = News::factory()->create(['description' => 'Test Description']);
        $this->assertEquals('Test Description', $news->getDescription());
    }

    public function test_get_original_url()
    {
        $news = News::factory()->create(['original_url' => 'http://example.com']);
        $this->assertEquals('http://example.com', $news->getOriginalUrl());
    }

    public function test_get_original_image_url()
    {
        $news = News::factory()->create(['original_image_url' => 'http://example.com/image.jpg']);
        $this->assertEquals('http://example.com/image.jpg', $news->getOriginalImageUrl());
    }

    public function test_get_author()
    {
        $news = News::factory()->create(['author' => 'John Doe']);
        $this->assertEquals('John Doe', $news->getAuthor());
    }

    public function test_get_categories()
    {
        $news = News::factory()->hasCategories(3)->create();
        $this->assertCount(3, $news->getCategories());
    }

    public function test_get_source()
    {
        $source = NewsSource::factory()->create();
        $news = News::factory()->create(['source_id' => $source->id]);

        $this->assertEquals($source->id, $news->getSource()->id);
    }
}